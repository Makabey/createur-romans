<?php
$sPageTitle = "(demo) mode Édition | ";

include "../assets/inc/header.inc.php";
include "../assets/inc/db_access.inc.php";
?>
<script src="../assets/xhr/xhrFunctions.js"></script>
<script>
/*
TODO: validation du contenu  du texte principal et des entitées pour transformer ou rejetter les balises avant d'envoyer dans la BD, au pire, devrais pouvoir ètre fait dans "editionProjet.xhr.php"

	Le texte principal est lu au chargement de la page puis à chaque "iFrequenceSauvegarde_TextePrincipal"
	millisecondes, si $("#main_write").data("dirtyBit") === true, la sauvegarde du texte principale est faite
	-Aussi- si l'usager clique le bouton, le timeout est tué si $("#main_write").data("dirtyBit") === true et la
	sauvegarde est forcée (pour les tests c'est 5 secs, je sais que c'est inutile/court, je pensais de 60 à 300
	secondes une fois "en ligne")

===chronologie des fonctions:
	-xhrFunctions.js::execXHR_Request ( texte principal [editionProjet.xhr.php] => db_access.php])
	-attente du retour XHR
	-appel à "afficherTextePrincipal"
	-setTimeout de "iFrequenceSauvegarde_TextePrincipal" millisecondes pour appel à sauvegarderTextePrincipal
	-si $("#main_write").data("dirtyBit") === true, la sauvegarde du texte principale est faite, attente XHR
	-xhrFunctions.js::execXHR_Request ( sauvegarde) => appel à lancerDelaiSauvegardeTextePrincipal
*/
	var nroRoman = 1;
	var gbl_DelaiSauvegarde_TextePrincipal = null; // SI on voulais arrêter le cycle d'enregistrement, il suffirais d'utiliser clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
	var iFrequenceSauvegarde_TextePrincipal = 5000; // 5 secs, mériteraia probablement une valeur entre 60 et 300 secs

	$(function(){
		$("#main_write").data("dirtyBit", false);
		$("#main_write").keyup(function(){
			$("#main_write").data("dirtyBit", true);
		});

		$("#btn_save").click(function(){
			/*
				Permet de forcer la sauvegarde SI le contenu as été modifié
			*/
			if($("#main_write").data("dirtyBit") === true){
				clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
				sauvegarderTextePrincipal("main_write");
				console.log("btn_save / DirtyBit :: True");
			}else{
				console.log("btn_save / DirtyBit :: False");
			}
		});

		$("#btn_lireEntites").click(function(){
			/*
				Charger les entitees de type "qui"
			*/
			var typeEntite = "qui";
			execXHR_Request("../assets/xhr/editionProjet.xhr.php", "oper=lire&typeEntite="+typeEntite+"&idRoman="+nroRoman+"&target=container_entites", afficherEntites, traiterErreurs);
		});

		$("#container_entites").on("dblclick",  "div>div>p", function(){
			/*
				Cette fonction s'attache aux balises pointees créés dynamiquement durant la fonction "afficherQuestions"
			*/
			//console.log("#container_entites -> dblclick");
			$(this).attr("contenteditable","true");
		});
		$("#container_entites").on("blur",  "div>div>p", function(){
			/*
				Cette fonction s'attache aux balises pointees créés dynamiquement durant la fonction "afficherQuestions"
			*/
			//console.log("#container_entites -> blur");
			if($(this).attr("contenteditable") == "true"){
				//console.log("était éditable");
				$(this).attr("contenteditable", "false") ;
				var typeEntite = "qui";
				var idEntite = $(this).data("idparent");
				var target = "contenu";
				//console.log(idEntite);
				//console.log($(this).html());
				execXHR_Request("../assets/xhr/editionProjet.xhr.php", "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+nroRoman+"&idEntite="+idEntite+"&target="+target+"&donnees="+$(this).html(), null, traiterErreurs);
			}
		});

		execXHR_Request("../assets/xhr/editionProjet.xhr.php", "oper=lire&typeEntite=textePrincipal&idRoman="+nroRoman, afficherTextePrincipal, traiterErreurs);
	});

	function afficherEntites(donnees){
		/*
			Affiche les entitées contenues dans "donnees", le fait que ce soit les "qui" ne devrais rien changer, le code
			devrais aisément être universel (pour les autres types : quoi, etc...)
		*/
		donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
		console.log(donnees);
		var contenu='';
		var arrDonnees_length = Object.keys(donnees).length; // trouver la taille de "donnees" qui est un hash / Object
		//console.log(arrDonnees_length);
		var curIndex = donnees[0]['first'];
		var typeEntite = "qui";
		//$(donnees[0]['target']).html('');
		for(var iCmpt_Entites=1;iCmpt_Entites<arrDonnees_length;iCmpt_Entites++){
			//console.log(iCmpt_Entites);
			contenu +='<div data-idprev="'+donnees[curIndex]['ID_prev']+'" data-idnext="'+donnees[curIndex]['ID_next'];
			contenu += '" id="contenantEntite-'+typeEntite+'_'+curIndex+'"><div><h4>'+donnees[curIndex]['titre']+"</h4></div>";
			contenu += '<div><p data-idparent="'+curIndex+'">'+donnees[curIndex]['contenu']+'</p></div><div>';
			donnees[curIndex]['note'] = (donnees[curIndex]['note'] == null)?'':donnees[curIndex]['note'];
			contenu += donnees[curIndex]['note']  + '</div></div>';
			curIndex = donnees[curIndex]['ID_next'];
		}
		//console.log(contenu);
		$("#"+donnees[0]['target']).html(contenu);
	}

	function afficherTextePrincipal(contenu){
		$("#balise_attendez").hide();
		contenu = contenu.substring(1, contenu.length-1); // Enlever les guillemets autour du texte
		$("#main_write").text(contenu);
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("main_write")', iFrequenceSauvegarde_TextePrincipal);
	}

	function sauvegarderTextePrincipal(id_balise){
		var texte_encoder = encodeURIComponent ($("#"+id_balise).val());

		$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 255, 0)")?"blue":"yellow");

		if($("#main_write").data("dirtyBit") === true){
			execXHR_Request("../assets/xhr/editionProjet.xhr.php", "oper=ecrire&typeEntite=textePrincipal&idRoman="+nroRoman+"&donnees="+texte_encoder, lancerDelaiSauvegardeTextePrincipal, traiterErreurs);
			console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: True");
		}else{
			gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("main_write")', iFrequenceSauvegarde_TextePrincipal);
			console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: False");
		}
	}

	function lancerDelaiSauvegardeTextePrincipal(msgRetour){
		$("#main_write").data("dirtyBit", false);
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("main_write")', iFrequenceSauvegarde_TextePrincipal);
		$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 0, 0)")?"green":"red");
	}

	function traiterErreurs(msgErreur){
		/*
		Voir appels à "execXHR_Request",
		Sert à traiter l'erreur recue.
		Pour le moment l'erreur la plus commune devrais être "usager team_codeH inexistant"
		ce qui veux dire que l'on doit créer l'usager pour accèder à la BD (ne pas mélanger avec la table "usagers"
		parce que ce n'est pas du tout la même chose), voir le fichier db_access.inc.php pour le mot de passe
		*/
		alert("L'erreur suivante est survenue : '"+msgErreur+"'");
	}

</script>

<form id="form_question" method="post" action="index.php"></form>
<p id="balise_attendez"><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." />Récupération du document...</p>
<textarea id="main_write" form="form_question" cols="80" rows="20"></textarea>
<button type="button" id="btn_save" form="form_question">Sauvegarder</button>
<div id="temoin_activite"></div>
<button type="button" id="btn_lireEntites" form="form_question">Lire Entitées</button>
<div id="container_entites"></div>
<?php
include "../assets/inc/footer.inc.php"
?>

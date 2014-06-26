"use strict";
/*
	Le texte principal est lu au chargement de la page puis à chaque "iFrequenceSauvegarde_TextePrincipal" millisecondes, si $("#main_write").data("dirtyBit") === true, la sauvegarde du texte principale est faite

	-Aussi- si l'usager clique le bouton "sauvegarder", le timeout est tué si $("#main_write").data("dirtyBit") === true et la sauvegarde est forcée (pour les tests c'est 5 secs, je sais que c'est inutile/court, je pensais de 60 à 300 secondes une fois "en ligne")

===chronologie des fonctions:
	-chargerTexte
	-attente du retour XHR
	-appel à "afficherTextePrincipal"
	-setTimeout de "iFrequenceSauvegarde_TextePrincipal" millisecondes pour appel à sauvegarderTextePrincipal
	-si on as tapé dans le textarea contenant le document, $("#main_write").data("dirtyBit") === true, la sauvegarde du texte principale est faite, attente XHR
	-xhrFunctions.js::execXHR_Request ( sauvegarde) => appel à lancerDelaiSauvegardeTextePrincipal
*/

/*
	VALEURS LUES DE php (une fois qu'on aura l'authentification)
*/
var idRoman = 1;

/*
	CONFIGURATION
*/
var gbl_DelaiSauvegarde_TextePrincipal = null; // SI on voulais arrêter le cycle d'enregistrement, il suffirais d'utiliser clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
var iFrequenceSauvegarde_TextePrincipal = 5000; // 5 secs, mériterais probablement une valeur entre 60 et 300 secs

/*
	EVENT HANDLERS
*/
$(function(){
	$("#main_write").data("dirtyBit", false);
	$("#main_write").keyup(function(){
		$("#main_write").data("dirtyBit", true);
	});

	$("#btn_save").click(function(){
		/*
			Permet de forcer la sauvegarde du texte Principal -SI- le contenu as été modifié
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
		var containerEntites = "container_entites";
		lireEntites(afficherEntites, traiterErreurs, idRoman, typeEntite, containerEntites);
	});

	$("#container_entites").on("dblclick", "div>div>p", function(){
		/*
			Cette fonction s'attache aux balises pointees, créés dynamiquement durant la fonction "afficherQuestions"

			Rend l'édition de la balise possible (ici un P)

			Manque :: evènement "on(keyup)" où KEY== touche Échappement, relirais de la BD le contenu OU mieux, on aurait une balise cachée avec un "backup" qui serait remplie ici et vidée (pour conserver de la mémoire) quand on as fini de sauvegarder (voir on(blur) ) ou qu'on tape "on(keyup)==esc"
		*/
		//console.log("#container_entites -> dblclick");
		$(this).attr("contenteditable","true");
	});
	$("#container_entites").on("blur", "div>div>p", function(){
		/*
			Cette fonction s'attache aux balises pointees, créés dynamiquement durant la fonction "afficherQuestions"

			-SI- notre balise est éditable, retire la propriété et enregistre le contenu, qu'il ais été modifié ou non

			Bon, cette fonction me donne du fil à retordre, c'est à dire que j'ai de la difficultée à figurer comment m'assurer des parents en remontant pour savoir de quel type (qui, quoi, etc...) est le parent du présent P (malgré mon truc un peu triche avec "data-idparent");

			DONC en relisant les croquis de vendredi le 20, il semble que la solution est simple : pas de onBlur nécessaire. Ce qui devrais vouloir dire qu'en entrant en "mode 'Éditer' " on est assuré du type d'entité (à cause de l'onglet auquel appartiendra le "contenant") et du coup à quel contenant est attaché chaque balise. Conclusion, l'idée n'est pas du "à la pièce et je peux changer séparément les titre, contenu et note" mais bien du "au bloc où tout est éditable jusqu'à ce qu'on clique le bouton de sauvegarde" MAIS encore là, un onBlur sera pratique pour le cas où l'usager clique ailleurs mais là l'évènement devrais "bubbler" jusqu'au "conteneur" qui s'occupera de s'auto-sauvegarder.
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
			execXHR_Request("../assets/xhr/editionProjet.xhr.php", "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&idEntite="+idEntite+"&target="+target+"&donnees="+$(this).html(), null, traiterErreurs);
			//execXHR_Request("../assets/xhr/editionProjet.xhr.php", "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&idEntite="+idEntite+"&contenu="+contenu+"&titre="+titre+"&note="note, null, traiterErreurs);
		}
	});

	$("#btn_saveEntite").click(function(){
		var typeEntite = "pourquoi";
		var contenu = 'blablablablabla';
		var noteEntite="";
		var titre = "test7";
		insererEntite(insererEntiteRetour, traiterErreurs, idRoman, typeEntite, titre, contenu, noteEntite);
	});

	$("#btn_updEntite").click(function(){
		var typeEntite = "pourquoi";
		var idEntite = 19;
		var titre = "updated5";
		var contenu = 'whacka';
		var noteEntite = "hum";
		modifierEntite(MaJ_EntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, titre, contenu, noteEntite);
	});

	$("#btn_moveEntite").click(function(){
		var typeEntite = "qui";
		var nvTypeEntite = "quoi"; // <-- optionnel, ici pour illustrer que c'est supporté
		var idEntite = 11;
		var id_prev = 13;
		var id_next = 0;

		deplacerEntite(deplacerEntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, id_prev, id_next, nvTypeEntite);
	});

	$("#btn_deleteEntite").click(function(){
		var typeEntite = "quoi";
		var idEntite = 11;
		var etatDeleted = false;
		effacerEntite(deplacerEntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, etatDeleted);
	});

	chargerTexte(afficherTextePrincipal, traiterErreurs, idRoman);
});


/*
	FONCTIONS DE TRAITEMENT DES RETOURS NÉGATIFS
*/
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


/*
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
*/
function insererEntiteRetour(donnees){
	console.log("[insererEntiteRetour] Retour = ' "+donnees+" '");
}

function deplacerEntiteRetour(donnees){
	console.log("[deplacerEntiteRetour] Retour = ' "+donnees+" '");
}

function MaJ_EntiteRetour(donnees){
	console.log("[MaJ_EntiteRetour] Retour = ' "+donnees+" '");
}

function afficherEntites(donnees){
	/*
		Affiche les entitées contenues dans "donnees", le fait que ce soit les "qui" ne devrais rien changer, le code
		devrais aisément être universel (pour les autres types : quoi, etc...)

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"
	*/
	/*
		Préparer les données
	*/
	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	console.log(donnees);
	var contenu='';
	//var arrDonnees_length = Object.keys(donnees).length; // trouver la taille de "donnees" qui est un hash / Object
	//console.log(arrDonnees_length);
	var curIndex = donnees[0]['first'];
	var typeEntite = donnees[0]['typeEntite']; //"qui";
	var baliseParent = "#"+donnees[0]['target'];
	//$(donnees[0]['target']).html('');
	/*
		Créer l'interface dans le parent donnees[0]['target']
	*/
	//for(var iCmpt_Entites=1;iCmpt_Entites<arrDonnees_length;iCmpt_Entites++){
	do{
		//console.log(iCmpt_Entites);
		contenu +='<div data-idprev="'+donnees[curIndex]['ID_prev']+'" data-idnext="'+donnees[curIndex]['ID_next'];
		contenu += '" id="contenantEntite_'+typeEntite+'_'+curIndex+'"><div><h4>'+donnees[curIndex]['titre']+"</h4></div>";
		contenu += '<div><p data-idparent="'+curIndex+'">'+donnees[curIndex]['contenu']+'</p></div><div>';
		donnees[curIndex]['note'] = (donnees[curIndex]['note'] === null)?'':donnees[curIndex]['note'];
		contenu += donnees[curIndex]['note'] + '</div></div>';
		curIndex = donnees[curIndex]['ID_next'];
	//}
	}while(curIndex != 0);
	//console.log(contenu);
	$(baliseParent).html(contenu);
}

function afficherTextePrincipal(contenu){
	$("#balise_attendez").hide();
	contenu = contenu.substring(1, contenu.length-1); // Enlever les guillemets autour du texte
	$("#main_write").text(contenu);
	gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("main_write")', iFrequenceSauvegarde_TextePrincipal);
}

function sauvegarderTextePrincipal(id_balise){
	/*
		Si le texte Principal as été modifié, alors il est sauvegardé.
		L'état de $("#main_write").data("dirtyBit") en décide
	*/

	$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 255, 0)")?"blue":"yellow");

	if($("#main_write").data("dirtyBit") === true){
		sauvegarderTexte(lancerDelaiSauvegardeTextePrincipal, traiterErreurs, idRoman, $("#"+id_balise).val());
		console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: True");
	}else{
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("main_write")', iFrequenceSauvegarde_TextePrincipal);
		console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: False");
	}
}

function lancerDelaiSauvegardeTextePrincipal(msgRetour){
	/*
		Lance un nouveau setTimeout à la fin duquel une tentative de sauvegarde sera faite
	*/
	$("#main_write").data("dirtyBit", false);
	gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("main_write")', iFrequenceSauvegarde_TextePrincipal);
	$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 0, 0)")?"green":"red");
}

/*
	FONCTIONS NOMMÉES DANS LA BD

	== N/A ==
*/

/*
	WRAPPERS
*/
function lireEntites(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, containerEntites){
	var XHR_Query = "oper=lire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&target="+containerEntites;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function modifierEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite, titre, contenu, noteEntite){
	var XHR_Query = "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite+"&idEntite="+idEntite;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function insererEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, titre, contenu, noteEntite){
	var XHR_Query = "oper=inserer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function deplacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite, id_prev, id_next){//, nvTypeEntite){
	/*
		nvTypeEntite : optionnel, si donné déplacera l'entité vers ce nouveau type
	*/
	var nvTypeEntite = (arguments[7])?arguments[7]:null; // dernier parametre, si pas là forcer "null"; facon JS de le faire! :: parametre/argument optionel :: http://www.openjs.com/articles/optional_function_arguments.php
	var XHR_Query ="oper=deplacer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&prev="+id_prev+"&next="+id_next+"&idEntite="+idEntite;

	if(null !== nvTypeEntite){
		XHR_Query += "&nvTypeEntite="+nvTypeEntite
	}

	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function effacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite){
	//console.log("arguments[5]  = "+arguments[5]);
	var etatDeleted = (arguments[5] != undefined)?arguments[5]:1; // argument optionel
	//console.log("arguments[5]  = "+etatDeleted);
	var XHR_Query = "oper=effacer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&idEntite="+idEntite+"&etat="+etatDeleted;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function chargerTexte(fctTraitementPositif, fctTraitementNegatif, idRoman){
	var XHR_Query = "oper=lire&typeEntite=textePrincipal&idRoman="+idRoman;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function sauvegarderTexte(fctTraitementPositif, fctTraitementNegatif, idRoman, nouveauTexte){
	var nouveauTexte = encodeURIComponent (nouveauTexte);
	var XHR_Query = "oper=ecrire&typeEntite=textePrincipal&idRoman="+idRoman+"&contenu="+nouveauTexte;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

/* == EOF == */

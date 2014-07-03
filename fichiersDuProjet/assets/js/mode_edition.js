"use strict";
/*
	Le texte principal est lu au chargement de la page puis à chaque "iFrequenceSauvegarde_TextePrincipal" millisecondes, si $("#"+balise_MainText).data("dirtyBit") === true, la sauvegarde du texte principale est faite

	-Aussi- si l'usager clique le bouton "sauvegarder", le timeout est tué si $("#"+balise_MainText).data("dirtyBit") === true et la sauvegarde est forcée (pour les tests c'est 5 secs, je sais que c'est inutile/court, je pensais de 60 à 300 secondes une fois "en ligne")

===chronologie des fonctions:
	-chargerRoman
	-attente du retour XHR
	-appel à "afficherRoman"
	-setTimeout de "iFrequenceSauvegarde_TextePrincipal" millisecondes pour appel à sauvegarderTextePrincipal
	-si on as tapé dans le textarea contenant le document, $("#"+balise_MainText).data("dirtyBit") === true, la sauvegarde du texte principale est faite, attente XHR
	-xhrFunctions.js::execXHR_Request ( sauvegarde) => appel à lancerDelaiSauvegardeTextePrincipal
*/

/**********************
	CONFIGURATION / VARIABLES GLOBALES
**********************/
var gbl_DelaiSauvegarde_TextePrincipal = null; // SI on voulais arrêter le cycle d'enregistrement, il suffirais d'utiliser clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
var iFrequenceSauvegarde_TextePrincipal = 5000; // 5 secs, mériterais probablement une valeur entre 60 et 300 secs
var balise_MainText = "main_write";
var balises_entites_base = "contenantEntites";
var gblRoman;
var gblEntites = new Array();

/**********************
	EVENT HANDLERS
**********************/
$(function(){
	// Handlers pour le textArea contenant le textePrincipal
	$("#"+balise_MainText).data("dirtyBit", false);
	$("#"+balise_MainText).keyup(function(){
		$("#"+balise_MainText).data("dirtyBit", true);
	});

	$("#btn_save").click(function(){
		/*
			Permet de forcer la sauvegarde du texte Principal -SI- le contenu as été modifié
		*/
		if($("#"+balise_MainText).data("dirtyBit") === true){
			clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
			sauvegarderTextePrincipal(balise_MainText);
			console.log("btn_save / DirtyBit :: True");
		}else{
			console.log("btn_save / DirtyBit :: False");
		}
	});

	/*$("#btn_lireEntites").click(function(){
		/ *
			Charger les entitees de type "qui"
		* /
		var typeEntite = "qui";
		var containerEntites = balises_entites_base + typeEntite;
		lireEntites(afficherEntites, traiterErreurs, idRoman, typeEntite, containerEntites);
	});

	$("#btn_saveEntite").click(function(){
		var typeEntite = "pourquoi";
		var contenu = 'blablablablabla';
		var noteEntite="";
		var titre = "test7";
		insererEntite(insererEntiteRetour, traiterErreurs, idRoman, typeEntite, titre, contenu, noteEntite);
	});

	$("#btn_updEntite").click(function(){
		var typeEntite = "comment";
		var idEntite = 19;
		var titre = "updated5";
		var contenu = 'whacka';
		var noteEntite = "hum";
		modifierEntite(MaJ_EntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, titre, contenu, noteEntite);
	});

	$("#btn_moveEntite").click(function(){
		var typeEntite = "qui";
		var nvTypeEntite = "quoi"; // <-- optionnel, ici pour illustrer que c'est supporté, de pouvoir déplacer de type une entitée
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
	});*/

	if(idRoman > 0){
		$("#balise_MainText").hide();
		chargerRoman(afficherRoman, traiterErreurs, idRoman);
		//var typeEntite = "qui";
		//var containerEntites = balises_entites_base; // + typeEntite;
		lireEntites(afficherEntites, traiterErreurs, idRoman, "qui");//typeEntite);//, containerEntites);
	}else{
		window.location.replace(baseURL+"index.php");
	}
});


/**********************
	WRAPPERS
**********************/
function lireEntites(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite){//, containerEntites){
	var XHR_Query = "oper=lire&typeEntite="+typeEntite+"&idRoman="+idRoman;//+"&target="+containerEntites;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

/*
function modifierEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite, titre, contenu, noteEntite){
	var XHR_Query = "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite+"&idEntite="+idEntite;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function insererEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, titre, contenu, noteEntite){
	var XHR_Query = "oper=inserer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function deplacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite, id_prev, id_next){
	/ *
		nvTypeEntite : optionnel, si donné déplacera l'entité vers ce nouveau type
	* /
	var nvTypeEntite = (arguments[7])?arguments[7]:null; // dernier parametre, si pas là forcer "null"; facon JS de le faire! :: parametre/argument optionel :: http://www.openjs.com/articles/optional_function_arguments.php
	var XHR_Query ="oper=deplacer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&prev="+id_prev+"&next="+id_next+"&idEntite="+idEntite;

	if(null !== nvTypeEntite){
		XHR_Query += "&nvTypeEntite="+nvTypeEntite
	}

	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function effacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite){
	var etatDeleted = (arguments[5] != undefined)?arguments[5]:1; // argument optionel
	var XHR_Query = "oper=effacer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&idEntite="+idEntite+"&etat="+etatDeleted;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}
*/

function chargerRoman(fctTraitementPositif, fctTraitementNegatif, idRoman){
	var XHR_Query = "oper=lire&typeEntite=textePrincipal&idRoman="+idRoman;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function sauvegarderTexte(fctTraitementPositif, fctTraitementNegatif, idRoman, nouveauTexte){
	var nouveauTexte = encodeURIComponent (nouveauTexte);
	var XHR_Query = "oper=ecrire&typeEntite=textePrincipal&idRoman="+idRoman+"&contenu="+nouveauTexte;
	execXHR_Request("../assets/xhr/editionProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}


/*********************
	FONCTIONS GLOBALES
	=N/A=
*********************/


/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
	(C'est à dire, quand la requête XHR s'est complètée correctement. Ici on réagit selon le
	type de la requête, que ce soit par un message de confirmation ou la manipulation des
	données de retour.)
**********************/
/*
function insererEntiteRetour(donnees){
	console.log("[insererEntiteRetour] Retour = ' "+donnees+" '");
}

function deplacerEntiteRetour(donnees){
	console.log("[deplacerEntiteRetour] Retour = ' "+donnees+" '");
}

function MaJ_EntiteRetour(donnees){
	console.log("[MaJ_EntiteRetour] Retour = ' "+donnees+" '");
}
*/

function afficherEntites(donnees){
	/*
		Affiche les entitées contenues dans "donnees"

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"
	*/
	//	Préparer les données
	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	//console.log(donnees);
	var contenu='';
	var curIndex = donnees[0]['first'];
	var typeEntite = donnees[0]['typeEntite'];
	var baliseParent = "#"+balises_entites_base; //+typeEntite; //donnees[0]['target'];

	gblEntites[typeEntite] = donnees;

	if(curIndex !== null){
		// 	Créer l'interface dans le parent donnees[0]['target']
		do{
			/*contenu +='<div data-idprev="'+donnees[curIndex]['ID_prev']+'" data-idnext="'+donnees[curIndex]['ID_next'];
			contenu += '" id="contenantEntite_'+typeEntite+'_'+curIndex+'"><div><h4>'+donnees[curIndex]['titre']+"</h4></div>";
			contenu += '<div><p data-idparent="'+curIndex+'">'+donnees[curIndex]['contenu']+'</p></div><div>';
			donnees[curIndex]['note'] = (donnees[curIndex]['note'] === null)?'':donnees[curIndex]['note'];
			contenu += donnees[curIndex]['note'] + '</div></div>';*/

			contenu += '<div  class="aide-memoire" ';
			//contenu += 'data-idprev="'+donnees[curIndex]['ID_prev']+'" data-idnext="'+donnees[curIndex]['ID_next']+'" ';
			contenu += 'data-idself="'+curIndex+'">';
			contenu += '	<div class="aide-memoire-headings"><span>'+donnees[curIndex]['titre']+'</span></div>';
			contenu += '	<div class="aide-memoire-content">';
			contenu += '		<span>(contenu -&gt;) '+donnees[curIndex]['contenu']+'</span>';
			contenu += '	</div>';
			contenu += '	<div class="aide-memoire-notes">';
			contenu += '		<span>(notes -&gt;) '+donnees[curIndex]['note']+'</span>';
			contenu += '	</div>';
			contenu += '</div>';

			curIndex = donnees[curIndex]['ID_next'];
		}while(curIndex != 0);

		$(baliseParent).html(contenu);
	}else{
		alert("Aucune entitée attachée à ce Roman!");
	}
}

function afficherRoman(donnees){
	//$("#balise_attendez").hide();
	gblRoman = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	console.log(gblRoman);
	if(gblRoman.length !== 0){
		$("#"+balise_MainText).text(gblRoman['contenu']);
		$("h2").text(gblRoman['titre']);
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("'+balise_MainText+'")', iFrequenceSauvegarde_TextePrincipal);
	}else{
		$("#"+balise_MainText).text("[Cet usager n'as aucun Roman ou il y as eût une erreur de BD.]");
	}
	$("#"+balise_MainText).show();
	$("#"+balise_MainText+"~p").hide();
}

function sauvegarderTextePrincipal(id_balise){
	/*
		Si le texte Principal as été modifié, alors il est sauvegardé.
		L'état de $("#"+balise_MainText).data("dirtyBit") en décide
	*/

	$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 255, 0)")?"blue":"yellow");

	if($("#"+balise_MainText).data("dirtyBit") === true){
		sauvegarderTexte(lancerDelaiSauvegardeTextePrincipal, traiterErreurs, idRoman, $("#"+id_balise).val());
		console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: True");
	}else{
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("'+balise_MainText+'")', iFrequenceSauvegarde_TextePrincipal);
		console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: False");
	}
}

function lancerDelaiSauvegardeTextePrincipal(msgRetour){
	/*
		Lance un nouveau setTimeout à la fin duquel une tentative de sauvegarde sera faite
	*/
	$("#"+balise_MainText).data("dirtyBit", false);
	gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("'+balise_MainText+'")', iFrequenceSauvegarde_TextePrincipal);
	$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 0, 0)")?"green":"red");
}


/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS NÉGATIFS
**********************/
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
	FONCTIONS NOMMÉES DANS LA BD

	== N/A ==
*/


/* == EOF == */

/**********************
	Variables globales
**********************/
//var baseURL = "http://createur-romans/fichiersDuProjet/"; // Maison Eric
var baseURL = "http://localhost/GitHub/createur-romans/fichiersDuProjet/"; // ISI
//var baseURL = "http://etscribimus.olirick-tp.site40.net/"; // en ligne


/**********************
	Fonctions communes à toutes les pages
**********************/
"use strict";

function lireListeRomansUsager(fctTraitementPositif, fctTraitementNegatif, ID_usager){
	/*
		Lance la requête pour lire les détails des romans que l'usager as déjà créés

		Appellée dans "assistant_creation.js" et " 'hub_client.js' "
	*/
	var XHR_Query = "oper=lireListeRomans&idUsager=" + ID_usager;
	execXHR_Request("../assets/xhr/assistant_creation.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function effacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, idEntite){
	/*
		Lance la requête pour effacer une entite ou un roman (auquel cas le parametre idEntite DOIT etre "-1"

		Appellée dans "mode_edition.js" et " 'hub_client.js' "
	*/
	var etatDeleted = (arguments[4] !== undefined)?arguments[4]:1; // argument optionel
	var XHR_Query = "oper=effacer&idRoman="+idRoman+"&idEntite="+idEntite+"&etat="+etatDeleted;
	//console.log(XHR_Query);
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function strStripHTML(str){
/*
	Enlève tout le HTML de 'str' et retourne ce qu'il reste
	Sert primairement à ramener les erreurs PHP au message en enlevant tout le formatage.

	source :: http://stackoverflow.com/questions/822452/strip-html-from-text-javascript
*/
	str=str.replace(/<br>/gi, "\n");
	str=str.replace(/<p.*>/gi, "\n");
	str=str.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, " $2 (Link->$1) ");
	str=str.replace(/<(?:.|\s)*?>/g, "");

	return str;
}

/* == EOF == */

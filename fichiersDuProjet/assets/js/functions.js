/**********************
	Variables globales
**********************/
var baseURL = "http://createur-romans/fichiersDuProjet/";  // à changer quand le site sera en ligne

/**********************
	Fonctions communes à toutes les pages
**********************/
"use strict";

function lireListeRomansUsager(fctTraitementPositif, fctTraitementNegatif, ID_usager){
	/*
		Lance la requête pour lire les détails des romans que l'usager as déjà créés

		Appellée dans "assistant_creation.js" et devras l'etre aussi dans " 'hub_client.php' "
	*/
	var XHR_Query = "oper=lireListeRomans&idUsager=" + ID_usager;
	execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
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

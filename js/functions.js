"use strict";

/**********************
	Variables globales
**********************/
/*
  var oLocation = location, aLog = ["Property (Typeof): Value", "location (" + (typeof oLocation) + "): " + oLocation ];
  for (var sProp in oLocation){
    aLog.push(sProp + " (" + (typeof oLocation[sProp]) + "): " +  (oLocation[sProp] || "n/a"));
  }
  alert(aLog.join("\n"));
*/  
/*
location (object): http://site3/porte-folio_github/createur-romans/hub_client.php
href (string): http://site3/porte-folio_github/createur-romans/hub_client.php
origin (string): http://site3
protocol (string): http:
host (string): site3
hostname (string): site3
port (string): n/a
pathname (string): /porte-folio_github/createur-romans/hub_client.php
*/

//var baseURL = "/";
var baseURL = window.location.pathname.split('/');
//console.log(baseURL);
//baseURL[baseURL.length-1] = '';
//console.log(baseURL);
baseURL = window.location.origin + baseURL.splice(0, baseURL.length-1).join('/')+'/';
console.log(baseURL);

/**********************
	Fonctions communes à toutes les pages
**********************/
function lireListeRomansUsager(fctTraitementPositif, fctTraitementNegatif, ID_usager){
	/*
		Lance la requête pour lire les détails des romans que l'usager as déjà créés

		Appellée dans "assistant_creation.js" et " 'hub_client.js' "
	*/
	var XHR_Query = "oper=lireListeRomans&idUsager=" + ID_usager;
	execXHR_Request("xhr/assistant_creation.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function effacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, idEntite){
	/*
		Lance la requête pour effacer une entite ou un roman (auquel cas le parametre idEntite DOIT etre "-1"

		Appellée dans "mode_edition.js" et " 'hub_client.js' "
	*/
	var etatDeleted = (arguments[4] !== undefined)?arguments[4]:1; // argument optionel
	var XHR_Query = "oper=effacer&idRoman="+idRoman+"&idEntite="+idEntite+"&etat="+etatDeleted;

	execXHR_Request("xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
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

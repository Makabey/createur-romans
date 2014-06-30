"use strict";
/**********************
	Variables globales
**********************/
var idUsager = 1; // <<-- passer par WebStorage! :) sinon session PHP :/
var iCmpt=0; // Compteur, global;

/**********************
	EVENT HANDLERS
**********************/
$(function(){
	$("#form_question").submit(function(){
		/*
			Validation par HTML5 : On doit attrapper l'évènement SUBMIT directement sur le FORM
			parce que si on agit sur le CLICK d'un bouton et que le FORM n'est pas valide selon le
			BROWSER, la fonction du bouton est appellée malgré tout.
		*/

		return false;
	});
});


/**********************
	WRAPPERS
**********************/
function lireGenresLitteraires_Noms(fctTraitementPositif, fctTraitementNegatif){
	/*
		Lance la requête pour récupérer de la BD les noms des genres littéraires.
		Une fois accompli, la fonction "fctTraitementPositif" (qui peux porter n'importe quel nom) est appellée.
	*/
	var XHR_Query = "oper=lireGenres";
	execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}




/*********************
	FONCTIONS GLOBALES
*********************/



/*********************
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
	(C'est à dire, quand la requête XHR s'est complètée correctement. Ici on réagit selon le
	type de la requête, que ce soit par un message de confirmation ou la manipulation des
	données de retour.)
*********************/
function felicitationSurCreation(donnees){
	/*
		Affiche un message d'état confirmant le nom du Roman et le nombre d'entitées
		pré-créées en accord avec les types et le nombre de questions pour le genre
		littéraire choisi.

		Ensuite, envoie à la page d'Édition
	*/
	donnees = donnees.split('¤');
	alert(donnees[1]);
	document.location.href="demo_mode_edition.php?idRoman="+donnees[0];
}




/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS NÉGATIFS
**********************/
function traiterErreurs(msgErreur){
	/*
		Voir appels à "execXHR_Request",
		Sert à traiter l'erreur recue.

		Il n'y as que cette fonction parce que je n'ai pas eût un besoin de traitement autre
	*/

	if(msgErreur.substring(0,6) =="<br />"){ // Si commence par '<br />', on suppose que c'est une erreur PHP!
		msgErreur = "[PHP] " + strStripHTML(msgErreur);
	}

	alert("L'erreur suivante est survenue : '"+msgErreur+"'");
}

/* == EOF == */

"use strict";
/**********************
	Variables globales
**********************/
//var idUsager = 1; // <<-- passer par WebStorage! :) sinon session PHP :/
//var iCmpt=0; // Compteur, global;

/**********************
	EVENT HANDLERS
**********************/
$(function(){
	$("#form_login").submit(function(){
		/*
			Validation par HTML5 : On doit attrapper l'évènement SUBMIT directement
			sur le FORM parce que si on agit sur le CLICK d'un bouton et que le FORM
			n'est pas valide selon le BROWSER, la fonction du bouton est appellée
			malgré tout.
		*/
		verifierUsager(authentifierUsager, erreurAuthentification, $("#loginName").val(), $("#loginPwd").val());
		return false;
	});
});


/**********************
	WRAPPERS
**********************/
function verifierUsager(fctTraitementPositif, fctTraitementNegatif, pseudo, motdepasse){
	/*
		Lance la requête pour vérifier si pseudo/motdepasse est une bonne combinaison
	*/
	var XHR_Query = "oper=lire&usager="+pseudo+"&pwd="+motdepasse;
	console.log(XHR_Query);
	execXHR_Request("assets/xhr/index.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
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
function authentifierUsager(){//donnees){
	/*

	*/
	//donnees = donnees.split('¤');
	//alert(donnees[1]);
	console.log(arguments[0]);
	//document.location.href = "index.php";
}


/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS NÉGATIFS
**********************/
/*function traiterErreurs(msgErreur){
	/ *
		Voir appels à "execXHR_Request",
		Sert à traiter l'erreur recue.
	* /

	if(msgErreur.substring(0,6) =="<br />"){ // Si commence par '<br />', on suppose que c'est une erreur PHP!
		msgErreur = "[PHP] " + strStripHTML(msgErreur);
	}

	alert("L'erreur suivante est survenue : '"+msgErreur+"'");
}*/

function erreurAuthentification(msgErreur){
	/*
		Voir appels à "execXHR_Request",
		Sert à traiter l'erreur recue.
	*/
	console.log(msgErreur);
	$("#form_login>div>span").text("Pseudo ou Mot de passe erroné.");
	$("#form_login>div>span").css({color:"#f00"});
	$("#form_login>div").css({"background-color":"#fcc", border:"3px inset #f00", padding:"10px", "margin-bottom":"10px"});
}

/* == EOF == */

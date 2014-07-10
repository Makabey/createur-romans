"use strict";
/**********************
	Variables globales
**********************/
//var gblChoixUsager = new Array(); // retenir les choix de l'usager et accessoirement quelques donnees
var gblParentDesBalises = "maincontent>div>div>div"; // Balise que l'on doit manipuler pour changer l'interface
//var idUsager = 1; // valeur forcée en attendant de la lire par PHP; (auquel cas cette variable ira dans "header.inc.php")
//var etapeAssistant = 0; // À quelle étape de la création nous sommes
//var iCmpt=0; // Compteur, global;

/**********************
	EVENT HANDLERS
**********************/
$(function(){
	$("#"+gblParentDesBalises).on("click", "div", function(){
		var nvID_roman = $(this).data("idroman");
		var XHR_Query = "oper=charger&idRoman="+nvID_roman;
		//console.log(XHR_Query);
		if(nvID_roman === 0){
			console.log(baseURL+"pages/assistant_creation.php");
			window.location.replace(baseURL+"pages/assistant_creation.php");
		}else{
			execXHR_Request("../assets/xhr/hub_client.xhr.php", XHR_Query, chargerTexte, traiterErreurs);
		}
	});

	lireListeRomansUsager(genererLesBoutons, traiterErreurs, idUsager)
});


/**********************
	WRAPPERS
**********************/


/*********************
	FONCTIONS GLOBALES
*********************/
function genererBoutonAjouter(){
	var bouton = "";

	bouton += "<div class=\"col-6 col-sm-6 col-lg-4 btn-roman last-cell\" data-idRoman=\"0\">\n";
	bouton += "<h2>Nouveau</h2>\n";
	bouton += "<span class=\"nouveau_projet\"></span>\n</div>\n";

	//$("#"+gblParentDesBalises).append(bouton);
	return bouton;
}

function genererUnBoutonCharger(donnees){
	var bouton = "";
	var synopsis;
	var synopsis_court;
	var synopsis_court_maxLng = 260;
	var synopsis_court_diff = -1;
	var titre = donnees['titre'];

	if (titre.length > 22){
		titre = donnees['titre'].substring(0, 21) + "...";
	}

	synopsis = donnees['synopsis'];
	synopsis = synopsis.replace(/¤/g, "\n\t-");
	synopsis = synopsis.replace(/¯/g, "\n\n");
	synopsis = synopsis.replace(/"/g, "&quot;");

	synopsis_court = donnees['synopsis'];
	synopsis_court = synopsis_court.replace(/:¤/g, ": ");
	synopsis_court = synopsis_court.replace(/¤/g, ", ");
	synopsis_court = synopsis_court.replace(/¯/g, "; ");
	synopsis_court = synopsis_court.replace(/"/g, "&quot;");

	if(synopsis_court.length > synopsis_court_maxLng){
		synopsis_court = synopsis_court.substring(0, synopsis_court_maxLng + synopsis_court_diff) + "...";
	}

	bouton += "<div class=\"col-6 col-sm-6 col-lg-4 btn-roman genreLit"+donnees['ID_genre']+"\" data-idRoman="+donnees['ID_roman'];
	bouton += ">\n<h2 data-creation=\""+donnees['date_creation']+"\" data-dnrEdition=\""+donnees['date_dnrEdition']+"\" title=\""+donnees['titre']+"\">"+titre+"</h2>\n<p title=\""+synopsis+"\">"+synopsis_court+"</p>\n";
	bouton += "</div>";

	return bouton;
}


/*********************
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
	(C'est à dire, quand la requête XHR s'est complètée correctement. Ici on réagit selon le
	type de la requête, que ce soit par un message de confirmation ou la manipulation des
	données de retour.)
*********************/
function genererLesBoutons(donnees){
	var iCmpt;
	var contenu='';
	//$("#"+gblParentDesBalises).html('');
	//$("#"+gblParentDesBalises).append("");
	if(donnees.length > 0){
		donnees = JSON.parse(donnees);
		if(donnees.length > 0){
			for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
				contenu += genererUnBoutonCharger(donnees[iCmpt]);
			}
		}
	}
	contenu += genererBoutonAjouter();

	$("#"+gblParentDesBalises).html(contenu);
}

function chargerTexte(){
	console.log(baseURL+"pages/mode_edition.php");
	window.location.replace(baseURL+"pages/mode_edition.php");
	//console.log(arguments[0]);
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


/**********************
	FONCTIONS NOMMÉES DANS LA BD
	(ça c'est le contenu du champs `genres_litteraires_questions`.`bouton_fonction`
	la section est vide parce que Thomas n'en as pas eût besoin dans ses questions.
	Pour un exemple, chercher dans les contributions antérieur sur GitHub)
**********************/

/* == EOF == */

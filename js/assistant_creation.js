"use strict";
/**********************
	Variables globales
**********************/
var gblChoixUsager = new Array(); // retenir les choix de l'usager et accessoirement quelques donnees
var gblParentDesBalises = "form_question>fieldset"; // Balise que l'on doit manipuler pour changer l'interface
var etapeAssistant = 0; // À quelle étape de la création nous sommes
var iCmpt=0; // Compteur, global;

/**********************
	EVENT HANDLERS
**********************/
$(function(){
	$("#form_question").submit(function(){
		/*
			Validation par HTML5 : On doit attrapper l'évènement SUBMIT directement sur le FORM parce que si on agit sur le CLICK d'un bouton et que le FORM n'est pas valide selon le BROWSER, la fonction du bouton est appellée malgré tout.

			Si etapeAssistant == 0 :: La page vient d'être chargée, par conséquent on voit le choix de Genres Littéraire et l'étape suivante est le chargement des questions
		*/
		switch(etapeAssistant){
			case 0: // L'usager vient de faire le choix de Genre, on passe aux questions
				// Mettre l'usager en attente
				afficherAttente();

				// Récupérer le choix de l'usager
				if(!gblChoixUsager['genreLitteraire']) {
					gblChoixUsager['genreLitteraire'] = $("#select_question").val();
				}

				// Corriger le titre de la page
				$("h2").text("du genre littéraire '"+gblChoixUsager['genreLitteraire']+"'");

				// Lire et afficher les questions
				lireGenresLitteraires_Questions(afficherQuestions, traiterErreurs, gblChoixUsager['genreLitteraire']);

				etapeAssistant++;
				break;

			case 1: // Les questions sont faites, on demande à l'usager de nommer son roman
				// Mettre l'usager en attente
				afficherAttente();

				// Recopier les choix maintenant, puisque l'interface est chamboulée un peu plus loin
				for(iCmpt=0;iCmpt < gblChoixUsager['questions'].length; iCmpt++){
					gblChoixUsager['questions'][iCmpt]['reponse'] = $("#question"+iCmpt).val();
					gblChoixUsager['questions'][iCmpt]['description'] = $("#description"+iCmpt).val();
				}

				// Lire les noms de Roman de l'usager courant, pour lui éviter de choisir un même nom.
				lireListeRomansUsager(afficherSynopsisEtDemandeNomRoman, traiterErreurs, idUsager);

				etapeAssistant++;
				break;

			case 2:
				// Mettre l'usager en attente
				afficherAttente("Création de votre roman dans la base de données...");

				// Récupérer le titre du Roman
				gblChoixUsager['titreRoman'] = $("#titreRoman").val();

				// Encoder titre et réponses
				var queryString = '';
				queryString += "idUsager="+idUsager;
				queryString += "&titreRoman="+encodeURIComponent (gblChoixUsager['titreRoman']);
				queryString += "&synopsis="+encodeURIComponent (gblChoixUsager['synopsis']);
				queryString += "&genreLitteraire="+gblChoixUsager['genreLitteraire'];
				for(iCmpt=0;iCmpt<gblChoixUsager['questions'].length;iCmpt++){
					queryString += "&question"+iCmpt+"[]="+encodeURIComponent (gblChoixUsager['questions'][iCmpt]['reponse']);
					queryString += "&question"+iCmpt+"[]="+encodeURIComponent (gblChoixUsager['questions'][iCmpt]['description']);
				}

				// Écrire le synopsis et le texte, créer toutes les entitées
				creerLeRoman(felicitationSurCreation, traiterErreurs, queryString);
			break;
		}
		return false;
	});

	// Au lancement de la page, tout de suite charger les genres littéraires...
	afficherAttente();
	lireGenresLitteraires_Noms(afficherGenres, traiterErreurs);
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
	execXHR_Request("xhr/assistant_creation.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function lireGenresLitteraires_Questions(fctTraitementPositif, fctTraitementNegatif, genreLitteraire){
	/*
		Lance la requête pour récupérer de la BD les questions liées à "genreLitteraire".
		Une fois accompli, la fonction "fctTraitementPositif" (qui peux porter n'importe quel nom) est appellée.
	*/
	var XHR_Query = "oper=lireQuestions&genre=" + genreLitteraire;
	execXHR_Request("xhr/assistant_creation.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function creerLeRoman(fctTraitementPositif, fctTraitementNegatif, queryString){
	/*
		Lance la requête pour créer le Roman, ce qui sous-entend: détails, texte et entitees
	*/
	var XHR_Query = "oper=creerLeRoman&" + queryString;
	execXHR_Request("xhr/assistant_creation.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}


/*********************
	FONCTIONS GLOBALES
*********************/
function afficherAttente(){
	/*
		Affiche une phrase et une image invitant l'usager à patienter
		Utilisé juste avant le lancement des requêtes XHR
	*/
	var strMessage = 'Récupération des Données...';
	if(arguments[0] !== undefined) { strMessage = arguments[0]; } // Si on as passé un paramètre à la fonction, l'utiliser comme "strMessage"
	$("#"+gblParentDesBalises).hide(); // cacher le FORM (permettant de quérir les réponses de l'usager)
	$("#button_nextQuestion").hide();
	$("#waitP>span").text(strMessage);
	$("#waitP").show(); // Afficher le bloc d'attente
}

function afficherFormulaire(){
	/*
		Occulter la balise d'attente et afficher le FORM qui permet à l'usager de faire ses choix

		C'est une fonction pour éviter de changer les 3-4 endroits où c'est utilisé
	*/
	$("#"+gblParentDesBalises).show();
	$("#button_nextQuestion").show();
	$("#waitP").hide();
}


/*********************
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
	(C'est à dire, quand la requête XHR s'est complètée correctement. Ici on réagit selon le
	type de la requête, que ce soit par un message de confirmation ou la manipulation des
	données de retour.)
*********************/
function felicitationSurCreation(donnees){
	/*
		Affiche un message d'état confirmant le nom du Roman et le nombre d'entitées pré-créées en accord avec les types et le nombre de questions pour le genre littéraire choisi.

		Ensuite, envoie à la page d'Édition
	*/
	donnees = donnees.split('¤');
	window.location.replace(baseURL+"mode_edition.php");
}

function afficherSynopsisEtDemandeNomRoman(donnees){
	/*
		Construit en parrallèle le SYNOPSIS qui sera enregistré dans la BD (si l'usager passe à l'étape suivate) et celui qui sera affiché. Puis ajoute un court FORM pour demander à l'usager le nom de son oeuvre. Du même coup, l'usager confirme le contenu du synopsis créé par les questions;

		le form doit vérifier realtime que le nom du nouveau Roman n'est pas déjà utilisé pour CET usager ?
	*/
	var synopsis_afficher = '<div><p>Votre synopsis :</p><dl>';
	var contenuDataList = '';

	gblChoixUsager['synopsis'] = '';

	for(iCmpt=0;iCmpt < gblChoixUsager['questions'].length; iCmpt++){
		synopsis_afficher += '<dt>'+gblChoixUsager['questions'][iCmpt]['titre']+'</dt>';
		synopsis_afficher += '<dd>'+gblChoixUsager['questions'][iCmpt]['reponse'];
		if(gblChoixUsager['questions'][iCmpt]['description'].length){
			synopsis_afficher += '; '+gblChoixUsager['questions'][iCmpt]['description'];
		}
		synopsis_afficher += '</dd>'
		gblChoixUsager['synopsis'] += '='+gblChoixUsager['questions'][iCmpt]['titre'] +' :¤'+gblChoixUsager['questions'][iCmpt]['reponse'];
		if(gblChoixUsager['questions'][iCmpt]['description'].length > 0){
			gblChoixUsager['synopsis'] += '¤'+gblChoixUsager['questions'][iCmpt]['description'];
		}

		gblChoixUsager['synopsis'] += '¯';
	}
	synopsis_afficher += '</dl></div>';
	synopsis_afficher += '<div><label for="titreRoman">Quel est le titre de votre roman?</label>';
	synopsis_afficher += '<input type="text" id="titreRoman" required="required" placeholder="Court titre évocateur"';

	// Analyse des données reçues et création du DATALIST si applicable
	gblChoixUsager['romans'] = (donnees.length)?JSON.parse(donnees):'';
	if(gblChoixUsager['romans'].length>0){
		synopsis_afficher += ' list="listeNomsRomans"';
		contenuDataList += '<datalist id="listeNomsRomans">';
		for(iCmpt=0;iCmpt<gblChoixUsager['romans'].length;iCmpt++){
			contenuDataList += '<option>'+gblChoixUsager['romans'][iCmpt]['titre']+'</option>';
		}
		contenuDataList += '</datalist>';
	}

	synopsis_afficher += ' />'+contenuDataList+'</div>'; // La fermeture du input "titreRoman" est ici

	$("#"+gblParentDesBalises).html(synopsis_afficher);
	afficherFormulaire();
}

function afficherGenres(donnees){
	/*
		Traite le retour de la fonction "lireGenresLitteraires_Noms"

		Affiche un "select" lequel permet de sélectionner le Genre Littéraire
	*/
	var iCmpt = 0;
	var sSelections = '<div><label for="select_question">Sélectionnez le genre littéraire désiré :</label><select id="select_question">';

	donnees = JSON.parse(donnees);

	if(donnees.length < 2){
		/*
			Si on as qu'un seul genre, passer la sélection manuelle par l'usager, on "clique" le bouton à sa place.
		*/
		gblChoixUsager['genreLitteraire'] = donnees[0];
		$("#form_question").submit();
	}else{
		// Créer un SELECT pour pouvoir choisir le genre littéraire
		for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
			sSelections += '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
		}
		sSelections += '</select></div>';

		$("#"+gblParentDesBalises).html(sSelections);
		afficherFormulaire();
	}
}

function afficherQuestions(donnees){
	/*
		Afficher les questions lues de la base de données pour le genre littéraire sélectionné plus tôt

		Le champs "donnees[]['type_input']" déterminer s'il faut créer une balise de type "input::text" ou "select"

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"
	*/
	var contenu = null;
	var contenuDataList = '';
	var iCmpt_lignes=0;
	var iCmpt_Options=0;

	donnees = JSON.parse(donnees);
	$("#"+gblParentDesBalises).html('');

	gblChoixUsager['questions'] = new Array();
	for(iCmpt_lignes=0; iCmpt_lignes<donnees.length; iCmpt_lignes++){
		gblChoixUsager['questions'][iCmpt_lignes] = new Array();
		gblChoixUsager['questions'][iCmpt_lignes]['titre'] = donnees[iCmpt_lignes]['forme_synopsis'];
		contenuDataList = '';
		contenu = '<div><label for="question'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['texte']+'</label>';
		if(donnees[iCmpt_lignes]['type_input'] == "text"){
			contenu += '<input type="text" name="questions[]" id="question'+iCmpt_lignes;
			if (donnees[iCmpt_lignes]['suggestions'] !== null){
				donnees[iCmpt_lignes]['suggestions'] = donnees[iCmpt_lignes]['suggestions'] .split('¤');
				if(donnees[iCmpt_lignes]['suggestions'][0].length>0){
					contenu += '" placeholder="'+donnees[iCmpt_lignes]['suggestions'][0];
				}

				if(donnees[iCmpt_lignes]['suggestions'].length>1){
					contenu += '" list = "datalist_question'+iCmpt_lignes;
					for(iCmpt_Options=1; iCmpt_Options<donnees[iCmpt_lignes]['suggestions'].length; iCmpt_Options++){
						contenuDataList += '<option>'+donnees[iCmpt_lignes]['suggestions'][iCmpt_Options]+'</option>';
					}
				}
			}
			contenu += '" placeholder="Tapez ou cliquez pour suggestions.';
			contenu += '" required="required" />';
			if(contenuDataList.length != ''){
				contenu += '<datalist id="datalist_question'+iCmpt_lignes+'">'+contenuDataList+'</datalist>';
			}
		}else if(donnees[iCmpt_lignes]['type_input'] == "select"){
			contenu += '<select name="questions[]" id="question'+iCmpt_lignes+'">';
			donnees[iCmpt_lignes]['suggestions'] = donnees[iCmpt_lignes]['suggestions'] .split('¤');
			for(iCmpt_Options=0; iCmpt_Options<donnees[iCmpt_lignes]['suggestions'].length; iCmpt_Options++){
				contenu += '<option>'+donnees[iCmpt_lignes]['suggestions'][iCmpt_Options]+'</option>';
			}
			contenu += '</select>';
		}

		if(donnees[iCmpt_lignes]['bouton_fonction'] != null){
			donnees[iCmpt_lignes]['bouton_fonction'] = donnees[iCmpt_lignes]['bouton_fonction'].split('¤');
			contenu += '<button type="button" class="bouton_question" data-fonction="'+donnees[iCmpt_lignes]['bouton_fonction'][0]+'" data-question="'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['bouton_fonction'][1]+'</button>';
		}

		contenu += '<textarea id="description'+iCmpt_lignes+'" placeholder="Entrez une courte description si désiré."></textarea>';
		contenu += "</div>";
		$("#"+gblParentDesBalises).append(contenu);
	}
	afficherFormulaire();
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

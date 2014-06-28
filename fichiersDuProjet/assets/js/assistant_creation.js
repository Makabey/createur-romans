"use strict";
/*
	Variables globales
*/
var gblChoixUsager = new Array(); // retenir les choix de l'usager et accessoirement quelques donnees
var gblParentDesBalises = "form_question>fieldset"; // Balise que l'on doit manipuler pour changer l'interface
var idUsager = 1;

/*
	EVENT HANDLERS
*/
$(function(){
	var etapeAssistant = 0;
	var iCmpt=0;

	$("#form_question").submit(function(){
		/*
			On doit attrapper l'évènement SUBMIT directement sur le FORM parce que si on agit sur le
			CLICK d'un bouton et que le FORM n'est pas valide selon le BROWSER, la fonction du
			bouton est appellée malgré tout.
		*/

		/*
			etapeAssistant == 0 :: La page vient d'être chargée, par conséquent on voit le choix de Genre Littéraire et l'étape suivante est le chargement des questions
		*/
		switch(etapeAssistant){
			case 0: // Le choix de Genre est fait, on passe aux questions
				// Mettre l'usager en attente
				afficherAttente();

				// Récupérer le choix de l'usager
				if(!gblChoixUsager['genreLitteraire']) { gblChoixUsager['genreLitteraire'] = $("#select_question").val(); }

				// Corriger le titre de la page
				$("h2").text("du genre littéraire '"+gblChoixUsager['genreLitteraire']+"'");

				// Lire et afficher les questions
				lireGenresLitteraires_Questions(afficherQuestions, traiterErreurs, gblChoixUsager['genreLitteraire']);

				etapeAssistant++;
				break;

			case 1: // Les questions sont faites, on demande à l'usager de nommer son roman
				// Mettre l'usager en attente
				afficherAttente();

				// Recopier les choix + Créer le synopsis; peux être aussi simple que juste un recap
				for(iCmpt=0;iCmpt < gblChoixUsager['questions'].length; iCmpt++){
					gblChoixUsager['questions'][iCmpt]['reponse'] = $("#question"+iCmpt).val();
					gblChoixUsager['questions'][iCmpt]['description'] = $("#description"+iCmpt).val();
				}

				// Lire les noms de Roman de l'usager courant pour lui éviter de se répéter
//				et la fonction DOIT être récupérable pour la page de sélection donc
//				 elle retourne les ID, nom, genre et synopsis
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
				var iCmpt=0;
				queryString += "idUsager="+idUsager;
				queryString += "&titreRoman="+encodeURIComponent (gblChoixUsager['titreRoman']);
				queryString += "&synopsis="+encodeURIComponent (gblChoixUsager['synopsis']);
				queryString += "&genreLitteraire="+gblChoixUsager['genreLitteraire'];
				for(iCmpt=0;iCmpt<gblChoixUsager['questions'].length;iCmpt++){
					queryString += "&question"+iCmpt+"[]="+encodeURIComponent (gblChoixUsager['questions'][iCmpt]['reponse']);
					queryString += "&question"+iCmpt+"[]="+encodeURIComponent (gblChoixUsager['questions'][iCmpt]['description']);
				}

				// Écrire le synopsis et le texte, tout créer les entitées
				creerLeRoman(felicitationSurCreation, traiterErreurs, queryString);
			break;
		}
		return false;
	});


	$("#"+gblParentDesBalises).on("keyup", "#titreRoman", function(){
		//console.log("ca marche!");
		/*
			Mon idée ici était d'avertir l'usager s'il entre le même nom pour ce roman que pour un
			précédent lui-appartenant, juste avec des classes CSS mais je met de côté pour me
			concentrer sur la sauvegarde
		*/
	});
});

/*
	WRAPPERS
*/
function afficherAttente(){
	/*
		Affiche une phrase et une image invitant l'usager à patienter
	*/
	var strMessage = 'Récupération des Données...';
	if(arguments[0] !== undefined) { strMessage = arguments[0]; } // Si on as passé un paramètre à la fonction, l'utiliser
	//$("#"+gblParentDesBalises).html('<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /> '+strMessage+'</p>');
	$("#"+gblParentDesBalises).hide();
	$("#button_nextQuestion").hide();
	$("#waitP>span").text(strMessage);
	$("#waitP").show();
}

function lireGenresLitteraires_Noms(fctTraitementPositif, fctTraitementNegatif){
	/*
		Lance la requête pour récupérer de la BD les noms des genres littéraires.
		Une fois accompli, la fonction "fctTraitementPositif" (qui peux porter n'importe quel nom) est appellée.
	*/
	var XHR_Query = "oper=lireGenres";
	execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function lireGenresLitteraires_Questions(fctTraitementPositif, fctTraitementNegatif, genreLitteraire){
	/*
		Lance la requête pour récupérer de la BD les questions liées à "genreLitteraire".
		Une fois accompli, la fonction "fctTraitementPositif" (qui peux porter n'importe quel nom) est appellée.
	*/
	var XHR_Query = "oper=lireQuestions&genre=" + genreLitteraire;
	execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function lireListeRomansUsager(fctTraitementPositif, fctTraitementNegatif, ID_usager){
	/*
		Lance la requête pour lire les détails des romans que l'usager as déjà créés
	*/
	var XHR_Query = "oper=lireListeRomans&idUsager=" + ID_usager;
	execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function creerLeRoman(fctTraitementPositif, fctTraitementNegatif, queryString){
	/*
		Lance la requête pour lire les détails des romans que l'usager as déjà créés
	*/
	var XHR_Query = "oper=creerLeRoman&" + queryString;
	execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

/*
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
*/
function felicitationSurCreation(donnees){
	//console.log("pas d'erreurs");
	// Envoyer à la page d'Édition
	//console.log("Questions finies!");
	donnees = donnees.split('¤');
	alert(donnees[1]);
	document.location.href="demo_mode_edition.php?idRoman="+donnees[0];
}

function afficherSynopsisEtDemandeNomRoman(donnees){
	// Recopier les choix + Créer le synopsis; peux être aussi simple que juste un recap
		//gblChoixUsager['questions'][iCmpt]['reponse'] = $("#question"+iCmpt).val();
		//gblChoixUsager['questions'][iCmpt]['description'] = $("#description"+iCmpt).val();


	var synopsis_afficher = '<div><p>Votre synopsis :</p><dl>';
	var contenuDataList = '';
	var iCmpt=0;

	gblChoixUsager['synopsis'] = '';
	//console.log(gblChoixUsager['romans'].length);

	//$("#"+gblParentDesBalises).hide();
	for(iCmpt=0;iCmpt < gblChoixUsager['questions'].length; iCmpt++){
		synopsis_afficher += '<dt>'+gblChoixUsager['questions'][iCmpt]['titre']+'</dt>';
		synopsis_afficher += '<dd>'+gblChoixUsager['questions'][iCmpt]['reponse'];
		if(gblChoixUsager['questions'][iCmpt]['description'].length){
			synopsis_afficher += '; '+gblChoixUsager['questions'][iCmpt]['description'];
		}
		synopsis_afficher += '</dd>'
		gblChoixUsager['synopsis'] += '='+gblChoixUsager['questions'][iCmpt]['titre'] +' :\n\t-'+gblChoixUsager['questions'][iCmpt]['reponse']+'\n\t-'+gblChoixUsager['questions'][iCmpt]['description']+'\n\n';
	}
	synopsis_afficher += '</dl></div>';
	synopsis_afficher += '<div><label for="titreRoman">Quel est le titre de votre roman?</label>';
	synopsis_afficher += '<input type="text" id="titreRoman" required="required"';

	var maintenant = new Date();
	//maintenant = maintenant.getFullYear()+"/"+maintenant.getMonth()+"/"+maintenant.getDay();
	synopsis_afficher += ' value = "test - \''+maintenant+'\'"';

	gblChoixUsager['romans'] = (donnees.length)?JSON.parse(donnees):''; // contraire :: JSON.stringify(array);
	if(gblChoixUsager['romans'].length>0){
		synopsis_afficher += ' list="listeNomsRomans"';
		contenuDataList += '<datalist id="listeNomsRomans">';
		for(iCmpt=0;iCmpt<gblChoixUsager['romans'].length;iCmpt++){
			contenuDataList += '<option value="'+gblChoixUsager['romans'][iCmpt]['titre']+'">';
		}
		contenuDataList += '</datalist>';
	}
	
	synopsis_afficher += ' />'+contenuDataList+'</div>';

	/*
		Afficher un form pour demander le nom de l'oeuvre et confirmer le synopsis créé par les
		questions; le form doit vérifier realtime que le nom n'est pas déjà utilisé
		pour CET usager ?
	*/
	$("#"+gblParentDesBalises).html(synopsis_afficher);
	$("#waitP").hide();
	$("#"+gblParentDesBalises).show();
	$("#button_nextQuestion").show();
}

function afficherGenres(donnees){
	/*
		Traite le retour de la fonction "lireGenresLitteraires_Noms"

		Affiche un "select" lequel permet de sélectionner le Genre Littéraire
	*/
	var iCmpt = 0;
	var sSelections = '<div><label for="select_question">Sélectionnez le genre littéraire désiré :</label><select id="select_question">';

	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	//console.log(donnees.length);

	if(donnees.length < 2){ // si on as qu'un seul genre, passer la sélection manuelle par l'usager
		gblChoixUsager['genreLitteraire'] = donnees[0];
		//$("#button_nextQuestion").click();
		$("#form_question").submit();
	}else{
		// Créer un SELECT pour choisir le genre littéraire
		//$("#"+gblParentDesBalises).hide();
		//$("#"+gblParentDesBalises).html('');

		for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
			sSelections += '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
			//sSelections += '<option>'+donnees[iCmpt]+'</option>';
		}
		sSelections += '</select></div>';
		$("#"+gblParentDesBalises).html(sSelections);
		$("#waitP").hide();
		$("#"+gblParentDesBalises).show();
		$("#button_nextQuestion").show();
	}
}

function afficherQuestions(donnees){
	/*
		Traite le retour de execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, afficherQuestions, traiterErreurs);

		Quand rencontre "text" crée une balise input::text et pour select, un select::option

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"
	*/
	var contenu = null;
	var contenuDataList = '';
	var iCmpt_lignes=0;
	var iCmpt_Options=0;
	//var arrNumber2Words = new Array("first", "second", "third", "fourth");
	//var "#"+gblParentDesBalises = "#"+gblParentDesBalises;//+">fieldset";

	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	//gblChoixUsager['donnees'] = donnees;
	//gblChoixUsager['nbrQuestions'] = donnees.length;

	//$("#form_question").html('');
	//$(".form-inner").html('');
	//$("#"+gblParentDesBalises).hide();
	$("#"+gblParentDesBalises).html('');
	gblChoixUsager['questions'] = new Array();
	for(iCmpt_lignes=0; iCmpt_lignes<donnees.length; iCmpt_lignes++){
		gblChoixUsager['questions'][iCmpt_lignes] = new Array();
		gblChoixUsager['questions'][iCmpt_lignes]['titre'] = donnees[iCmpt_lignes]['forme_synopsis'];
		//gblChoixUsager['questions'][iCmpt_lignes]['typeEntite'] = donnees[iCmpt_lignes]['typeEntite']; // sert au moment d'enregistrer le Roman OU on pourrais s'en servir ici pour donner une couleur aux questions, sinon je pourrais aussi bien ne pas envoyer ça ici et le lire de la BD au moment de créer les entitées parce qu'en principe les réponses sont dans l'ordre des questions donc par conséquent je peux savoir de façon assurée leurs type
		contenuDataList = '';
		contenu = '<div><label for="questions'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['texte']+'</label>';
		if(donnees[iCmpt_lignes]['type_input'] == "text"){
			contenu += '<input type="text" name="questions[]" id="question'+iCmpt_lignes;
			if (donnees[iCmpt_lignes]['suggestions'] != null){
				donnees[iCmpt_lignes]['suggestions'] = donnees[iCmpt_lignes]['suggestions'] .split('¤');
				if(donnees[iCmpt_lignes]['suggestions'][0].length>0){
					contenu += '" placeholder="'+donnees[iCmpt_lignes]['suggestions'][0];
				}

				if(donnees[iCmpt_lignes]['suggestions'].length>1){
					contenu += '" list = "datalist_question'+iCmpt_lignes;
					for(iCmpt_Options=1; iCmpt_Options<donnees[iCmpt_lignes]['suggestions'].length; iCmpt_Options++){
						contenuDataList += '<option value="'+donnees[iCmpt_lignes]['suggestions'][iCmpt_Options]+'">';
					}
				}
			}
			contenu += '" value="test';
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
		if(donnees[iCmpt_lignes]['bouton_fonction']!=null){
			donnees[iCmpt_lignes]['bouton_fonction'] = donnees[iCmpt_lignes]['bouton_fonction'].split('¤');
			contenu += '<button type="button" class="bouton_question" data-fonction="'+donnees[iCmpt_lignes]['bouton_fonction'][0]+'" data-question="'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['bouton_fonction'][1]+'</button>';
		}
		//contenu += "<span>"+(iCmpt_lignes+1)+"/"+donnees.length+"</span>";
		contenu += '<textarea id="description'+iCmpt_lignes+'" placeholder="entrez une courte description"></textarea>';
		contenu += "</div>";
		$("#"+gblParentDesBalises).append(contenu);
	}

	$("#waitP").hide();
	$("#"+gblParentDesBalises).show();
	$("#button_nextQuestion").show();
}


/*
	FONCTIONS DE TRAITEMENT DES RETOURS NÉGATIFS
*/
function traiterErreurs(msgErreur){
	/*
	Voir appels à "execXHR_Request",
	Sert à traiter l'erreur recue.
	*/

	if(msgErreur.substring(0,6) =="<br />"){ // On suppose que c'est une erreur PHP!
		msgErreur = "[PHP] " + strStripHTML(msgErreur);
	}
	
	alert("L'erreur suivante est survenue : '"+msgErreur+"'");
}


/*
	FONCTIONS NOMMÉES DANS LA BD
*/

/* == EOF == */

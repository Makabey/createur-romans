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

				// Lire et afficher les questions
				lireGenresLitteraires_Questions(afficherQuestions2, traiterErreurs, gblChoixUsager['genreLitteraire']);

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

				// Écrire le synopsis et le texte, tout créer les entitées
/*				gblChoixUsager['etapeCreation']='textePrincipal'
				gblChoixUsager['etapeCreation']='synopsis'
				gblChoixUsager['etapeCreation']='question'+iCmpt


				-faire une fonction qui s'appelle elle-meme
				-si gblChoixUsager['etapeCreation'] est inexistant alors premier appel, commencer par mettre gblChoixUsager['etapeCreation']='roman' et créer le roman/détails (appel XHR)
				-cette fonction est sa propre fonction de traitement
				-si gblChoixUsager['etapeCreation']='roman' alors mettre gblChoixUsager['etapeCreation']='textePrincipal' et sauver le texte principal
				-si gblChoixUsager['etapeCreation']='textePrincipal', donc écrire gblChoixUsager['etapeCreation']='synopsis' et sauver le synopsis
				-si gblChoixUsager['etapeCreation']='synopsis', donc écrire gblChoixUsager['etapeCreation']=nroQuestion et sauver cette question qui est en fait une entitée
				-si gblChoixUsager['etapeCreation']=nroQuestion alors gblChoixUsager['etapeCreation']=nroQuestion+1 et si < gblChoixUsager['questions'].length alors sauver cette question qui est en fait une entitée
				-si gblChoixUsager['etapeCreation']=nroQuestion+1 >= gblChoixUsager['questions'].length alors aller à la page d'édition!

				= OU =

				Penser comment créer une seule fonction qui sauvegarde tout pour faire un seul appel XHR. ça risque d'être gros mais au moins rapide en terme d'économie d'attente.
				*/
				// Envoyer à la page d'Édition
				alert("Questions finies!");
			break;
		}
		return false;
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

/*
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
*/
function afficherSynopsisEtDemandeNomRoman(donnees){
/*
PLAN:
*-écrire la fonction qui lit les romans avec leur ID a partir du ID_usager
-afficher le synopsis et la possiblité de nommer son roman en plus d'avoir un datalist avec les romans précédents si applicable
-s'il y as des romans précédents, indiquer quand on en choisi un qu'il est pareil ou non à un autre pour éviter que l'auteur se répète
-écrire la fonction qui compacte tout et fait une seule requête XHR pour créer le roman et les entitées de départ
*/


	// Recopier les choix + Créer le synopsis; peux être aussi simple que juste un recap
		//gblChoixUsager['questions'][iCmpt]['reponse'] = $("#question"+iCmpt).val();
		//gblChoixUsager['questions'][iCmpt]['description'] = $("#description"+iCmpt).val();


	var synopsis_afficher = '<div><p>Votre synopsis :</p><dl>';
	var contenuDataList = '';
	var iCmpt=0;

	gblChoixUsager['synopsis'] = '';
	gblChoixUsager['romans'] = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	console.log(gblChoixUsager['romans'].length);

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
	synopsis_afficher += '<input type="text" id="titreRoman"';

	var maintenant = new Date();
	synopsis_afficher += ' value = "test-'+maintenant+'"';

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

/*function afficherGenres(donnees){
	/ *
	Affiche un "select" lequel permet de sélectionner le Genre Littéraire
	* /
	var iCmpt = 0;

	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);

	$("#select_question").html('');
	var sSelections = '';
	for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
		sSelections += '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
	}
	$("#form_question>p").hide();
	$("#select_question").html(sSelections);
	$("#label_question").attr("for", "select_question");
	$("#label_question").text("Sélectionnez un genre littéraire : ");
	$("#label_question").show();
	$("#select_question").show();
	$("#button_nextQuestion").show();
}*/


function afficherGenres2(donnees){
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

/*function afficherQuestions(donnees){
	/ *
	kaduc
		Traite le retour de lireGenreLitteraire_Questions(afficherQuestions2, traiterErreurs, genreChoisi);

		Quand rencontre "text" crée une balise input::text et pour select, un select::option

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"
	* /
	var contenu = null;
	var contenuDataList = '';

	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	$("#form_question").html('');
	for(var iCmpt_lignes=0; iCmpt_lignes<donnees.length; iCmpt_lignes++){
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
					for(var iCmpt_Options=1;iCmpt_Options<donnees[iCmpt_lignes]['suggestions'].length;iCmpt_Options++){
						contenuDataList += '<option>'+donnees[iCmpt_lignes]['suggestions'][iCmpt_Options]+'</option>';
					}
				}
			}
			contenu += '" />';
			if(contenuDataList.length != ''){
				contenu += '<datalist id="datalist_question'+iCmpt_lignes+'">'+contenuDataList+'</datalist>';
			}
		}else if(donnees[iCmpt_lignes]['type_input'] == "select"){
			contenu += '<select name="questions[]" id="question'+iCmpt_lignes+'">';
			donnees[iCmpt_lignes]['suggestions'] = donnees[iCmpt_lignes]['suggestions'] .split('¤');
			for(var iCmpt_Options=0;iCmpt_Options<donnees[iCmpt_lignes]['suggestions'].length;iCmpt_Options++){
				contenu += '<option>'+donnees[iCmpt_lignes]['suggestions'][iCmpt_Options]+'</option>';
			}
			contenu += '</select>';
		}
		if(donnees[iCmpt_lignes]['bouton_fonction']!=null){
			donnees[iCmpt_lignes]['bouton_fonction'] = donnees[iCmpt_lignes]['bouton_fonction'].split('¤');
			contenu += '<button type="button" class="bouton_question" data-fonction="'+donnees[iCmpt_lignes]['bouton_fonction'][0]+'" data-question="'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['bouton_fonction'][1]+'</button>';
		}
		contenu += "<span>"+(iCmpt_lignes+1)+"/"+donnees.length+"</span></div>";
		$("#form_question").append(contenu);
	}
	$("#button_nextQuestion").show();
}*/

function afficherQuestions2(donnees){
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
		gblChoixUsager['questions'][iCmpt_lignes]['typeEntite'] = donnees[iCmpt_lignes]['typeEntite'];
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
	Pour le moment l'erreur la plus commune devrais être "usager team_codeH inexistant"
	ce qui veux dire que l'on doit créer l'usager pour accèder à la BD (ne pas mélanger avec la table "usagers"
	parce que ce n'est pas du tout la même chose), voir le fichier db_access.inc.php pour le mot de passe
	*/
	alert("L'erreur suivante est survenue : '"+msgErreur+"'");
}


/*
	FONCTIONS NOMMÉES DANS LA BD
*/

/* == EOF == */

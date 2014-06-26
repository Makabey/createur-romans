"use strict";
/*
	Variables globales
*/
var gblChoixUsager = new Array(); // retenir les choix de l'usager et accessoirement quelques donnees
var gblParentDesBalises = "form_question>fieldset"; // Balise que l'on doit manipuler pour changer l'interface

/*
	EVENT HANDLERS
*/
$(function(){
	var etapeAssistant = 0;

	$("#button_nextQuestion").click(function(){
		/*
			etapeAssistant == 0 :: La page vient d'être chargée, par conséquent on voit le choix de Genre Littéraire et l'étape suivante est le chargement des questions
		*/
		switch(etapeAssistant){
			case 0:
				// Récupérer le choix de l'usager
				gblChoixUsager['genreLitteraire'] = $("#select_question").val();

				// Mettre l'usager en attente
				afficherAttente();

				// Lire et afficher les questions
				lireGenresLitteraires_Questions(afficherQuestions2, traiterErreurs, gblChoixUsager['genreLitteraire']);

				etapeAssistant++;
				break;
			case 1:
				// Recopier les choix + Créer le synopsis; peux être aussi simple que juste un recap
				var synopsis = '<div><p>Votre synopsis :</p><dl>';
				for(var iCmpt=0;iCmpt < gblChoixUsager['questions'].length; iCmpt++){
					gblChoixUsager['questions'][iCmpt]['choix'] = $("#question"+iCmpt).val();
					gblChoixUsager['questions'][iCmpt]['note'] = $("#description"+iCmpt).val();
					synopsis += '<dt>'+gblChoixUsager['questions'][iCmpt]['synopsis']+'</dt>';
					synopsis += '<dd>'+gblChoixUsager['questions'][iCmpt]['choix'];
					if(gblChoixUsager['questions'][iCmpt]['note'].length){
						synopsis += '; '+gblChoixUsager['questions'][iCmpt]['note'];
					}
					synopsis += '</dd>'
				}
				synopsis += '</dl></div>';
				synopsis += '<div><label for="titreRoman">Quel est le titre de votre roman?</label>';
				synopsis += '<input type="text" id="titreRoman" /></div>';

				
				$("#"+gblParentDesBalises).html(synopsis);
				
				// Mettre l'usager en attente
				//afficherAttente();

				// Afficher un form pour demander le nom de l'oeuvre et confirmer le synopsis créé par les questions; le form doit vérifier realtime que le nom n'est pas déjà utilisé pour CET usager


				etapeAssistant++;
				break;
			case 2:
				// Mettre l'usager en attente
				afficherAttente();

				// Écrire le synopsis et le texte, tout créer les entitées
				gblChoixUsager['etapeCreation']='textePrincipal'
				gblChoixUsager['etapeCreation']='synopsis'
				gblChoixUsager['etapeCreation']='question'+iCmpt
				
				
				-faire une fonction qui s'appelle elle-meme
				-si gblChoixUsager['etapeCreation'] est inexistant alors premier appel, commencer par mettre gblChoixUsager['etapeCreation']='roman' et créer le roman/détails (appel XHR)
				-cette fonction est sa propre fonction de traitement
				-si gblChoixUsager['etapeCreation']='roman'  alors mettre gblChoixUsager['etapeCreation']='textePrincipal' et sauver le texte principal 
				-si gblChoixUsager['etapeCreation']='textePrincipal', donc écrire gblChoixUsager['etapeCreation']='synopsis' et sauver le synopsis
				-si gblChoixUsager['etapeCreation']='synopsis', donc écrire gblChoixUsager['etapeCreation']=nroQuestion et sauver cette question qui est en fait une entitée
				-si gblChoixUsager['etapeCreation']=nroQuestion alors gblChoixUsager['etapeCreation']=nroQuestion+1 et si < gblChoixUsager['questions'].length alors sauver cette question qui est en fait une entitée
				-si gblChoixUsager['etapeCreation']=nroQuestion+1 >= gblChoixUsager['questions'].length alors aller à la page d'édition!
				
				= OU =
				
				Penser comment créer une seule fonction qui sauvegarde tout pour faire un seul appel XHR. ça risque d'être gros mais au moins rapide en terme d'économie d'attente.
				
				// Envoyer à la page d'Édition
				alert("Questions finies!");
			break;
		}
	});

});

/*
	WRAPPERS
*/
function afficherAttente(){
	/*
		Affiche une phrase et une image invitant l'usager à patienter
	*/
	$("#"+gblParentDesBalises).html('<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /> Récupération des Données...</p>');
	$("#button_nextQuestion").hide();
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


/*
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
*/
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
	//var arrNumber2Words = new Array("first", "second", "third", "fourth");
	//var "#"+gblParentDesBalises = "#"+gblParentDesBalises;//+">fieldset";

	donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	//gblChoixUsager['donnees'] = donnees;
	//gblChoixUsager['nbrQuestions'] = donnees.length;

	//$("#form_question").html('');
	//$(".form-inner").html('');
	$("#"+gblParentDesBalises).html('');
	gblChoixUsager['questions'] = new Array();
	for(var iCmpt_lignes=0; iCmpt_lignes<donnees.length; iCmpt_lignes++){
		gblChoixUsager['questions'][iCmpt_lignes] = new Array();
		gblChoixUsager['questions'][iCmpt_lignes]['synopsis'] = donnees[iCmpt_lignes]['forme_synopsis'];
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
						contenuDataList += '<option value="'+donnees[iCmpt_lignes]['suggestions'][iCmpt_Options]+'" />';
					}
				}
			}
			contenu += '" required="required" />';
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
		//contenu += "<span>"+(iCmpt_lignes+1)+"/"+donnees.length+"</span>";
		contenu += '<textarea id="description'+iCmpt_lignes+'" placeholder="entrez une courte description"></textarea>';
		contenu += "</div>";
		$("#"+gblParentDesBalises).append(contenu);
	}
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

	$("#"+gblParentDesBalises).hide();
	//$("#"+gblParentDesBalises).html('');

	for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
		sSelections += '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
	}
	sSelections += '</select></div>';
	$("#"+gblParentDesBalises).html(sSelections);
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

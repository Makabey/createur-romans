<?php
$sPageTitle = "Acceuil | ";

include "assets/inc/header.inc.php";
include "assets/inc/db_access.inc.php";
?>
<script src="assets/xhr/xhrFunctions.js"></script>
<script>
	$(function(){
		var donneesQuestions = null;
		var etapeAssistant = 0;

		$("#button_nextQuestion").click(function(){
			if(etapeAssistant == 0){
				var XHR_Query = "etape=lireQuestions&genre=" + $("#select_question").val();
				//console.log(XHR_Query);
				execXHR_Request("assets/xhr/creationProjet.xhr.php", XHR_Query, demarrerSequenceQuestions);
			}else if(etapeAssistant > 0){
				etapeAssistant++;
				afficherQuestion();
			}else{
				console.log("[button_nextQuestion.click] Erreur d'exécution!");
			}
		});

		function hideAll(){
			$("#button_question").hide();
			$("#select_question").hide();
			$("#input_question").hide();
			$("#label_question").hide();
			$("#button_nextQuestion").hide();
			//$("#compte_question").hide();
		}

		function afficherGenres(donnees){
			/*
			Affiche un "select" lequel permet de sélectionner le Genre Littéraire
			*/
			var iCmpt = 0;

			$("#select_question").html('');
			var sSelections = '';
			for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
				sSelections +=  '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
			}
			$("#select_question").html(sSelections);
			$("#label_question").attr("for", "select_question");
			$("#label_question").text("Sélectionnez un genre littéraire : ");
			$("#label_question").show();
			$("#select_question").show();
			$("#button_nextQuestion").show();
		}

		function demarrerSequenceQuestions(donnees){
			/*
			Cette fonction est appellée dès qu'on as sélectionné un Genre Littéraire
			*/
			donneesQuestions = donnees;
			etapeAssistant++;
			afficherQuestion();
		}

		function afficherQuestion(){
			/*
			Appellée pour chaque questions
			*/
			if(etapeAssistant > donneesQuestions.length){
				alert("C'était la dernière question!"); /* On s'attend ici plus à un code de style document.location.href="nouvellePage", jQuery peux le faire */
			}else{
				hideAll();
				if(donneesQuestions[etapeAssistant-1]['type_input'] == "text"){
						/*
						- pour input="text" :
							'nom' => le nom du genre tel qu'affiché à l'usager
							'nro_question' => position de la question pour ce genre, c'est à dire que chaque genre as une question #1
							'texte' => texte de la question/qui doit apparaitre dans le label du form
							'type_input' => 'text' pour cette option (autre possibilité : 'select')
							'valeurs_defaut' => vide/NULL =ou= placeholder¤option1¤option2¤optionN-1 ex: "entrez un nom" =ou= "tapez une lettre pour inspiration¤Jack¤Sammy¤Tim" -> 'Jack¤Sammy¤Tim' deviennent 3 choix dans un datalist =ou= "¤choix1¤choix2" autrement dit, le placeholder est optionnel
							'bouton_fonction => vide/NULL =ou= si on veux une fonction attachée comme par exemple générer un nom de X à Y lettres "generer_nom(4,6)¤Générer un Nom", la partie après le "¤" est l'étiquette
						*/
					if(donneesQuestions[etapeAssistant-1]['valeurs_defaut'] != null){
						donneesQuestions[etapeAssistant-1]['valeurs_defaut']  = donneesQuestions[etapeAssistant-1]['valeurs_defaut'] .split('¤');
						$("#input_question").attr("placeholder", donneesQuestions[etapeAssistant-1]['valeurs_defaut'][0]);

						if(donneesQuestions[etapeAssistant-1]['valeurs_defaut'].length > 1){
							$("#datalist_question").html('');
							var sSelections = '';
							for(var iCmpt=1;iCmpt<donneesQuestions[etapeAssistant-1]['valeurs_defaut'].length;iCmpt++){
								sSelections += '<option>'+donneesQuestions[etapeAssistant-1]['valeurs_defaut'][iCmpt]+'</option>';
							}
							$("#datalist_question").html(sSelections);
						}
					}

					if(donneesQuestions[etapeAssistant-1]['bouton_fonction'] != null){
						donneesQuestions[etapeAssistant-1]['bouton_fonction'] = donneesQuestions[etapeAssistant-1]['bouton_fonction'].split('¤');
						$("#button_question").click(function(){
							donneesQuestions[etapeAssistant-1]['bouton_fonction'][0];
						});
						$("#button_question").text(donneesQuestions[etapeAssistant-1]['bouton_fonction'][1]);
						$("#button_question").show();
					}

					$("#label_question").attr("for", "input_question");
					$("#label_question").text(donneesQuestions[etapeAssistant-1]['texte']);
					$("#input_question").show();
					$("#label_question").show();
					$("#button_nextQuestion").show();
				}else if(donneesQuestions[etapeAssistant-1]['type_input'] == "select"){
					/*
					- pour input="select" :
						'nom' => le nom du genre tel qu'affiché à l'usager
						'nro_question' => position de la question pour ce genre, c'est à dire que chaque genre as une question #1
						'texte' => texte de la question/qui doit apparaitre dans le label du form
						'type_input' => 'select' pour cette option (autre possibilité : 'text')
						'valeurs_defaut' => ne peux PAS etre vide,  liste des options séparée par des "¤" (la valeur sera la position numérique dans la liste d'options, au moment d'afficher on reprend le texte(?))
						'bouton_fonction => vide/NULL, ne sert pas pour les 'select'
					*/
					if(donneesQuestions[etapeAssistant-1]['valeurs_defaut'] != null){
						donneesQuestions[etapeAssistant-1]['valeurs_defaut']  = donneesQuestions[etapeAssistant-1]['valeurs_defaut'] .split('¤');

						var sSelections = '';
						for(var iCmpt=0;iCmpt<donneesQuestions[etapeAssistant-1]['valeurs_defaut'].length;iCmpt++){
							sSelections += '<option>'+donneesQuestions[etapeAssistant-1]['valeurs_defaut'][iCmpt]+'</option>';
						}
						$("#select_question").html(sSelections);

						$("#label_question").attr("for", "select_question");
						$("#label_question").text(donneesQuestions[etapeAssistant-1]['texte']);
						$("#select_question").show();
						$("#label_question").show();
						$("#button_nextQuestion").show();
					}else{
						// Erreur! 'valeurs_defaut' => ne peux PAS etre vide, On passe à la question suivante...
						etapeAssistant++;
						afficherQuestion();
					}
				}else{
					console.log("[afficherQuestion] Erreur d'affichage!");
				}
			}
		}

		function generer_nom(min_chars, max_chars, word_count){
			console.log("'generer_nom' appellé, params = "+min_chars+", "+max_chars+", "+word_count);
		}

		hideAll();
		execXHR_Request("assets/xhr/creationProjet.xhr.php", "etape=lireGenres", afficherGenres);
	});
</script>

<div id="reponses_questions"></div>
<p>imaginer ici un truc qui tourne --&gt; <img src="assets/images/wait_circle2.png" alt="Attendez..." /> &lt;-- en attendant que XHR revienne avec les "genre littéraires" et ensuite sa disparait pour laisser le premier select apparaitre</p>
<form>
<!-- <label id="label_choixGenre">Choississez le style du roman : <select id="choixGenre"></select> -->
<label id="label_question"></label><input id="input_question" type="text" list="datalist_question" value="" placeholder="" /><select id="select_question"></select><span id="compte_question"></span>
<button type="button" id="button_question"></button><button type="button" id="button_nextQuestion">Suivant</button>
<datalist id="datalist_question"></datalist>
</form>
<?php
include "assets/inc/footer.inc.php"
?>
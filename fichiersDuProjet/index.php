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
				console.log(XHR_Query);
				execXHR_Request("assets/xhr/creationProjet.xhr.php", XHR_Query, demarrerSequenceQuestions);
			}else if(etapeAssistant > 0){
				etapeAssistant++;
				afficherQuestion();
			}else{
				console.log("[button_nextQuestion.click] Erreur d'exécution!");
			}
		});
	
		function hideAll(){
			$("#button_question").hide()
			$("#select_question").hide();
			$("#input_question").hide();
			$("#label_question").hide();
			$("#button_nextQuestion").hide();
			//$("#compte_question").hide();
		}
		
		function afficherGenres(donnees){
			var iCmpt = 0;
			//console.log('ok ca marche');
			//console.log("Retour = '"+donnees+"'");
			//console.log(donnees[1]);
			$("#select_question").html('');
			var sSelections = '';
			for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
				//console.log(donnees[iCmpt]);
				//$("#select_question").html($("#select_question").html() + '<option value="'+iCmpt+'">'+donnees[iCmpt]+'</option>');
				//iCmpt++;
				sSelections +=  '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
			}
			$("#select_question").html(sSelections);
			$("#label_question").text="Sélectionnez un genre littéraire : ";
			$("#label_question").show();
			$("#select_question").show();
			$("#button_nextQuestion").show();
		}
		
		function demarrerSequenceQuestions(donnees){
			donneesQuestions = donnees;
			etapeAssistant++;
			afficherQuestion();
		}
		
		function afficherQuestion(){
			if(etapeAssistant == donneesQuestions.length){
				console.log("C'était la dernière question!");
			}else{
				hideAll();
				if(donneesQuestions[etapeAssistant-1]['type_input'] == "text"){
					//$("#button_question").hide()
					//$("#select_question").hide();
					if(donneesQuestions[etapeAssistant-1]['valeurs_defaut'] != ''){
						donneesQuestions[etapeAssistant-1]['valeurs_defaut']  = donneesQuestions[etapeAssistant-1]['valeurs_defaut'] .split('¤');
						$("#input_question").val(donneesQuestions[etapeAssistant-1]['valeurs_defaut'][0]);
						if(donneesQuestions[etapeAssistant-1]['valeurs_defaut'].length > 1){
							$("#input_question").attr("placeholder", donneesQuestions[etapeAssistant-1]['valeurs_defaut'][1]);
						}
						
						if(donneesQuestions[etapeAssistant-1]['valeurs_defaut'].length > 2){
							$("#datalist_question").html('');
							var sSelections = '';
							for(var iCmpt=2;iCmpt<donneesQuestions[etapeAssistant-1]['valeurs_defaut'].length;iCmpt++){
								sSelections += '<option>'+donneesQuestions[etapeAssistant-1]['valeurs_defaut'][iCmpt]+'</option>';
							}
							$("#datalist_question").html(sSelections);
						}
						
						/*
						- pour input="text" : chaine et si elle comporte un "¤" alors tout ce qui le suit est un 'placeholder', s'il y as encore un "¤" alors on suppose que ce qui suit sont des valeurs pour un datalist, ex: "jaune¤couleur du pantalon¤rouge¤vert¤noir "
						*/
					}
					$("#label_question").text(donneesQuestions[etapeAssistant-1]['texte']);
					//$("#button_nextQuestion").hide();
					
					
					//$("#button_question").hide()
					//$("#select_question").hide();
					$("#input_question").show();
					$("#label_question").show();
					//$("#button_nextQuestion").hide();
				}else if(donneesQuestions[etapeAssistant-1]['type_input'] == "select"){
					/*
					- pour select : liste séparée par des "¤" (la valeur sera la position numérique dans la liste d'options, au moment d'afficher on reprend le texte(?))
					*/
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
<label id="label_question"><input id="input_question" type="text" list="datalist_question" value="" placeholder="Entrez son nom" /><select id="select_question"></select></label><span id="compte_question"></span>
<button type="button" id="button_question">Générer</button><button type="button" id="button_nextQuestion">Suivant</button>
<datalist id="datalist_question"></datalist>
</form>
<?php
include "assets/inc/footer.inc.php"
?>
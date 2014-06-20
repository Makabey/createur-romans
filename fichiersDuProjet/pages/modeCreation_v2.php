<?php
$sPageTitle = "Acceuil | ";

include "../assets/inc/header.inc.php";
include "../assets/inc/db_access.inc.php";
?>
<script src="../assets/xhr/xhrFunctions.js"></script>
<script>
	$(function(){
		var donneesQuestions = null;
		var etapeAssistant = 0;

		$("#button_nextQuestion").click(function(){
			if(etapeAssistant == 0){
				var XHR_Query = "etape=lireQuestions&genre=" + $("#select_question").val();
				//console.log(XHR_Query);
				execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, afficherQuestions, traiterErreurs);
			}else if(etapeAssistant > 0){
				//etapeAssistant++;
				//afficherQuestion();
			}else{
				console.log("[button_nextQuestion.click] Erreur d'exécution!");
			}
		});

		function traiterErreurs(msgErreur){
			alert("L'erreur suivante est survenue : '"+msgErreur+"'");
		}
		
		function afficherQuestions(donnees){
			donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
			$("#form_question").html('');
			var contenu=null;
			//var sSelections = '';
			for(var iCmpt_lignes=0; iCmpt_lignes<donnees.length; iCmpt_lignes++){
				contenu = '<div><label for="questions'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['texte']+'</label>';
				if(donnees[iCmpt_lignes]['type_input'] == "text"){
					contenu += '<input type="text" name="questions[]" id="question'+iCmpt_lignes+'"';
//***
					donnees[iCmpt]['valeurs_defaut']  = donnees[iCmpt]['valeurs_defaut'] .split('¤');
					if(donnees[iCmpt]['valeurs_defaut'][0].length>0){
						contenu += ' placeholder="'+donnees[iCmpt]['valeurs_defaut'][0]+'" />';
					}
			/*		
					$("#input_question").attr("placeholder", donnees[iCmpt]['valeurs_defaut'][0]);

						if(donnees[iCmpt]['valeurs_defaut'].length > 1){
							$("#datalist_question").html('');
							var sSelections = '';
							for(var iCmpt=1;iCmpt<donnees[iCmpt]['valeurs_defaut'].length;iCmpt++){
								sSelections += '<option>'+donnees[iCmpt]['valeurs_defaut'][iCmpt]+'</option>';
							}
							$("#datalist_question").html(sSelections);*/
//***



					
				}else if(donnees[iCmpt_lignes]['type_input'] == "select"){
					//sSelections = '';
					contenu += '<select name="questions[]" id="question'+iCmpt_lignes+'">';
					donnees[iCmpt_lignes]['valeurs_defaut']  = donnees[iCmpt_lignes]['valeurs_defaut'] .split('¤');
					for(var iCmpt_Options=0;iCmpt_Options<donnees[iCmpt_lignes]['valeurs_defaut'].length;iCmpt_Options++){
						contenu += '<option>'+donnees[iCmpt_lignes]['valeurs_defaut'][iCmpt_Options]+'</option>';
					}
					//contenu += sSelections;
					contenu += '</select>';
				}
				contenu += "<span>"+(iCmpt_lignes+1)+"/"+donnees.length+"</span></div>";
				$("#form_question").append(contenu);
			}
		}
		
		function afficherGenres(donnees){
			/*
			Affiche un "select" lequel permet de sélectionner le Genre Littéraire
			*/
			var iCmpt = 0;

			donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
			
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

		//hideAll();
		execXHR_Request("../assets/xhr/creationProjet.xhr.php", "etape=lireGenres", afficherGenres, traiterErreurs);
	});
</script>

<div id="reponses_questions"></div>
<p>imaginer ici un truc qui tourne --&gt; <img src="../assets/images/wait_circle2.png" alt="Attendez..." /> &lt;-- en attendant que XHR revienne avec les "genre littéraires" et ensuite sa disparait pour laisser le premier select apparaitre</p>
<form id="form_question" method="post" action="index.php">
	<!-- <label id="label_choixGenre">Choississez le style du roman : <select id="choixGenre"></select> -->
	<label id="label_question"></label>
	<!--<input id="input_question" type="text" list="datalist_question" value="" placeholder="" />-->
	<select id="select_question"></select>
	<!--<span id="compte_question"></span>-->
	<!--<button type="button" id="button_question"></button>-->
	<button type="button" id="button_nextQuestion">Suivant</button>
	<!--<datalist id="datalist_question"></datalist>-->
</form>
<?php
include "../assets/inc/footer.inc.php"
?>
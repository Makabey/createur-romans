<?php
$sPageTitle = "Acceuil | ";

include "assets/inc/header.inc.php";
include "assets/inc/db_access.inc.php";
?>
<script src="assets/xhr/xhrFunctions.js"></script>
<script>
	$(function(){
		$("#button_question").hide()
		$("#select_question").hide();
		$("#input_question").hide();
		$("#label_question").hide();
		
		$("#button_nextQuestion").click(function(){
			
		});
		
		lireListeStylesLitteraires();
		
		var donneesQuestions = null;
		
		function afficherGenres(donnees){
			donneesQuestions = donnees;
		}
	});
</script>

<div id="reponses_questions"></div>
<p>imaginer ici un truc qui tourne en attendant que XHR revienne avec les genre littéraires et ensuite sa disparait pour laisser le premier select apparaitre</p>
<form>
<!-- <label id="label_choixGenre">Choississez le style du roman : <select id="choixGenre"></select> -->
<label id="label_question"><input id="input_question" type="text" value="" placeholder="Entrez son nom" /><select id="select_question"></select></label>
<button type="button" id="button_question">Générer</button><button type="button" id="button_nextQuestion">Suivant</button>
</form>
<?php
include "assets/inc/footer.inc.php"
?>
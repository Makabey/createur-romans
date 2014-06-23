<?php
$sPageTitle = "(demo) mode Création | ";

include "../assets/inc/header.inc.php";
?>
<form id="form_question" method="post" action="index.php">
	<label id="label_question"></label>
	<select id="select_question"></select>
	<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." />Récupération des Genres Littéraires...</p>
</form>
<button form="form_question" type="button" id="button_nextQuestion">Suivant</button>
<?php
include "../assets/inc/footer.inc.php"
?>
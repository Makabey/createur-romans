<?php
$sPageTitle = "Assistant de Création | ";

require "../assets/inc/header.inc.php";
?>
		<div class="starter-template">
			<h1>Assistant à la structuration d'un roman</h1>
			<h2></h2>
			<p class="lead">Choisissez une valeur parmis les suggestions</p>
		</div>
		<div class="form">
			<div class="form-inner">
				<p id="waitP"><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /><span></span></p>
				<form id="form_question" method="post" action="index.php" autocomplete="off">
					<fieldset></fieldset>
				</form>
				<button id="button_nextQuestion" form="form_question" type="submit">Suivant</button>
			</div>
		</div>
<?php
require "../assets/inc/footer.inc.php"
?>

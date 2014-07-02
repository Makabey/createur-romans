<?php
$sPageTitle = "Accueil | ";

require "assets/inc/header.inc.php";
?>
		<div class="titre">
			<h1>Créateur Roman</h1>
			<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
			<p><a href="#" class="btn btn-primary btn-lg" role="button">En savoir plus</a></p>
			<div class="video">
				<video width="100%" controls="controls">
					<source src="assets/videos/movie.webm" type="video/webm">
					<source src="assets/videos/movie.ogg" type="video/ogg">
					<source src="assets/videos/movie.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
		</div>
		
		<form id="form_register" method="post" action="index.php" class="form-signin" role="form">
			<h2 class="form-signin-heading">S'inscrire</h2>
			<p>C'est rapide et gratuit !</p>
			<input type="text" id="registerName" placeholder="Votre nom" pattern="[^\<\>]{1,40}" title="de 1 à 40 caractères"  class="form-control" required />
			<input type="text" id="registerNick" placeholder="Nom d'usager" pattern="[0-9A-Za-z]{4,20}" title="de 4 à 20 charactères sans accents" data-nomLibre="false" class="form-control" required>
			<input type="password" id="registerPwd" class="form-control" required placeholder="Mot de passe" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" />
			<input type="password" id="registerPwdConf" class="form-control" required placeholder="Confirmez le mot de passe" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" />
			<button class="btn btn-lg btn-primary btn-block" type="submit">S'inscrire</button>
		</form>

		<br /><br /><br />(<a href="../exemple_page_index/hub_client.php">Page sélection de Roman</a> -- <a href="pages/assistant_creation.php">Page assistant de Création</a> -- <a href="pages/demo_mode_edition.php">Page édition du Roman</a>)<br />

<?php require "assets/inc/footer.inc.php"; ?>

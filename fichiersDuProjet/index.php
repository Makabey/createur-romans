<?php
$sPageTitle = "Accueil | ";

require "assets/inc/header.inc.php";
?>
		<div class="titre">
			<h1>Et Scribimus</h1>
			<p class="lead">"Écrivez!" Tel as probablement été, au milieu du VIe siècle av. J.-C., les paroles du tyran Pisistrate lorsqu'il as introduit le culte de Dionysos et le concours littéraire que Thespis a remporté. Deux cents ans plus tard, Aristote lui attribue l'invention de la tragédie.</p>
			<p><a href="#" class="btn btn-primary btn-lg" role="button">En savoir plus</a></p>
			<div class="video">
				<video width="100%" controls="controls">
					<source src="assets/videos/movie.webm" type="video/webm">
					<source src="assets/videos/movie.ogg" type="video/ogg">
					<source src="assets/videos/movie.mp4" type="video/mp4">
					Votre fureteur ne supporte pas la balise video.
				</video>
			</div>
		</div>

		<form id="form_register" method="post" action="index.php" class="form-signin" role="form">
			<h2 class="form-signin-heading">S'inscrire</h2>
			<p>C'est rapide et gratuit !</p>
			<input type="text" id="registerName" placeholder="Votre nom" pattern="[^\<\>]{1,40}" title="de 1 à 40 caractères" class="form-control" />
			<input type="text" id="registerNick" placeholder="Nom d'usager" pattern="[0-9A-Za-z]{4,20}" title="de 4 à 20 charactères sans accents" class="form-control" required="required" data-nomLibre="false" />
			<input type="password" id="registerPwd" placeholder="Mot de passe" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" class="form-control" required="required" />
			<input type="password" id="registerPwdConf" placeholder="Confirmez le mot de passe" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" class="form-control" required="required" />
			<button class="btn btn-lg btn-primary btn-block" type="submit">S'inscrire</button>
		</form>

<?php
require "assets/inc/footer.inc.php";
?>

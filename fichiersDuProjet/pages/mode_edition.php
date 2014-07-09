<?php
$sPageTitle = "mode Édition | ";

require "../assets/inc/header.inc.php";
?>
		<!--<input type="text" value="Titre de l'oeuvre" class="titre-roman form-control" />-->
		<h2 class="titre-roman">Titre de l'oeuvre</h2>
		<div class="col-md-8 custom-box-content" id="edition-boite-textePrincipal">
			<ul class="nav nav-tabs custom-ul">
				<li class="active"><a href="#">Composition</a></li>
				<li class=""><a href="#">Notes</a></li>
				<!--<li class="active">Composition</li>
				<li class="">Notes</li>-->
			</ul>
			<div class="wrap-composition">
				<span id="msg_confirm_save"></span>
				<textarea id="main_write" class=""></textarea>
				<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /><span>Chargement du texte</span></p>
			</div>
		</div>
		<div class="col-md-4 custom-box-content" id="edition-boite-entites">
			<ul class="nav nav-tabs custom-ul">
				<li class="active"><a href="#">Qui</a></li>
				<li class=""><a href="#">Quoi</a></li>
				<li class=""><a href="#">Où</a></li>
				<li class=""><a href="#">Quand</a></li>
				<!--<li class="active">Qui</li>
				<li class="">Quoi</li>
				<li class="">Où</li>
				<li class="">Quand</li>-->
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Délit <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li class=""><a href="#">Comment</a></li>
						<li class=""><a href="#">Pourquoi</a></li>
						<!--<li class="">Comment</li>
						<li class="">Pourquoi</li>-->
					</ul>
				</li>
			</ul>
			<!--<span class="en-tete-aide-memoire">Vos personnages</span>-->
			<div class="wrap-aide-memoire"><!-- id="contenantEntites1"> -->
				<!-- Tout le code est retourné dans le fichier mode_edition.js ~ln 290 -->
			</div>
		</div>
<?php
require "../assets/inc/footer.inc.php"
?>

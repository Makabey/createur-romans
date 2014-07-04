<?php
$sPageTitle = "mode Édition | ";

require "../assets/inc/header.inc.php";
?>
		<!--<input type="text" value="Titre de l'oeuvre" class="titre-roman form-control" />-->
		<h2 class="titre-roman">Titre de l'oeuvre</h2>
		<div class="col-md-8 custom-box-content">
			<ul class="nav nav-tabs custom-ul">
				<li class="active"><a href="#">Composition</a></li>
				<li class=""><a href="#">Notes</a></li>
			</ul>
			<div class="wrap-composition">
				<textarea id="main_write" class=""></textarea>
				<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /><span>Chargement du texte</span></p>
			</div>
		</div>
		<div class="col-md-4 custom-box-content">
			<ul class="nav nav-tabs custom-ul">
				<li class="active"><a href="#">Qui</a></li>
				<li class=""><a href="#">Quoi</a></li>
				<li class=""><a href="#">Où</a></li>
				<li class=""><a href="#">Quand</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Délit <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li class=""><a href="#">Comment</a></li>
						<li class=""><a href="#">Pourquoi</a></li>
					</ul>
				</li>
			</ul>
			<!--<span class="en-tete-aide-memoire">Vos personnages</span>-->
			<div class="wrap-aide-memoire" id="contenantEntites">
				<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /><span>Chargement des entités</span></p>
			</div>
		</div>
<?php
require "../assets/inc/footer.inc.php"
?>

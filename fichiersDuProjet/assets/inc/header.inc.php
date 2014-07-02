<?php
session_start();
$sNomDeCettePage = substr($_SERVER['SCRIPT_NAME'], (strrpos($_SERVER['SCRIPT_NAME'],'/')+1));
$sNomDeCettePage = substr($sNomDeCettePage, 0, (strpos($sNomDeCettePage,'.')));

if(($sNomDeCettePage != 'index') && (!isset($_SESSION['usager']))){
	header("Location:/fichiersDuProjet/index.php");
	exit();
}

$rootDomaine = ($sNomDeCettePage == 'index')?'':"/fichiersDuProjet/";
?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $sPageTitle; ?>"Le créateur de Romans"</title>
		<meta name="author" content="Thomas A. Séguin, Olivier Berthier, Eric Robert</p>" />
		<meta name="description" content="Effacez le syndrome de la page blanche avec notre assistant. Sélectionnez un genre, répondez à quelques questions et vous voilà avec un paragraphe suggèrant le fil de votre prochaine oeuvre." />
		<meta name="keywords" content="roman, assistant, page blanche, aide à l'écriture, scrivener, evernote, gratuit, composer" />
		<link rel="stylesheet" href="<?php echo $rootDomaine; ?>assets/css/styles.css" media="only screen" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="<?php echo $rootDomaine; ?>assets/xhr/xhrFunctions.js"></script>
		<script src="<?php echo $rootDomaine; ?>assets/js/functions.js"></script>
		<!-- Bootstrap core CSS -->
		<link href="<?php echo $rootDomaine; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom css -->
		<link href="<?php echo $rootDomaine; ?>assets/css/theme.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script>
			"use strict";
			/*window.addEventListener("load", function(){ // J'utilise un listener pour éviter de marcher sur les platebandes de jQuery
				// mettre ici le code qui pourrait servir à plusieurs pages.
			});*/
			/* Variables nécessaires pour le fichier JS qui suit, si applicable */
			<?php
			// Variables générées par PHP pour JS
			/*switch($sNomDeCettePage){
				case 'index':
					break;
			}*/
			if($sNomDeCettePage != 'index'){
				if(isset($_SESSION['usager'])){
					$usager = $_SESSION['usager'];
					$idRoman = (isset($_SESSION[$usager]['idRoman']))?$_SESSION[$usager]['idRoman']:0;
					echo "var idUsager = $usager;", PHP_EOL;
					
					if(isset($_GET['idRoman']))$idRoman = $_GET['idRoman']; // pour tests seulement
					
					echo "var idRoman = $idRoman;", PHP_EOL;
				}else{
					echo 'document.location.href="' . $rootDomaine . 'index.php"'; // Si $_SESSION['usager'] ne contient rien, c'est qu'on essaie d'accèder à une page sans se logguer
				}
			}
			?>
		</script>
		<!-- Fichier JS spécifique à la page -->
		<?php
			#if(file_exists($rootDomaine . 'assets/js/'.$sNomDeCettePage.'.js')){
				echo '<script src="' . $rootDomaine . 'assets/js/',$sNomDeCettePage,'.js"></script>',PHP_EOL;
			#}
		?>
	</head>
	<body id="<?php echo $sNomDeCettePage; ?>">
		<header role="banner">
		<div class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
				<div class='container'>
					<div class='navbar-header'>
					  <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
						<span class='sr-only'>Toggle navigation</span>
						<span class='icon-bar'></span>
						<span class='icon-bar'></span>
						<span class='icon-bar'></span>
					  </button>
					  <a class='navbar-brand' href='#'><img src='assets/images/logo.png' alt='créateur roman, bienvenue' />Créateur Roman</a>
					</div>
					<div class='navbar-collapse collapse'>
						<?php if(!isset($_SESSION['usager'])){ ?>
						<form id='form_login' method='post' action='#' class='navbar-form navbar-right' role='form'>
							<div class='form-group'>
								<input type='text' id='loginName' required='required' pattern='[0-9A-Za-z]{4,20}' title='de 4 à 20 charactères sans accents' type='text' placeholder='Identifiant' class='form-control'>
							</div>
							<div class='form-group'>
								<input type='password' id='loginPwd' required='required' pattern='[^\<\>]{8,20}' title='de 8 à 20 caractères' placeholder='Mot de passe' class='form-control'>
							</div>
							<button type='submit' class='btn btn-success'>Connexion</button>
						</form>
						<?php }else{ ?>
						<p>Bienvenue <?php echo $_SESSION['nom']; ?></p>
						<a href="<?php echo $rootDomaine; ?>pages/logout.php">Se déconnecter</a>
						<?php } ?>
					</div><!--/.navbar-collapse -->
				</div>
			</div>
		</header>
		<div class="container" role="main">

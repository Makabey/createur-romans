<?php
$sNomDeCettePage = substr($_SERVER['SCRIPT_NAME'], (strrpos($_SERVER['SCRIPT_NAME'],'/')+1));
$sNomDeCettePage = substr($sNomDeCettePage, 0, (strpos($sNomDeCettePage,'.')));

#require_once "assets/inc/tools.inc.php";
require_once "../assets/inc/menus.inc.php";
?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $sPageTitle; ?>"Le créateur de Romans"</title>
		<meta name="author" content="Thomas A. Séguin, Olivier Berthier, Eric Robert</p>" />
		<meta name="description" content="Effacez le syndrome de la page blanche avec notre assistant. Sélectionnez un genre, répondez à quelques questions et vous voilà avec un paragraphe suggèrant le fil de votre prochaine oeuvre." />
		<meta name="keywords" content="roman, assistant, page blanche, aide à l'écriture, scrivener, evernote, gratuit, composer" />
		<link rel="stylesheet" href="../assets/css/styles.css" media="only screen" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../assets/xhr/xhrFunctions.js"></script>
		<script src="../assets/js/functions.js"></script>
		<script>
		"use strict";
		/*window.addEventListener("load", function(){ // J'utilise un listener pour éviter de marcher sur les platebandes de jQuery
			// mettre ici le code qui pourrait servir à plusieurs pages.
		});*/
		/* Variables nécessaires pour le fichier JS qui suit, si applicable */
		<?php
		// Variables générées par PHP  pour JS
		/*switch($sNomDeCettePage){
			case 'index':
				break;
		}*/
		echo "var idRoman = $idRoman;", PHP_EOL
		?>
		</script>
		<!-- Fichier JS spécifique à la page -->
		<?php
			if(file_exists('../assets/js/'.$sNomDeCettePage.'.js')){
				echo '<script src="../assets/js/',$sNomDeCettePage,'.js"></script>',PHP_EOL;
			}
		?>
	</head>
	<body id="<?php echo $sNomDeCettePage; ?>">
		<header role="banner">
		<?php
			/*
				Le menu irait ici...
			*/
			spawnHeaderMenu();
		?>
		</header>
		<div id="container">
			<div id="content" role="main">

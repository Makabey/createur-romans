<?php
session_start();
$sNomDeCettePage = substr($_SERVER["SCRIPT_NAME"], (strrpos($_SERVER["SCRIPT_NAME"],"/")+1));
$sNomDeCettePage = substr($sNomDeCettePage, 0, (strpos($sNomDeCettePage,".")));

#$rootDomaine = ($sNomDeCettePage == "index")?"":"/fichiersDuProjet/"; // maison Eric
#$rootDomaine = ($sNomDeCettePage == "index")?"":"http://localhost/GitHub/createur-romans/fichiersDuProjet/"; // ISI
$rootDomaine = "http://etscribimus.olirick-tp.site40.net/"; // en ligne

if(($sNomDeCettePage != "index") && (!isset($_SESSION['pseudo']))){
	header("Location:".$rootDomaine."index.php");
	exit();
}elseif(($sNomDeCettePage == "index") && (isset($_SESSION['pseudo']))){
	header("Location:".$rootDomaine."pages/hub_client.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge" />-->
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?php echo $sPageTitle; ?>Et Scribimus</title>
		<meta name="author" content="Thomas A. Séguin, Olivier Berthier, Eric Robert</p>" />
		<meta name="description" content="Effacez le syndrome de la page blanche avec notre assistant. Sélectionnez un genre, répondez à quelques questions et vous voilà avec un paragraphe suggèrant le fil de votre prochaine oeuvre." />
		<meta name="keywords" content="roman, assistant, page blanche, aide à l'écriture, scrivener, evernote, gratuit, composer" />
		<link rel="shortcut icon" href="<?php echo $rootDomaine; ?>assets/ico/favicon.ico" />
		<!-- Bootstrap core CSS -->
		<link href="<?php echo $rootDomaine; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom css -->
		<!-- <link rel="stylesheet" href="<?php #echo $rootDomaine; ?>assets/css/styles.css" media="only screen" /> -->
		<link rel="stylesheet" href="<?php echo $rootDomaine; ?>assets/css/theme.css">
		<link rel="stylesheet" href="<?php echo $rootDomaine; ?>assets/css/custom-style.css">
	</head>
	<body class="<?php echo $sNomDeCettePage; ?>">
		<header role="banner">
			<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#"><img src="<?php echo $rootDomaine; ?>assets/images/logo.png" alt="Et Scribimus, bienvenue" />Et Scribimus</a>
					</div>
					<div class="navbar-collapse collapse inner-header">
						<?php if(!isset($_SESSION['pseudo'])){ ?>
						<form id="form_login" method="post" action="#" class="navbar-form navbar-right" role="form">
							<div class="form-group">
								<input type="text" id="loginName" required="required" pattern="[0-9A-Za-z]{4,20}" title="de 4 à 20 charactères sans accents" placeholder="Identifiant" class="form-control">
							</div>
							<div class="form-group">
								<input type="password" id="loginPwd" required="required" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" placeholder="Mot de passe" class="form-control">
							</div>
							<button type="submit" class="btn btn-success">Connexion</button>
						</form>
						<?php }else{ ?>
							<div class="bienvenue_user">
								<span class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Bienvenue <?php echo $_SESSION[$_SESSION['pseudo']]['nom']; ?> <span class="caret"></span></a>
									<ul class="dropdown-menu">
										<?php
											if($sNomDeCettePage == "mode_edition"){
												echo '<li><a href="' . $rootDomaine . 'pages/hub_client.php">Retour au HUB</a></li>';
											}
										?>
										<li><a href="<?php echo $rootDomaine; ?>pages/logout.php">Se déconnecter</a></li>
									<ul>
								</span>
							</div>
						<?php } ?>
					</div><!--/.navbar-collapse -->
				</div>
			</div>
		</header>
		<div class="container" role="main" id="maincontent">

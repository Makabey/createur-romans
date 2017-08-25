<?php
session_start();
$sNomDeCettePage = strrpos($_SERVER["SCRIPT_NAME"],"/")+1;
$rootDomaine = substr($_SERVER["SCRIPT_NAME"], 0, $sNomDeCettePage);
$sNomDeCettePage = substr($_SERVER["SCRIPT_NAME"], $sNomDeCettePage);
$sNomDeCettePage = explode('.', $sNomDeCettePage);
$sNomDeCettePage = $sNomDeCettePage[0];

if(!isset($_SESSION['pseudo'])){
	if($sNomDeCettePage != "index"){
		header("Location:".$rootDomaine."index.php");
		exit();
	}
}else{
	if($_SESSION[$_SESSION['pseudo']]['est_admin']){
		if($sNomDeCettePage !== 'administration'){
			header("Location:" . $rootDomaine . 'administration');
			exit();
		}
	}else{
		if($sNomDeCettePage == 'index' || $sNomDeCettePage == 'administration'){
			header("Location:" . $rootDomaine . 'hub_client.php');
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge" />-->
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?=$sPageTitle?>Et Scribimus</title>
		<meta name="author" content="Thomas A. Séguin, Olivier Berthier, Eric Robert</p>" />
		<meta name="description" content="Effacez le syndrome de la page blanche avec notre assistant. Sélectionnez un genre, répondez à quelques questions et vous voilà avec un paragraphe suggèrant le fil de votre prochaine oeuvre." />
		<meta name="keywords" content="roman, assistant, page blanche, aide à l'écriture, scrivener, evernote, gratuit, composer" />
		<!--<link rel="shortcut icon" href="<?=$rootDomaine?>ico/favicon.ico" /> -->
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Custom css -->
		<link rel="stylesheet" href="<?=$rootDomaine?>css/custom-style.css">
	</head>
	<body class="<?=$sNomDeCettePage?>">
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
						<a class="navbar-brand" href="#"><img src="<?=$rootDomaine?>images/logo.png" alt="Et Scribimus, bienvenue" />Et Scribimus</a>
					</div>
					<div class="navbar-collapse collapse inner-header">
						<?php if(!isset($_SESSION['pseudo'])){ ?>
						<form id="form_login" method="post" action="#" class="navbar-form navbar-right" role="form">
							<div class="form-group">
								<input type="text" id="loginName" required="required" pattern="[0-9A-Za-z]{4,20}" title="de 4 à 20 charactères sans accents" placeholder="Identifiant" class="form-control">
							</div>
							<div class="form-group">
								<input type="password" id="loginPwd" required="required" pattern="[0-9A-Za-z]{8,20}" title="de 8 à 20 caractères" placeholder="Mot de passe" class="form-control">
							</div>
							<button type="submit" class="btn btn-success">Connexion</button>
						</form>
						<?php }else{ ?>
							<div class="bienvenue_user">
								<span class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Bienvenue <?=$_SESSION[$_SESSION['pseudo']]['nom']?> <span class="caret"></span></a>
									<ul class="dropdown-menu">
										<?php
											if(!$_SESSION[$_SESSION['pseudo']]['est_admin'] && $sNomDeCettePage !== "hub_client"){
												echo '<li><a href="', $rootDomaine, 'hub_client.php">Retour au HUB</a></li>', PHP_EOL;
											}
										?>
										<li><a href="<?=$rootDomaine?>logout.php">Se déconnecter</a></li>
									</ul>
								</span>
							</div>
						<?php } ?>
					</div><!--/.navbar-collapse -->
				</div>
			</div>
		</header>
		<div class="container" role="main" id="maincontent">

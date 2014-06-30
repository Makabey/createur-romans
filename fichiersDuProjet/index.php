<?php
$sPageTitle = "base pour Index - ";
//$idRoman = 0;
if(isset($_GET['pablum'])){
	unset($_SESSION['usager']);
	$_SESSION['usager'] = $_GET['pablum'];
}

require "assets/inc/header.inc.php";
#require "assets/inc/menus.inc.php";
?>
Mettre ici le corps de la page Index<br /><br />

<a href="../exemple_page_index/hub_client.php">Page sélection de Roman</a><br />
<a href="pages/assistant_creation.php">Page assistant de Création</a><br />
<a href="pages/demo_mode_edition.php">Page édition du Roman</a><br />

<br /><br />

<form id="form_login" method="post" action="#">
<div><span></span></div>
<input type="text" id="loginName" required="required" placeholder="Nom d'usager" pattern="[0-9A-Za-z]{4,20}" title="de 4 à 20 charactères sans accents" value="MPO1" />
<input type="password" id="loginPwd" required="required" placeholder="Mot de passe" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" value="ljfksljskldjf" />
<button type="submit">S'authentifier</button>
</form>
<br /><br />
<form id="form_register" method="post" action="index.php">
<input type="text" name="registerName" placeholder="Votre nom" pattern="[^\<\>]{1,40}" title="de 1 à 40 caractères" />
<input type="text" name="registerNick" required="required" placeholder="Nom d'usager" pattern="[0-9A-Za-z]{4,20}" title="de 4 à 20 charactères sans accents" />
<input type="password" name="registerPwd" required="required" placeholder="Mot de passe" pattern="[^\<\>]{8,20}" title="de 8 à 20 caractères" />
<input type="password" name="registerPwdConf" required="required" placeholder="Confirmez le mot de passe" />
<button type="submit">Créer un compte</button>
</form>
<?php
echo '$usager = ' , $_SESSION['usager'] , "<br />", PHP_EOL;
if(!isset($_SESSION['usager'])){
	echo "<p>Pas loggué</p>";
}else{
	echo "<p>Bienvenue {$_SESSION['nom']} ({$_SESSION['usager']})</p>";
}

require "assets/inc/footer.inc.php";

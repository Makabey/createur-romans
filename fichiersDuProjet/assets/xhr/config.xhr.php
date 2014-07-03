<?php
#require_once "../inc/db_access.inc.php";
#require_once "../inc/library01.inc.php";

if(!isset($_POST['oper']) || !isset($_POST['idRoman'])){
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬A required parameter (either "oper", "typeEntite" or "idRoman"), is missing';
	exit();
}

$_POST['idRoman'] += 0;
if($_POST['idRoman'] <= 0){
	echo '0¬Invalid value for "idRoman" (', $_POST['idRoman'], ')';
	exit();
}

/*$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo $db;
	exit();
}*/

/* =================================================== */

$resultat = false;
switch($_POST['oper']){
	case 'charger' :
		// en principe $_SESSION['pseudo'] existe
		$resultat = configRoman();
		break;

	default: $resultat = '0¬"' . $_POST["oper"] . '" unknown value for parameter "oper"';
}

echo $resultat; /* résultat final retourné à XHR */
exit();

/* =================================================== */

/*
	FONCTIONS
*/
function configRoman(){
	session_start();
	$pseudo = $_SESSION['pseudo'];
	$_SESSION[$pseudo]['idRoman'] = $_POST['idRoman'];
	$resultat = "1¬Prêt à charger.";

	return $resultat;
}
/* == EOF == */

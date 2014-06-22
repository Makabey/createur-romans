<?php
/*

TODO:
1. validation des valeurs POST recues
2. optimiser code pour ne pas répéter le bloc de lecture si possible

*/

#require_once "../inc/tools.inc.php";
#include "../inc/db_access.inc.php";
include "../inc/db_access.inc.php";
#include "/assets/inc/db_access.inc.php";

$arrChamps_genres_litteraires = array('nom', 'nro_question', 'texte', 'type_input', 'valeurs_defaut', 'bouton_fonction');

if(!isset($_POST['etape'])){
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬$_POST["etape"] was required';
	exit();
}

$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo "0¬" . $db;
	exit();
}

$resultat = false;
if($_POST['etape'] == 'lireGenres'){
/*
	Extraire de la BD une copie de chaques noms "Genre LIttéraire"
*/
	$query = "SELECT DISTINCT nom FROM genres_litteraires;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$resultat[] = $row[0];
		}
		$resultat = json_encode($resultat);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{
			$resultat = "1¬" . $resultat;
		}
	}
}

if($_POST['etape'] == 'lireQuestions'){
/*
	En accord avec $_POST['genre'] , lire les questions et retourner tout en bloc
*/
	if(!isset($_POST['genre'])){
		echo '0¬$_POST["genre"] was required';
		exit();
	}

	$query = "SELECT " . implode(', ', $arrChamps_genres_litteraires) . " FROM genres_litteraires WHERE nom='" . $_POST['genre'] . "' ORDER BY nro_question;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$tmp[] = array_combine($arrChamps_genres_litteraires, $row);
		}
		$resultat = json_encode($tmp);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{
			$resultat = "1¬" . $resultat;
		}
	}else{
		$resultat = "0¬An error occured during the operation";
	}
}

function decodeJSON_Error($error){
	switch ($error) {
		case JSON_ERROR_NONE:
			$retour = '[JSON] - No errors';
			break;
		case JSON_ERROR_DEPTH:
			$retour = '[JSON] - Maximum stack depth exceeded';
			break;
		case JSON_ERROR_STATE_MISMATCH:
			$retour = '[JSON] - Underflow or the modes mismatch';
			break;
		case JSON_ERROR_CTRL_CHAR:
			$retour = '[JSON] - Unexpected control character found';
			break;
		case JSON_ERROR_SYNTAX:
			$retour = '[JSON] - Syntax error, malformed JSON';
			break;
		case JSON_ERROR_UTF8:
			$retour = '[JSON] - Malformed UTF-8 characters, possibly incorrectly encoded';
			break;
		default:
			$retour = '[JSON] - Unknown error';
			break;
	 }

	 return $retour;
}

echo $resultat; /* résultat final retourné à XHR */

/* == EOF == */

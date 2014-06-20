<?php
/*

TODO:
1. validation des valeurs POST recues
2. optimiser code pour ne pas répéter le bloc de lecture si possible

*/

#require_once "../inc/tools.inc.php";
include "../inc/db_access.inc.php";
#include "/assets/inc/db_access.inc.php";

$arrChamps_genres_litteraires = array('nom', 'nro_question', 'texte', 'type_input', 'valeurs_defaut', 'bouton_fonction');

if(!isset($_POST['etape'])){
	return false;
	exit();
}

$db = db_connect();

if(false == $db){
	return false;
	exit();
}

$resultat = false;
if($_POST['etape'] == 'lireGenres'){
	$query = "SELECT DISTINCT nom FROM genres_litteraires;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$resultat[] = $row[0];
		}
		$resultat = json_encode($resultat);
	}
}

if($_POST['etape'] == 'lireQuestions'){
	$query = "SELECT " . implode(', ', $arrChamps_genres_litteraires) . " FROM genres_litteraires WHERE nom='" . $_POST['genre'] . "';";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$tmp[] = array_combine($arrChamps_genres_litteraires, $row);
		}
		$resultat = json_encode($tmp);
		if(json_last_error() !== 0){
			$resultat = json_last_error();
		}
	}
}

function barfJSONerror($error){
	switch ($error) {
		case JSON_ERROR_NONE:
			echo ' - No errors';
		break;
		case JSON_ERROR_DEPTH:
			echo ' - Maximum stack depth exceeded';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			echo ' - Underflow or the modes mismatch';
		break;
		case JSON_ERROR_CTRL_CHAR:
			echo ' - Unexpected control character found';
		break;
		case JSON_ERROR_SYNTAX:
			echo ' - Syntax error, malformed JSON';
		break;
		case JSON_ERROR_UTF8:
			echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		break;
		default:
			echo ' - Unknown error';
		break;
	 }

	 echo PHP_EOL;
}

echo $resultat; /* résultat final retourné à XHR */

/* == EOF == */

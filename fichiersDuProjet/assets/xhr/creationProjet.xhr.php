<?php
/*

TODO:
1. validation des valeurs POST recues
2. optimiser code pour ne pas répéter le bloc de lecture si possible

*/

#require_once "../inc/tools.inc.php";
include "../inc/db_access.inc.php";
#include "/assets/inc/db_access.inc.php";

#echo 'test1',PHP_EOL;

if(!isset($_POST['etape'])){
	return false;
	exit();
}

$db = db_connect();

if(false == $db){
	return false;
	exit();
}

#echo 'test2',PHP_EOL;

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
#$_POST['genre'] = 'Policier';
	$query = "SELECT * FROM genres_litteraires WHERE nom='" . $_POST['genre'] . "';";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$tmp[] = array($row[3], $row[4], $row[5], $row[6], $row[7]); # explode(',', implode(',', $row));
			#var_dump($row);
			#var_dump($resultat);
			
			#echo explode(',', implode(',', $row)), PHP_EOL, "<br />";
		}
		#echo "tmp = ";
		#var_dump($tmp);
		#$resultat = json_encode($tmp, JSON_FORCE_OBJECT , 4);
		$resultat = json_encode($tmp);
		#echo "JSON error = ", barfJSONerror(json_last_error()),"<br />";
		#echo "resultat = ";
		#var_dump($resultat);
		if(json_last_error() !== 0) $resultat = json_last_error();
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

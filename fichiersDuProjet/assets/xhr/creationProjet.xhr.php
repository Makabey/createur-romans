<?php
#require_once "../inc/tools.inc.php";
include "../inc/db_access.inc.php";

echo 'test1';

/*if(!isset($_POST['etape'])){
	return false;
	exit();
}*/

$db = db_connect();

if(false == $db){
	return false;
	exit();
}


echo 'test2';

/*

TODO:
1. validation des valeurs POST recues
2. optimiser code pour ne pas répéter le bloc de lecture si possible

*/

$resultat = false;
/*if($_POST['etape'] == 'lireGenres'){
	$query = "SELECT DISTINCT nom FROM genres_litteraires;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$resultat[] = $row[0];
		}
		$resultat = json_encode($resultat);
	}
	
	// tricher un peu, à la maison c'est lent :'(
	/ *$resultat[] = 'Policier';
	$resultat[] = 'Drame';
	$resultat = json_encode($resultat);* /

}*/

#if($_POST['etape'] == 'lireQuestions'){
$_POST['genre'] = 'Policier';
	$query = "SELECT * FROM genres_litteraires WHERE nom='" . $_POST['genre'] . "';";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$resultat[] = array($row[3], $row[4], $row[5], $row[6], $row[7]); # explode(',', implode(',', $row));
			var_dump($row);
			echo explode(',', implode(',', $row)), PHP_EOL, "<br />";
		}
		$resultat = json_encode($resultat);
	}
#}


echo $resultat; /* résultat final retourné à XHR */

/* == EOF == */

<?php
#require_once "../inc/tools.inc.php";
include "assets/inc/db_access.inc.php";

if(!isset($_POST['etape'])){
	return false;
	exit();
}

$db  = db_connect();

if(false == $db){
	return false;
	exit();
}


$resultat = false;
if($_POST['etape'] == 'lireStyles'){
	$query = "SELECT DISTINCT nom FROM genres_litteraires;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()) {
			$resultat[] = $row[0];
		}
		$resultat = json_encode($resultat);
	}
}

echo $resultat; /* résultat final retourné à XHR */

/* == EOF == */

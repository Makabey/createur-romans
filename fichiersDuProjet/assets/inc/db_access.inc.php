<?php
/*
	Ouvre une connection sur la BD MySQL.
*/
function db_connect(){
	#$db = @new mysqli('localhost', 'team_codeH', '^gtCNl06', 'createurromans');
	#$db = @new mysqli('127.0.0.1', 'team_codeH', '^gtCNl06', 'createurromans');
	$db = @new mysqli('mysql3.000webhost.com', 'a6127734_codeH', 'B0lgSfhB', 'a6127734_etscrib');
	#$db = @new mysqli('127.0.0.1', 'testA', 'vNSLpIM0', 'createurromans');

	/*
	$mysql_host = "mysql3.000webhost.com";
$mysql_database = "a6127734_etscrib";
$mysql_user = "a6127734_codeH";
$mysql_password = "B0lgSfhB";
	
	*/
	
	
	if ($db->connect_errno) {
		$db = "0¬Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}else{
		$db->set_charset("utf8");
	}

	return $db;
}


function real_escape_string($chaine, $db){
	if(is_object($db) && (get_class($db) == "mysqli")){
		$chaine = str_replace('>', '&gt;', $chaine);
		$chaine = str_replace('<', '&lt;', $chaine);
		$chaine = trim($chaine);
		$chaine = $db->real_escape_string($chaine);
		$chaine = str_replace('_', '\_', $chaine);
		$chaine = str_replace('%', '\%', $chaine);
	}else{
		$chaine = "0¬'db' is not a mysqli object";
	}
	return $chaine;
}
/* == EOF == */

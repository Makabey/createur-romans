<?php
/*
	Ouvre une connection sur la BD MySQL. 
*/
function db_connect(){
	#$db = @new mysqli('localhost', 'team_codeH', '^gtCNl06', 'createurromans');
	$db = @new mysqli('127.0.0.1', 'team_codeH', '^gtCNl06', 'createurromans');

	if ($db->connect_errno) {
		return "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}

	$db->set_charset("utf8");

	return $db;
}

/* == EOF == */

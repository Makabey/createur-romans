<?php
############
#
# Ouvre une connection sur la BD MySQL. Fonction globale pour éviter de devoir changer X lignes
# dans les Y fichiers du site
#
function db_connect(){
	$db = @new mysqli('localhost', 'team_codeH', '^gtCNl06', 'createurromans');

	if ($db->connect_errno) {
		die("Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error);
	}

	$db->set_charset("utf8");

	return $db;
}

/* == EOF == */

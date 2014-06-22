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

#$arrChamps_genres_litteraires = array('nom', 'nro_question', 'texte', 'type_input', 'valeurs_defaut', 'bouton_fonction');
$arrChamps_entites = array('ID_prev', 'ID_next', 'titre', 'contenu', 'note');

if(!isset($_POST['oper']) || !isset($_POST['typeEntite']) || !isset($_POST['idRoman'])){
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬A required $_POST parameter is missing';
	exit();
}

if($_POST['oper'] != 'lire' && $_POST['oper'] != 'ecrire'){
	echo '0¬Invalid value for $_POST["oper"]';
	exit();
}

$_POST['idRoman'] += 0;
if($_POST['idRoman'] <= 0){
	echo '0¬Invalid value for $_POST["idRoman"]';
	exit();
}

$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo "0¬" . $db;
	exit();
}

$resultat = false;
$mode=1;
if($_POST['oper'] == 'lire'){
/*
	Lire de la BD, selon la valeur de $_POST['typeEntite'], les donnes soit du Texte lui-même soit de l'une des sectiosn d'entitées
*/
	switch($_POST['typeEntite']){
		case 'qui' :
			#$resultat = '0¬Invalid value for $_POST["typeEntite"]'; 
			$query = 'SELECT ID_entite, ' . implode(', ', $arrChamps_entites) . ' FROM entites WHERE ID_roman = ' . $_POST['idRoman'] . ' AND type = "qui" AND deleted = 0 ORDER BY ID_prev;';
			$mode=2;
			break;
		case 'quoi' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'ou' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'comment' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'pourquoi' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'textePrincipal' : $query = "SELECT contenu FROM roman_texte WHERE ID_roman=".$_POST['idRoman'].";"; $mode=1; break;
		default: echo '0¬Invalid value for $_POST["typeEntite"]'; exit();
	}

	$result = $db->query ($query);
	if(false !== $result){
		if($mode == 1){
			/* On veux le texte ? Faire une simple lecture */
			$row = $result->fetch_row();
			$resultat = $row[0];
		}elseif($mode == 2){
			/* On as besoin d'un ID pour la balise qui recevra tout le contenu, en principe, ne devrais pas âtre passé mais bon, on peux corriger plus tard */
			if(!isset($_POST['target'])){
				echo '0¬Invalid value for $_POST["target"]';
				exit();
			}
			$resultat[0]['target'] = $_POST['target'];
			$resultat[0]['first'] = null;
			while ($row = $result->fetch_row()) {
				$ID_entite = array_shift($row);
				if($resultat[0]['first'] === null){
					$resultat[0]['first'] = $ID_entite;
				}
				$resultat[$ID_entite] = array_combine($arrChamps_entites, $row);
			}
			
		}

		/* Convertir en JSON */
		$resultat = json_encode($resultat);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{
			$resultat = "1¬" . $resultat;
		}
	}else{
		$resultat = "0¬An error occured during a SELECT operation. (query = '$query')";
	}
}else if($_POST['oper'] == 'ecrire'){
	switch($_POST['typeEntite']){
		case 'qui' :
			#$resultat = '0¬Invalid value for $_POST["typeEntite"]';
			#$query = 'SELECT ID_entite, ' . $arrChamps_entites . ' FROM entites WHERE ID_roman = ' . $_POST['idRoman'] . ' AND type = "qui" AND deleted = 0 ORDER BY ID_prev;';
			#$mode=2; 
			if(!isset($_POST['target'])){
				echo '0¬Invalid value for $_POST["target"]';
				exit();
			}
			$query = 'UPDATE entites SET ' . $_POST['target'] . ' = "' . $_POST['donnees'] . '" WHERE ID_entite=' . $_POST['idEntite'] . ';';
			break;
		case 'quoi' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'ou' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'comment' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'pourquoi' : $resultat = '0¬Invalid value for $_POST["typeEntite"]';break;
		case 'textePrincipal' :
			#$mode=1;
			$query = 'UPDATE roman_texte SET contenu = "' . $_POST['donnees'] . '" WHERE ID_roman=' . $_POST['idRoman'] . ';';
			break;
		default: echo '0¬Invalid value for $_POST["typeEntite"]'; exit();
	}

	$result = $db->query ($query);
	if(false !== $result){
	/*	if($mode == 1){
			$row = $result->fetch_row();
			$resultat = $row[0];
		}elseif($mode == 2){
			while ($row = $result->fetch_row()) {
				$ID_entite = array_shift($row);
				$resultat[$ID_entite] = array_combine($arrChamps_entites, $row);
			}
		}

		$resultat = json_encode($resultat);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{*/
			$resultat = "1¬UPDATE successful :: " . $query;
		//}
	}else{
		$resultat = "0¬An error occured during an UPDATE operation.  (query = '$query')";
	}
}else{
	$resultat = "0¬Unknown operation '" . $_POST['oper'] . "'";
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

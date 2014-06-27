<?php
require_once "../inc/db_access.inc.php";
require_once "../inc/library01.inc.php";

if(!isset($_POST['oper'])){
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬Parameter "oper" is required';
	exit();
}

$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo "0¬(DB pas un objet) " . $db;
	exit();
}

/* =================================================== */

$resultat = false;
switch($_POST['oper']){
	case 'lireGenres':
		$resultat = lireGenreLitteraires($db);
		break;

	case 'lireQuestions':
		if(!isset($_POST['genre'])){
			$resultat = '0¬"genre" is required';
		}else{
			$genresValides = lireGenreLitteraires($db, false); //"policier,drame";
			//$pos = stripos($genresValides, $_POST['genre']);
			$pos = in_array($_POST['genre'], $genresValides);
			if(false === $pos){
				$resultat = '0¬"genre" unknown value "' . $_POST["genre"] . '"';
			}else{
				$resultat = lireQuestions($db);
			}
		}
		break;

	case "lireListeRomans":
		if(!isset($_POST['idUsager'])){
			$resultat = '0¬"idUsager" is required';
		}else{
			$resultat = lireListeRomans($db);
		}
		break;

	default: $resultat = '0¬"' . $_POST["oper"] . '" unknown value for parameter "oper"';
}

echo $resultat; /* résultat final retourné à XHR */
exit();

/* =================================================== */

/*
	FONCTIONS
*/
function lireGenreLitteraires($db, $encode_result=true){
	/*
		Extraire de la BD une copie de chaques noms "Genre Littéraire"
	*/
	#global $db;
	$query = "SELECT nom FROM genres_litteraires_noms;"; # LIMIT 0,1;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()){
			$resultat[] = $row[0];
		}
		if($encode_result){
			$resultat = json_encode($resultat);
			if(json_last_error() !== 0){
				$resultat = "0¬" . decodeJSON_Error(json_last_error());
			}else{
				$resultat = "1¬" . $resultat;
			}
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] " . $db->error . " ($query)";
	}
	return $resultat;
}

function lireQuestions($db){
	/*
		En accord avec $_POST['genre'] , lire les questions et retourner tout en bloc
	*/
	#global $db;
	#$arrChamps_genres_litteraires = array('nom', 'nro_question', 'texte', 'type_input', 'suggestions', 'bouton_fonction');
	$arrChamps_genres_litteraires = array('nro_question', 'typeEntite', 'texte', 'forme_synopsis', 'type_input', 'suggestions', 'bouton_fonction'); // j'ai enlevé le champs 'nom' pour que ça fasse moins de données retournés

	$query = "SELECT `genres_litteraires_questions`.`" . implode('`, `genres_litteraires_questions`.`', $arrChamps_genres_litteraires) . "` FROM genres_litteraires_questions, genres_litteraires_noms WHERE genres_litteraires_questions.ID_genre = genres_litteraires_noms.ID_genre AND genres_litteraires_noms.nom = '{$_POST['genre'] }' ORDER BY genres_litteraires_questions.nro_question;";

	/*
	SELECT genres_litteraires_questions.nro_question, genres_litteraires_questions.typeEntite, genres_litteraires_questions.texte, genres_litteraires_questions.type_input, genres_litteraires_questions.suggestions, genres_litteraires_questions.bouton_fonction FROM genres_litteraires_questions, genres_litteraires_noms WHERE genres_litteraires_questions.ID_genre = genres_litteraires_noms.ID_genre AND genres_litteraires_noms.nom = 'drame' ORDER BY genres_litteraires_questions.nro_question
	*/

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()){
			$tmp[] = array_combine($arrChamps_genres_litteraires, $row);
		}
		$resultat = json_encode($tmp);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{
			$resultat = "1¬" . $resultat;
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] " . $db->error . " ($query)";
	}
	return $resultat;
}

function lireListeRomans($db){
	/*
		En accord avec $_POST['idRoman'] , lire les détails des Romans
	*/
	$arrChamps_Romans = array('ID_roman', 'ID_genre', 'titre', 'date_creation', 'date_dnrEdition');

	$query = "SELECT `roman_details`.`" . implode('`, `roman_details`.`', $arrChamps_Romans) . "`, `roman_texte`.`synopsis` FROM roman_details, roman_texte WHERE roman_details.ID_roman = roman_texte.ID_roman AND roman_details.ID_usager = '{$_POST['idUsager']}' AND roman_details.deleted = 0 ORDER BY roman_texte.ID_roman;";

	$result = $db->query ($query);
	if(false !== $result){
		while ($row = $result->fetch_row()){
			$arrChamps_Romans[] = 'synopsis';
			$tmp[] = array_combine($arrChamps_Romans, $row);
		}
		$resultat = json_encode($tmp);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{
			$resultat = "1¬" . $resultat;
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] " . $db->error . " ($query)";
	}
	return $resultat;
}

/* == EOF == */

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
		
	case "creerLeRoman":
		// Plus de validation à ajouter plus tard pour ce qui est des champs requis...
		$resultat = creerLeRoman($db);
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
	$arrChamps_genres_litteraires = array('nro_question', 'texte', 'forme_synopsis', 'type_input', 'suggestions', 'bouton_fonction'); #, 'typeEntite'); // j'ai enlevé le champs 'nom' pour que ça fasse moins de données retournés

	$query = "SELECT `genres_litteraires_questions`.`" . implode('`, `genres_litteraires_questions`.`', $arrChamps_genres_litteraires) . "` FROM genres_litteraires_questions, genres_litteraires_noms WHERE genres_litteraires_questions.ID_genre = genres_litteraires_noms.ID_genre AND genres_litteraires_noms.nom = '{$_POST['genre'] }' ORDER BY genres_litteraires_questions.nro_question;";

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


function creerLeRoman($db){
	/*			queryString += "idUsager="+idUsager;
				queryString += "&titreRoman="+encodeURIComponent (gblChoixUsager['titreRoman']);
				queryString += "&synopsis="+encodeURIComponent (gblChoixUsager['synopsis']);
				queryString += "&genreLitteraire="+gblChoixUsager['genreLitteraire'];
				for(iCmpt=0;iCmpt<gblChoixUsager['questions'].length;iCmpt++){
					queryString += "&question"+iCmpt+"[]="+encodeURIComponent (gblChoixUsager['questions'][iCmpt]['reponse']);
					queryString += "&question"+iCmpt+"[]="+encodeURIComponent (gblChoixUsager['questions'][iCmpt]['description']);*/
	
	$_POST['titreRoman'] = real_escape_string($_POST['titreRoman'], $db);
	
	// Créer le roman
	$query = "START TRANSACTION; INSERT INTO `roman_details` (`ID_usager`, `ID_genre`, `titre`) VALUES ({$_POST['idUsager']}, (SELECT ID_genre FROM genres_litteraires_noms WHERE nom = '{$_POST['genreLitteraire']}'), '{$_POST['titreRoman']}');";
	
	$result = $db->query ($query);
	if(false !== $result){
		$ID_roman = $db->insert_id;
		// Ajouter le synopsis du Roman
		$_POST['synopsis'] = real_escape_string($_POST['synopsis'], $db);
		$query = "INSERT INTO `roman_texte` (`ID_roman`, `synopsis`, `contenu`) VALUES ($ID_roman, '{$_POST['synopsis']}', 'Bienvenue dans votre roman! Quel sera le commencement de votre histoire? :)');";
		
		$result = $db->query ($query);
		if(false !== $result){
			// Maintenant lire le nombre de questions pour le genre littéraire, ce qui permet de savoir combien d'insertions faire pour la suite
			
			// Insérer chaque entitées tel que commandé par la série "questionsX" où [0] est le contenu et [1] est la note, utiliser le champs forme_synopsis pour le titre
			
		}else{
			$query="rollback;";
			$result = $db->query ($query);
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an INSERT operation. (query = '$query')";
		}
	}else{
		$query="rollback;";
		$result = $db->query ($query);
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an INSERT operation. (query = '$query')";
	}
	
	//$resultat = "0¬[" . __FUNCTION__ . "]  ($query)";
	return $resultat;
}

/* == EOF == */

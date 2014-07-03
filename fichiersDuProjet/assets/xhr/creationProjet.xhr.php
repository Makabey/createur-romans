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
	echo $db;
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
			$genresValides = lireGenreLitteraires($db, false);
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
		Extraire de la BD les noms de chaque "Genre Littéraire"

		$encode_result sert à passer l'encodage en JSON
	*/
	$query = "SELECT nom FROM genres_litteraires_noms;";

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
		En accord avec $_POST['genre'] , c'est à dire le genre littéraire choisi, lire les questions et retourner tout en bloc
	*/
	$arrChamps_genres_litteraires = array('nro_question', 'texte', 'forme_synopsis', 'type_input', 'suggestions', 'bouton_fonction');

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
		Lire les détails des Romans pour "idUsager", ce qui comprend : leur ID, genre, titre, date de creation, date de dernière édition et le synopsis
	*/
	$arrChamps_Romans = array('ID_roman', 'ID_genre', 'titre', 'date_creation', 'date_dnrEdition');
	$tmp = array();

	$query = "SELECT `roman_details`.`" . implode('`, `roman_details`.`', $arrChamps_Romans) . "`, `roman_texte`.`synopsis` FROM roman_details, roman_texte WHERE roman_details.ID_roman = roman_texte.ID_roman AND roman_details.ID_usager = '{$_POST['idUsager']}' AND roman_details.deleted = 0 ORDER BY roman_details.date_dnrEdition DESC;";

	$result = $db->query ($query);
	if(false !== $result){
		$arrChamps_Romans[] = 'synopsis';
		while ($row = $result->fetch_row()){
			$tmp[] = array_combine($arrChamps_Romans, $row);
		}

		$resultat = (!empty($tmp))?json_encode($tmp):'';

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
	/*
		Pousser dans la BD tout les détails pour le nouveau Roman
	*/
	$_POST['titreRoman'] = real_escape_string($_POST['titreRoman'], $db);
	#if(mb_substr($_POST['titreRoman'], 0, 2, "UTF-8") == "0¬") {
	#	return $_POST['titreRoman']; // S'il y as erreur ici, c'est que $db n'est pas une BD ouverte
	#}

	#if(false !== $resultat){
		// Démarrer une TRANSACTION pour pouvoir reculer avec un ROLLBACK si nécessaire et ne pas laisser d'orphelins
		// Normalement je devrais regarder l'aide pour LOCK TABLE et l'appliquer, mais je vais garder ça pour un peu plus tard
		$typeQuery = " TRANSACTION";
		$query = "START TRANSACTION;";
		$resultat = $db->query ($query);
	#}

	// Créer le roman
	if(false !== $resultat){
		$query = "INSERT INTO `roman_details` (`ID_usager`, `ID_genre`, `titre`) VALUES ({$_POST['idUsager']}, (SELECT ID_genre FROM genres_litteraires_noms WHERE nom = '{$_POST['genreLitteraire']}'), '{$_POST['titreRoman']}');";

		$typeQuery = "n INSERT";
		$resultat = $db->query ($query);
	}

	// Ajouter le synopsis et premier contenu du Roman
	if(false !== $resultat){
		$ID_roman = $db->insert_id; // Lire le nouvel ID (dernier AUTONUM généré)

		$_POST['synopsis'] = real_escape_string($_POST['synopsis'], $db);
		$query = "INSERT INTO `roman_texte` (`ID_roman`, `synopsis`, `contenu`) VALUES ($ID_roman, '{$_POST['synopsis']}', 'Bienvenue dans votre roman! Quel sera le commencement de votre histoire? :)');";

		$typeQuery = "n INSERT";
		$resultat = $db->query ($query);
	}

	// Lire les typeEntite et forme_synopis des questions pour le Genre Littéraire choisi pour ce Roman, leur nombre permet de savoir combien d'insertions faire pour la suite
	if(false !== $resultat){
		$query = "SELECT `genres_litteraires_questions`.`typeEntite`, `genres_litteraires_questions`.`forme_synopsis` FROM genres_litteraires_questions, genres_litteraires_noms WHERE genres_litteraires_questions.ID_genre = genres_litteraires_noms.ID_genre AND genres_litteraires_noms.nom = '{$_POST['genreLitteraire'] }' ORDER BY genres_litteraires_questions.nro_question;";

		$typeQuery = " SELECT";
		$resultat = $db->query ($query);
	}

	// Insérer dans la BD chaque entitée pour lesquelles on as récolté des renseignements de l'usager
	if(false !== $resultat){
		// Collecter les types de questions/typeEntite avec leur "forme_synopsis" qui deviendra le "titre" de l'entitée
		while ($row = $resultat->fetch_row()){
			$typesEntiteQuestions[] = $row;
		}

		/*
			Le premier "questionX" provenant de la QUERY STRING doit s'appeller "questions0" et les autres suivre
			-Durant les INSERT, NEXT est mis à 0, on corrigera pour la bonne valeur dans un UPDATE plus loin
		*/
		$arrPrevNextIDs = array();
		$typeQuery = "n INSERT";
		foreach($typesEntiteQuestions as $key => $val){
			$currEntite = $val[0]; // 'typeEntite'
			if(true !== array_key_exists($currEntite, $arrPrevNextIDs)){
				$arrPrevNextIDs[$currEntite][] = 0;
			}

			$PrevID = $arrPrevNextIDs[$currEntite][count($arrPrevNextIDs[$currEntite]) - 1]; // ID du précédent
			$val[1] = real_escape_string($val[1], $db); // le titre de cette entitée
			$_POST['question'.$key][0] = real_escape_string($_POST['question'.$key][0], $db); // le contenu, ce que l'usager as tapé

			$query = "INSERT INTO `entites` (`ID_roman`, `ID_prev`, `typeEntite`, `titre`, `contenu`%s) VALUES ($ID_roman, $PrevID, '$currEntite', '{$val[1]}', '{$_POST['question'.$key][0]}'%s);";

			if($_POST['question'.$key][1] !== ''){ // la note que l'usager as tapé, si quelque chose
				$_POST['question'.$key][1] = real_escape_string($_POST['question'.$key][1], $db);
				$query = sprintf($query, ", `note`", ", '{$_POST['question'.$key][1]}'");
			}else{
				$query = sprintf($query, '', '');
			}

			$resultat = $db->query ($query);

			if(false !== $resultat){
				$arrPrevNextIDs[$currEntite][] = $db->insert_id; // Lire le nouvel ID (dernier AUTONUM généré);
			}else{
				break;
			}
		}
	}

	// Faire un UPDATE du champs NEXT pour tout les typeEntite qui ont plus d'un membre
	// La requête est un peu compliquée pour permettre de faire toutes les mises à jour d'une seule requête
	if(false !== $resultat){
		$typeQuery = "n UPDATE";
		$query = "UPDATE `entites` SET `ID_next` = CASE `ID_entite`";
		$IDsAChanger = array();
		foreach($arrPrevNextIDs as $val){
			$count_questions = count($val);
			if($count_questions > 2){
				for($iter_questions=1;$iter_questions<($count_questions-1);$iter_questions++){
					$query .= " WHEN " . $val[$iter_questions] . " THEN " . $val[$iter_questions+1];
					$IDsAChanger[] = $val[$iter_questions];
				}
			}
		}
		$query .= " ELSE `ID_next` END WHERE `ID_entite` IN (" . implode(', ', $IDsAChanger) . ");";
		$resultat = $db->query ($query);
	}

	// Traitement des erreurs!
	if(false === $resultat){
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a$typeQuery operation.\n\n" . $db->error . "\n\n $query";
		$resultat2 = $db->query ("ROLLBACK;");
	}else{
		$query="COMMIT;";
		$resultat = $db->query ($query);
		if(false !== $resultat){
			$_POST['titreRoman'] = str_replace('\\', '', $_POST['titreRoman']);
			$resultat = "1¬$ID_roman ¤Le roman \"{$_POST['titreRoman']}\" ainsi que ses ".count($typesEntiteQuestions)." premières entitées, as été créé.";
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during the COMMIT phase ( " . $db->error . " )";
		}
	}

	return $resultat;
}

/* == EOF == */

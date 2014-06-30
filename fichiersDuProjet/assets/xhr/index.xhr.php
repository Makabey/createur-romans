<?php
/*

TODO:
- [] tout réécrire/corriger pour répondre aux besoins des fonctions
- [ ] écrire la fonction de création d'usager
- [ ] écrire la fonction de lecture usager, voir en fait la requête plus bas (sous "page index")
- [ ] fichier XHR + code JS pour query de validité usager (usr+pwd)
- [ ] fichier XHR + code JS pour query de disponiblité nom usager, à moins de fusionner avec "validité" et d'utiliser les codes de retour ex:
 * 0=usager inexistant (usager libre ou nom mal tapé),
 * 1=usager existant (usager indisponible ou nom bien tapé),
 * 2=mot de passe invalide,
 * 3=mot de passe OK (-doit- sous-entendre usager existant) ; suppose que le champs PWD peux être vide et si c'est le cas la validation du PWD n'est pas faite donc pas de code 2 erroné

 après réflection, ici aussi je vais faire 4 fonctions : lire, insérer, effacer et actualiser

*/

require_once "../inc/db_access.inc.php";
require_once "../inc/library01.inc.php";

if(!isset($_POST['oper'])){
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬A required parameter (either "oper", "typeEntite" or "idRoman"), is missing';
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
	case 'lire' : # SELECT
		/*

		*/
		if(isset($_POST[''])){
			$resultat = lireUsager($db);
		}else{
			$resultat = "0¬Specify either contenu, titre, note, idEntite and typeEntite != 'textePrincipal' -or- contenu, idRoman and typeEntite = 'textePrincipal' but not both branches.";
		}
		break;

	case 'inserer': # INSERT
		break;

	case 'actualiser': 	# UPDATE
	case 'effacer':
		break;

	default: $resultat = '0¬"' . $_POST["oper"] . '" unknown value for parameter "oper"';
}

echo $resultat; /* résultat final retourné à XHR */
exit();

/* =================================================== */

/*
	FONCTIONS
*/
function lireUsager($db){
	/*
		Lire de la BD, selon la valeur de $_POST['typeEntite'], les données soit du Texte lui-même soit de l'une des sections d'entitées
	*/
	$arrChamps_entites = array('ID_prev', 'ID_next', 'titre', 'contenu', 'note');
	$resultat = false;
	$query = 'SELECT ID_entite, ' . implode(', ', $arrChamps_entites) . ' FROM entites WHERE ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '" AND deleted = 0 ORDER BY ID_prev ASC;';

	$result = $db->query ($query);
	if(false !== $result){
		$resultat = $result->fetch_row();
		$resultat[$ID_entite] = array_combine($arrChamps_entites, $row);

		/* Convertir en JSON */
		$resultat = json_encode($resultat);
		if(json_last_error() !== 0){
			$resultat = "0¬" . decodeJSON_Error(json_last_error());
		}else{
			$resultat = "1¬" . $resultat;
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a SELECT operation.\n\n" . $db->error . "\n\n $query";
	}

	return $resultat;
}


function miseAJourUsager($db){
	$resultat = false;

	if($_POST[''] == ''){
			$_POST['titre'] = real_escape_string($_POST['titre'], $db);
			$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
			$_POST['note'] = real_escape_string($_POST['note'], $db);
		}else if(isset($_POST['etat'])){ // ont veux "effacer" l'entitee, on prend "etat" pour permettre de changer TRUE/FALSE sans une 2eme fonction
			$query .= 'deleted = ' . $_POST['etat'];
		}// à moins d'erreur dans le code plus haut, je n'ai pas besoin d'un ELSE ultime

	if($resultat === false){
		$result = $db->query ($query);
		if(false !== $result){
			if($db->affected_rows){
				$resultat = "1¬[" . __FUNCTION__ . "] UPDATE successful\n\n '$query'";
			}else{
				$resultat = "0¬[" . __FUNCTION__ . "] UPDATE didn't occur (most probably because there was nothing to change)\n\n $query";
			}
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an UPDATE operation.\n\n" . $db->error . "\n\n $query";
		}
	}
	return $resultat;
}


function insererUsager($db){ // pour le moment ne s'appliquerais qu'aux entitées
	/*
		Insérer une nouvelle entitée dans la BD
	*/
	
		$_POST['titre'] = real_escape_string($_POST['titre'], $db);
		$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
		$_POST['note'] = real_escape_string($_POST['note'], $db);

		$query = 'INSERT INTO entites (ID_roman, ID_prev, ID_next, typeEntite, titre, contenu, note) VALUES (' . $_POST['idRoman'] . ', ' . $ID_prev . ', 0, "' . $_POST['typeEntite'] . '", "' . $_POST['titre'] . '", "' . $_POST['contenu'] . '", "' . $_POST['note'] . '");';
		$queryType = "n INSERT";

		$resultat = $db->query ($query);
	

		$ID_entite = $db->insert_id;

		$query = 'UPDATE entites SET ID_next = ' . $ID_entite . ' WHERE ID_entite = ' . $ID_prev . ';';
		$queryType = "n UPDATE";

		$resultat = $db->query ($query);
	

	// Traitement des erreurs!
	if(false !== $resultat){
		if($db->affected_rows){
			$resultat = "1¬[" . __FUNCTION__ . "] INSERT successful. New ID is " . $ID_entite;
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] UPDATE phase didn't occur\n\n $query";
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a$queryType operation.\n\n" . $db->error . "\n\n $query";
	}
	return $resultat;
}

/* == EOF == */

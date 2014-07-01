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
 * 3=mot de passe OK (-doit- sous-entendre usager existant) ; suppose que le champs PWD peux être vide (pour pouvoir demander si usager existe sans donner d'erreur de MdP) et si c'est le cas la validation du PWD n'est pas faite donc pas de code 2 erroné

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
			Permet de vérifier la validité de la paire usager/pwd -et- savoir si un nom est pris.
		*/
		if(isset($_POST['usager'])){
			$resultat = lireUsager($db);
		}else{
			$resultat = "0¬Missing parameter 'usager'";
		}
		break;

	case 'inserer': # INSERT
		$resultat = insererUsager($db);
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
		Lire de la BD les données de l'usager et retourner leurs validité versus
		ce qui est été reçu par $_POST

		Si ne reçoit pas $_POST['pwd'], alors on ne veux que savoir si le nom est disponible,
		si c'est le cas, l'usager n'est pas authentifié!
		
		retour :
		 0 = user+mdp OK
		1 = mdp erroné
		2 = usager inexistant
		4 = 'usager' ne répond pas à la regexp utilisée par JS -donc- potentiellement injection!
	*/
	$pseudoMatch = preg_match("/[0-9A-Za-z]{4,20}/", $_POST['usager']);

	if(1 === $pseudoMatch){
		$query = 'SELECT ID_usager, pseudo, motdepasse, nom FROM usagers WHERE pseudo = "' . $_POST['usager'] . '" AND deleted = 0;';

		$db_result = $db->query ($query);
		if(false !== $db_result){
			$resultat = 0;

			if($db_result->num_rows > 0){
				$row = $db_result->fetch_row();

				if(isset($_POST['pwd'])){
					$motDePasseMatch = preg_match("/[^\<\>]{8,20}/", $_POST['pwd']);
					if((1 !== $motDePasseMatch) || ($row[2] !== $_POST['pwd'])){
						$resultat = 1;
					}
				}
			}else{
				$resultat = 2; // Pseudo erroné ou introuvable, selon la fct qui recoit le code d'erreur
			}

			if($resultat != 0){
				$resultat = "0¬" . $resultat; // 1 = MdP erroné, 2 = usager erroné/pris, 3 = rien de bon
			}else{
				if(isset($_POST['pwd'])){ // Sans mot de passe, on log pas complètement l'usager
					$resultat = "1¬" . $row[0]; // ID usager
					session_start();
					$_SESSION['usager'] = $row[0];
					$_SESSION['nom'] = ($row[3] !== null)?$row[3]:$row[1];
				}else{
					$resultat = "1¬0"; // ID usager
				}
			}
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a SELECT operation.\n\n" . $db->error . "\n\n $query";
		}
	}else{
		$resultat = "0¬4"; // Nom usager ne repond pas a regexp, potentiel injection!
	}

	return $resultat;
}

/*
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
*/

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

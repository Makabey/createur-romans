<?php
require_once "../inc/db_access.inc.php";
require_once "../inc/library01.inc.php";

if(!isset($_POST['oper']) || !isset($_POST['usager'])){ // oper DOIT être présent
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬A required parameter (either "oper" or "usager"), is missing';
	exit();
}

/*
	Vérifier tout de suite la validité de $_POST['usager'], inutile d'aller plus loin si transige le regexp,
	parce que si 'usager' ne répond pas à la regexp utilisée par JS -donc- potentiellement injection!
*/
$pseudoMatch = preg_match("/[0-9A-Za-z]{4,20}/", $_POST['usager']);
if(1 !== $pseudoMatch){
	echo "0¬4";
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
		#if(isset($_POST['usager'])){
			$resultat = lireUsager($db);
		#}else{
		#	$resultat = "0¬Missing parameter 'usager'";
		#}
		break;

	case 'inserer': # INSERT
		$resultat = insererUsager($db);
		break;

	#case 'actualiser': 	# UPDATE
	case 'effacer':
		if(!isset($_POST['etat'])) { $_POST['etat'] = 1; }
		if(is_numeric($_POST['etat'])){
			$_POST['etat']+=0;
			if($_POST['etat'] !=1) $_POST['etat']=0;
		}else{
			$_POST['etat'] = strtolower($_POST['etat']);
			$_POST['etat'] = ($_POST['etat'] == "true" || $_POST['etat'] == "vrai")?1:0;
		}
		$resultat = miseAJourUsager($db);
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
	*/
	$query = 'SELECT ID_usager, pseudo, motdepasse, nom, est_admin FROM usagers WHERE pseudo = "' . $_POST['usager'] . '" AND deleted = 0;';

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
				$resultat = "1¬" . $row[0] . '¤' . $row[4]; // ID usager + est_admin
				session_start();
				$_SESSION['pseudo'] = $row[1];
				$_SESSION[$row[1]]['idUsager'] = $row[0]; // ID usager
				$_SESSION[$row[1]]['nom'] = ($row[3] !== null)?$row[3]:$row[1]; // Nom sinon Pseudo
				$_SESSION[$row[1]]['est_admin'] = $row[4]+0;
			}else{
				$resultat = "1¬0"; // ID usager
			}
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a SELECT operation.\n\n" . $db->error . "\n\n $query";
	}

	return $resultat;
}

function insererUsager($db){
	/*
		Insérer un nouvel usager dans la BD
	*/
	$resultat = false;

	$motDePasseMatch = preg_match("/[^\<\>]{8,20}/", $_POST['pwd']);
	if(1 === $motDePasseMatch){
		$_POST['pwd'] = real_escape_string($_POST['pwd'], $db);

		$query = "INSERT INTO `usagers` (`pseudo`, `motdepasse`%s) VALUES ('{$_POST['usager']}', '{$_POST['pwd']}'%s);";

		if(isset($_POST['nomUsager'])){
			$nomUsagerMatch = preg_match("/[^\<\>]{1,40}/", $_POST['nomUsager']);
			if(1 === $nomUsagerMatch){
				$_POST['nomUsager'] = real_escape_string($_POST['nomUsager'], $db);
				$query = sprintf($query, ", `nom`", ", '{$_POST['nomUsager']}'");
			}else{
				$query = sprintf($query, '', '');
				unset($_POST['nomUsager']);
			}
		}else{
			$query = sprintf($query, '', '');
		}

		$resultat = $db->query ($query);

		// Traitement des erreurs!
		if(false !== $resultat){
			session_start();
			$_SESSION['pseudo'] = $_POST['usager'];
			$pseudo = $_SESSION['pseudo'];
			$_SESSION[$pseudo]['idUsager'] = $db->insert_id;
			$_SESSION[$pseudo]['nom'] = (isset($_POST['nomUsager']))?$_POST['nomUsager']:$_POST['usager']; // Nom sinon Pseudo
			$resultat = "1¬" . $pseudo;
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an INSERT operation.\n\n" . $db->error . "\n\n $query";
		}

	}else{
		$resultat = "0¬1"; // Mot de passe invalide parce qu'il ne répond pas au regexp, injection potentielle!
	}

	return $resultat;
}

function miseAJourUsager($db){
	$resultat = false;
	$query = 'UPDATE usagers SET deleted = ' . $_POST['etat'] . " WHERE pseudo = '{$_POST['usager']}';";

	$result = $db->query ($query);
	if(false !== $result){
		if($db->affected_rows){
			$resultat = "1¬[" . __FUNCTION__ . "] UPDATE successful\n\n '$query'";
		}else{
			$resultat = "1¬[" . __FUNCTION__ . "] UPDATE didn't occur (most probably because there was nothing to change)\n\n $query";
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an UPDATE operation.\n\n" . $db->error . "\n\n $query";
	}

	return $resultat;
}

/* == EOF == */

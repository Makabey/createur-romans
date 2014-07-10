<?php
/*
---Pour oper = lire
entrées::
-typeEntite doit être l'un de : quoi, ou, comment, pourquoi, qui, quand, textePrincipal
-ID_roman doit etre un chiffre

sorties::
-pour textePrincipal, seulement le texte
-pour les autres, à l'index #0 on as le type de l'entite principalement pour différentier les ID des DIVs contenant la fiche de l'entite et "first" contenant le ID_entite de celle qui est la première parce que son ID_prev=0, le reste sont les données désirées et on peux donc partir de "first" pour suivre les fiches et les afficher dans l'ordre dicté.

============================
---Pour oper = ecrire
entrées::
-idEntite le ID de l'Entite
-typeEntite doit être l'un de : quoi, ou, comment, pourquoi, qui, quand, textePrincipal
-ID_roman doit etre un chiffre
-contenu, titre et note qui sont le contenu de la fiche
=ou=
-contenu seul pour 'textePrincipal'

sortie::
résultat de l'opération

============================
---Pour oper = deplacer
entrées::
-prev/next qui sont les nouvelles valeurs pour les champs correspondant et utile lorsqu'on déplace les entitées visuellement dans l'interface
-idEntite le ID de l'Entite
-typeEntite ne peux pas être 'textePrincipal'
-nvTypeEntite si specifié, change le type de l'entité, ne peux pas etre 'textePrincipal', servirais si on permet de déplacer entre les types

sortie::
résultat de l'opération

============================
---Pour oper = effacer
entrées::
-etat si omis alors défaut à 1 sinon valeurs valides sont 1, 'true', 'vrai' pour effacer, toute autre valeur est considérée 'false'
-idEntite le ID de l'Entite
-typeEntite ne peux pas être 'textePrincipal' <== à changer

sorties::
résultat de l'opération

============================
--Pour oper = inserer
entrées::
-titre, contenu, note
-typeEntite tout sauf 'textePrincipal'

sorties::
résultat de l'opération

*/

require_once "../inc/db_access.inc.php";
require_once "../inc/library01.inc.php";

if(!isset($_POST['oper']) || !isset($_POST['idRoman'])){ // || !isset($_POST['typeEntite'])
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬A required parameter (either "oper" or "idRoman"), is missing'; #, "typeEntite"
	exit();
}

$_POST['idRoman'] += 0;
if($_POST['idRoman'] <= 0){
	echo '0¬Invalid value for "idRoman"';
	exit();
}


$arrValidEntities = array('quoi', 'ou', 'comment', 'pourquoi', 'qui', 'quand', 'textePrincipal', 'notesGenerales');

$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo $db;
	exit();
}

/* =================================================== */

$resultat = false;
switch($_POST['oper']){
	case 'lire' :
		/*
			On as besoin d'un ID pour la balise qui recevra tout le contenu, en principe, ne devrais
			pas être passé mais bon, on peux corriger plus tard
		*/
		#if(('textePrincipal' != $_POST['typeEntite']) && !isset($_POST['target'])){
		#	$resultat = '0¬Parameter "target" is required';
		#}else{
			$resultat = lireDonneesEntite($db);
		#}
		break;

	case 'ecrire':
		if(!isset($_POST['typeEntite'])){
			$resultat = "0¬typeEntite is required for oper = ecrire";
		}else{
			if(!in_array($_POST['typeEntite'], $arrValidEntities)){
				$resultat = "0¬Invalid value for 'typeEntite' ({$_POST['typeEntite']})";
			}else{
				if(isset($_POST['contenu']) &&
					(($_POST['typeEntite'] != 'textePrincipal' && isset($_POST['idEntite']) && isset($_POST['titre']) && isset($_POST['note'])) xor
					(($_POST['typeEntite'] == 'textePrincipal') || ($_POST['typeEntite'] == 'notesGenerales')))
					){
					$resultat = miseAJourDonneesEntite($db);
				}else{
					$resultat = "0¬Specify either contenu, titre, note, idEntite and typeEntite != 'textePrincipal' -or- contenu, idRoman and typeEntite = 'textePrincipal' but not both branches.";
				}
			}
		}
		break;

	case 'deplacer': // Sert à déplacer l'entitée parmis ses collègue et/ou d'un type à un autre (quoi -> quand)
		if(!isset($_POST['typeEntite'])){
			$resultat = "0¬typeEntite is required for oper = ecrire";
		}else{
			if(!in_array($_POST['typeEntite'], $arrValidEntities)){
				$resultat = "0¬Invalid value for 'typeEntite' ({$_POST['typeEntite']})";
			}else{
				if(isset($_POST['idEntite']) && isset($_POST['prev']) && isset($_POST['next']) && $_POST['typeEntite'] != 'textePrincipal'){
					if(isset($_POST['nvTypeEntite'])){
						if($_POST['nvTypeEntite'] == 'textePrincipal'){
							$resultat = "0¬Illegal value 'textePrincipal' for parameter 'nvTypeEntite'.";
						}else if($_POST['nvTypeEntite'] == $_POST['typeEntite']){
							unset($_POST['nvTypeEntite']);
						}
					}

					if(false === $resultat){
						$resultat = miseAJourDonneesEntite($db);
					}
				}else{
					$resultat = "0¬Missing either prev, next or idEntite -or- typeEntite = 'textePrincipal' when it shouldn't be.";
				}
			}
		}
		break;

	case 'effacer': // Supporte à la fois marquer DELETED et restaurer l'entité, donc TRUE/FALSE
		if(isset($_POST['idEntite'])){
			if(!isset($_POST['etat'])) { $_POST['etat'] = 1; }
			if(is_numeric($_POST['etat'])){
				$_POST['etat']+=0;
				if($_POST['etat'] !=1) $_POST['etat']=0;
			}else{
				$_POST['etat'] = strtolower($_POST['etat']);
				$_POST['etat'] = ($_POST['etat'] == "true" || $_POST['etat'] == "vrai")?1:0;
			}
			$resultat = miseAJourDonneesEntite_EtatDeleted($db);
		}else{
			$resultat = "0¬Missing either etat or idEntite.";
		}
		break;

	case 'inserer':
		if(!isset($_POST['typeEntite'])){
			$resultat = "0¬typeEntite is required for oper = ecrire";
		}else{
			if(!in_array($_POST['typeEntite'], $arrValidEntities)){
				$resultat = "0¬Invalid value for 'typeEntite' ({$_POST['typeEntite']})";
			}else{
				if(isset($_POST['titre']) && isset($_POST['contenu']) && isset($_POST['note']) && $_POST['typeEntite'] != 'textePrincipal'){
					$resultat = insererEntite($db);
				}else{
					$resultat = "0¬Missing either idRoman, typeEntite, titre, contenu or note -or- typeEntite = 'textePrincipal' when it shouldn't be.";
				}
			}
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
function lireDonneesEntite($db){
	/*
		Lire de la BD, selon la valeur de $_POST['typeEntite'], les données soit du Texte lui-même soit de l'une des sections d'entitées
	*/
	$arrChamps_entites = array('ID_prev', 'ID_next', 'titre', 'contenu', 'note');
	$arrChamps_roman = array('contenu', 'synopsis', 'notes_globales', 'titre');
	$resultat = false;

	if($_POST['typeEntite'] == 'textePrincipal'){
		$query = "SELECT `roman_texte`.`contenu`, `roman_texte`.`synopsis`, `roman_texte`.`notes_globales`, `roman_details`.`titre` FROM `roman_texte`, `roman_details` WHERE `roman_texte`.`ID_roman` = `roman_details`.`ID_roman` AND `roman_details`.`ID_roman` = " . $_POST['idRoman'] . " AND `roman_details`.`deleted` = 0;";
		$mode=1;
	}else{ // L'une des autres entitées : ou, quand, etc...
		$query = 'SELECT ID_entite, ' . implode(', ', $arrChamps_entites) . ' FROM entites WHERE ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '" AND deleted = 0 ORDER BY ID_prev ASC;';
		$mode=2;
	}

	$result = $db->query ($query);
	if(false !== $result){
		if($mode == 1){
			/* On veux le texte ? Faire une simple lecture */
			$resultat = array_combine($arrChamps_roman, $result->fetch_row());
			// Corriger des transformation faites par la fonction real_escape_string
			$resultat['contenu'] = str_replace('&gt;', '>', $resultat['contenu']);
			$resultat['contenu'] = str_replace('&lt;', '<', $resultat['contenu']);
			$resultat['notes_globales'] = str_replace('&gt;', '>', $resultat['notes_globales']);
			$resultat['notes_globales'] = str_replace('&lt;', '<', $resultat['notes_globales']);
		}elseif($mode == 2){
			$resultat[0]['typeEntite'] = $_POST['typeEntite'];
			#$resultat[0]['target'] = $_POST['target'];
			$resultat[0]['first'] = null;

			while ($row = $result->fetch_row()){
				$ID_entite = array_shift($row);
				if($resultat[0]['first'] === null){
					$resultat[0]['first'] = $ID_entite;
				}
				if($row[1] == 0){ // ID_next
					$resultat[0]['last'] = $ID_entite;
				}
				$resultat[$ID_entite] = array_combine($arrChamps_entites, $row);
			}
		}

		/* Convertir en JSON */
		$resultat = json_encode($resultat);
		#if(json_last_error() !== 0){
		#	$resultat = "0¬" . decodeJSON_Error(json_last_error());
		#}else{
			$resultat = "1¬" . $resultat;
		#}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a SELECT operation.\n\n" . $db->error . "\n\n $query";
	}

	return $resultat;
}


function miseAJourDonneesEntite($db){
	global $arrValidEntities;
	$resultat = false;

	/*if($_POST['typeEntite'] == 'textePrincipal'){
		$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
		$query = 'UPDATE roman_texte SET contenu = "' . $_POST['contenu'] . '" WHERE ID_roman=' . $_POST['idRoman'] . ';';
	}else if($_POST['typeEntite'] == 'notesGenerales'){
		$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
		$query = 'UPDATE roman_texte SET notes_globales = "' . $_POST['contenu'] . '" WHERE ID_roman=' . $_POST['idRoman'] . ';';*/
	if($_POST['typeEntite'] == 'textePrincipal'){
		$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
		$_POST['notes'] = real_escape_string($_POST['notes'], $db);
		$query = 'UPDATE roman_texte SET contenu = "' . $_POST['contenu'] . '", notes_globales = "' . $_POST['notes'] . '" WHERE ID_roman=' . $_POST['idRoman'] . ';';
	}else{
		$query = 'UPDATE entites SET ';
		if(isset($_POST['contenu'])){ // Mise à jour intégrale
			$_POST['titre'] = real_escape_string($_POST['titre'], $db);
			$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
			$_POST['note'] = real_escape_string($_POST['note'], $db);

			$query .= "titre = \"{$_POST['titre']}\", contenu = \"{$_POST['contenu']}\", note = \"{$_POST['note']}\"";
		}else if(isset($_POST['prev'])){ // l'entite as été visuellement déplacée, la partie de manipulation des autres autour d'elle relève du code JS
			$query .= 'ID_prev = ' . $_POST['prev'] . ', ID_next = ' . $_POST['next'];
			if(isset($_POST['nvTypeEntite'])){
				if(!in_array($_POST['nvTypeEntite'], $arrValidEntities)){
					$resultat = "0¬[" . __FUNCTION__ . "] Invalid value for 'nvTypeEntite'";
				}
				$query .= ', typeEntite = "' . $_POST['nvTypeEntite'] . '"';
			}
		}else if(isset($_POST['etat'])){ // ont veux "effacer" l'entitee, on prend "etat" pour permettre de changer TRUE/FALSE sans une 2eme fonction
			$query .= 'deleted = ' . $_POST['etat'];
		}// à moins d'erreur dans le code plus haut, je n'ai pas besoin d'un ELSE ultime

		$query .= ' WHERE ID_entite = ' . $_POST['idEntite'];
		#$query .= ' AND typeEntite = "' . $_POST['typeEntite'] . '"'; // Juste pour être sur qu'on met la bonne entitée à jour
		$query .= ';';
	}

	if($resultat === false){
		$result = $db->query ($query);
		if(false !== $result){
			if($db->affected_rows){
				$resultat = "1¬[" . __FUNCTION__ . "] UPDATE successful\n\n $query";
			}else{
				$resultat = "1¬[" . __FUNCTION__ . "] UPDATE didn't occur (most probably because there was nothing to change)\n\n $query";
			}
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an UPDATE operation.\n\n" . $db->error . "\n\n $query";
		}
	}
	return $resultat;
}

function miseAJourDonneesEntite_EtatDeleted($db){
	global $arrValidEntities;
	#$resultat = false;
	$num_rows = 0;

	if($_POST['etat'] == 1){ // DELETE
		/*
			etapes :
			1. lire pour idEntite le prev/next
			2. update du next avec le chiffre du prev si non 0
			3. idem inverse next/prev
			4. update deleted
		*/
		$query = 'SELECT `ID_prev`, `ID_next` FROM `entites` WHERE `ID_entite` = ' . $_POST['idEntite'] . ';';
		$resultat = $db->query ($query);

		if(false !== $resultat){
			$num_rows = $resultat->num_rows;
			if($num_rows){
				$row_idEntite = $resultat->fetch_row();
				if($row_idEntite[0]  > 0){
					$query = 'UPDATE `entites` SET `ID_next` = ' . $row_idEntite[1] . ' WHERE `ID_entite` = ' . $row_idEntite[0] . ';';
					$resultat = $db->query ($query);
					if(!$db->affected_rows){ $resultat = false; }
				}
			}else{
				$resultat = false;
			}
		}

		if(false !== $resultat){
			if($row_idEntite[1]  > 0){
				//$row_idEntite = $resultat->fetch_row();
				#if($row_idEntite[1]  > 0){
					$query = 'UPDATE `entites` SET `ID_prev` = ' . $row_idEntite[0] . ' WHERE `ID_entite` = ' . $row_idEntite[1] . ';';
					$resultat = $db->query ($query);
					if(!$db->affected_rows){ $resultat = false; }
				#}
			}
		}

		if(false !== $resultat){
			$query = 'UPDATE `entites` SET deleted = ' . $_POST['etat'] . ' WHERE ID_entite = ' . $_POST['idEntite'] . ';';
			$resultat = $db->query ($query);
		}
	}else{ // UNDELETE
		/*
			Sur restauration on la replace à la toute fin; càd que son prev devient celui qui as next==0, corriger celui qui était next aussi
		*/
		$resultat = false; # pour le moment...
	}

	#if($resultat === false){
		//$resultat = $db->query ($query);
		if(false !== $resultat){
			if($db->affected_rows){
				$resultat = "1¬[" . __FUNCTION__ . "] UPDATE successful\n\n '$query'";
			#}else{
			#	$resultat = "0¬[" . __FUNCTION__ . "] UPDATE didn't occur (most probably because there was nothing to change)\n\n $query";
			}
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an UPDATE operation.\n\nError = " . $db->error . "\nnum_rows = $num_rows \n\n $query";
		}
	#}
	return $resultat;
}


function insererEntite($db){ // pour le moment ne s'appliquerais qu'aux entitées
	/*
		Insérer une nouvelle entitée dans la BD
	*/
	// Commencer par relever le ID de l'entité qui as ID_next=0 pour le Roman et le type recheché
	$query = 'SELECT ID_entite FROM entites WHERE ID_next = 0 AND ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '";';
	$queryType = " SELECT";
	$resultat = $db->query ($query);
	// Tenter d'insérer une nouvelle entitée
	if(false !== $resultat){
		if($resultat->num_rows > 0){
			$row = $resultat->fetch_row();
			$ID_prev = $row[0];
		}else{
			$ID_prev = 0;
		}

		$_POST['titre'] = real_escape_string($_POST['titre'], $db);
		$_POST['contenu'] = real_escape_string($_POST['contenu'], $db);
		$_POST['note'] = real_escape_string($_POST['note'], $db);

		$query = 'INSERT INTO entites (ID_roman, ID_prev, ID_next, typeEntite, titre, contenu, note) VALUES (' . $_POST['idRoman'] . ', ' . $ID_prev . ', 0, "' . $_POST['typeEntite'] . '", "' . $_POST['titre'] . '", "' . $_POST['contenu'] . '", "' . $_POST['note'] . '");';
		$queryType = "n INSERT";

		$resultat = $db->query ($query);
	}

	// Mettre à jour l'entité qui avais auparavent ID_next=0 pour mettre le ID de la nouvelle entitée
	if(false !== $resultat){
		$ID_entite = $db->insert_id;

		if($ID_prev > 0){
			$query = 'UPDATE entites SET ID_next = ' . $ID_entite . ' WHERE ID_entite = ' . $ID_prev . ';';
			$queryType = "n UPDATE";

			$resultat = $db->query ($query);
		}
	}

	// Traitement des erreurs!
	if(false !== $resultat){
		if($ID_prev > 0){
			if($db->affected_rows){
				#$resultat = "1¬[" . __FUNCTION__ . "] INSERT successful. New ID is " . $ID_entite;
				$resultat = "1¬" . $ID_entite;
			}else{
				$resultat = "0¬[" . __FUNCTION__ . "] UPDATE phase didn't occur\n\n $query";
			}
		}else{
			$resultat = "1¬" . $ID_entite;
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a$queryType operation.\n\n" . $db->error . "\n\n $query";
	}
	return $resultat;
}

/* == EOF == */

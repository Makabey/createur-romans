<?php
/*

TODO:
	5. faire un peu de validation de type dans les Wrapper JS ?
	6. validation du contenu du texte principal et des entitées pour transformer ou rejetter les balises avant d'envoyer dans la BD
	8. modifier les fonctions pour mettre un IF sur $_POST['typeEntite'] au lieu d'un SWITCH parce que j'ai ajouté une validation au début (qui s'occupe du "option invalide"), on peux donc avoir 'textePrincipal' et ELSE
	9. retirer le besoin pour 'typeEntite' partout où c'est possible, surtout considérant que, tant qu'on parle des entitées, on peux faire presque tout seulement avec leur ID_entite pour les identifier de façon unique.
*/
/*
---Pour oper = lire
entrées::
typeEntite doit être l'un de : quoi, ou, comment, pourquoi, qui, textePrincipal
ID_roman doit etre un chiffre
target doit être le ID d'une balise qui recevra tout le contenu, p-ê trouver un moyen pour ne pas avoir à le passer ici, la nécessitée est née de mon obstination à ne pas vouloir laisser trainer une variable globale contenant la valeur ou le hardcoder dans la fonction de réception laquelle ne pourrais traiter alors qu'une seule valeur parmis "quoi, ou, comment, pourquoi, qui", la solution étant probablement 5 variations de la fonction et de la duplication inutile de code DONC une autre solution serait une fonction capable de savoir où elle en est et rappeller la fonction XHR pour passer d'un onglet (quoi, ou, comment, pourquoi, qui ) à l'autre....

sorties::
pour textePrincipal, seulement le texte
pour les autres, à l'index #0 on as le target, càd le ID de la balise qui doit recevoir le code/l'affichage, le type de l'entite principalement pour différentier les ID des DIVs contenant la fiche de l'entite et "first" contenant le ID_entite de celle qui est la première parce que son ID_prev=0, le reste sont les données désirées et on peux donc partir de "first" pour suivre les fichers et les afficher dans l'ordre dicté.

============================
---Pour oper = ecrire
entrées::
idEntite le ID de l'Entite
typeEntite doit être l'un de : quoi, ou, comment, pourquoi, qui, textePrincipal
ID_roman doit etre un chiffre
contenu, titre et note qui sont le contenu de la fiche
=ou=
contenu seul pour 'textePrincipal'

sortie::
résultat de l'opération

============================
---Pour oper = deplacer
entrées::
prev/next qui sont les nouvelles valeurs pour les champs correspondant et utile lorsqu'on déplace les entitées visuellement dans l'interface
idEntite le ID de l'Entite
typeEntite ne peux pas être 'textePrincipal'
nvTypeEntite si specifié, change le type de l'entité, ne peux pas etre 'textePrincipal', servirais si on permet de déplacer entre les types

sortie::
résultat de l'opération

============================
---Pour oper = effacer
entrées::
etat si omis alors défaut à 1 sinon valeurs valides sont 1, 'true', 'vrai' pour effacer, toute autre valeur est considérée 'false'
idEntite le ID de l'Entite
typeEntite ne peux pas être 'textePrincipal'

sorties::
résultat de l'opération

============================
--Pour oper = inserer
entrès::
titre, contenu, note
typeEntite tout sauf 'textePrincipal'

sorties::
résultat de l'opération

*/

require_once "../inc/db_access.inc.php";
require_once "../inc/library01.inc.php";

if(!isset($_POST['oper']) || !isset($_POST['idRoman'])){ // || !isset($_POST['typeEntite'])
	// Pour JavaScript : 0/1 : false/true ¬ texte erreur
	echo '0¬A required parameter (either "oper", "typeEntite" or "idRoman"), is missing';
	exit();
}

$_POST['idRoman'] += 0;
if($_POST['idRoman'] <= 0){
	echo '0¬Invalid value for "idRoman"';
	exit();
}

$arrValidEntities = array('quoi', 'ou', 'comment', 'pourquoi', 'qui', 'textePrincipal');
if(!in_array($_POST['typeEntite'], $arrValidEntities)){
	echo "0¬Invalid value for 'typeEntite'";
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
	case 'lire' :
		/* On as besoin d'un ID pour la balise qui recevra tout le contenu, en principe, ne devrais pas être passé mais bon, on peux corriger plus tard */
		if(('textePrincipal' != $_POST['typeEntite']) && !isset($_POST['target'])){
			$resultat = '0¬Parameter "target" is required';
		}else{
			$resultat = lireDonneesEntite($db);
		}
		break;

	case 'ecrire':
		if(isset($_POST['contenu']) &&
			(($_POST['typeEntite'] != 'textePrincipal' && isset($_POST['idEntite']) && isset($_POST['titre']) && isset($_POST['note'])) xor
			($_POST['typeEntite'] == 'textePrincipal'))# && isset($_POST['idRoman'])))
			){
			$resultat = miseAJourDonneesEntite($db);
		}else{
			$resultat = "0¬Specify either contenu, titre, note, idEntite and typeEntite != 'textePrincipal' -or- contenu, idRoman and typeEntite = 'textePrincipal' but not both branches.";
		}
		break;

	case 'deplacer':
		if(isset($_POST['idEntite']) && isset($_POST['prev']) && isset($_POST['next']) && $_POST['typeEntite'] != 'textePrincipal'){
			if(isset($_POST['nvTypeEntite'])){
				if($_POST['nvTypeEntite'] == 'textePrincipal'){
					$resultat = "0¬Illegal value 'textePrincipal' for parameter 'nvTypeEntite'.";
				}else if($_POST['nvTypeEntite'] == $_POST['typeEntite']){
					unset($_POST['nvTypeEntite']);
			#		$resultat = miseAJourDonneesEntite($db);
			#	}else{
			#		$resultat = miseAJourDonneesEntite($db);
				}
			}
			#}else{
			if(false === $resultat){
				$resultat = miseAJourDonneesEntite($db);
			}
		}else{
			$resultat = "0¬Missing either prev, next or idEntite -or- typeEntite = 'textePrincipal' when it shouldn't be.";
		}
		break;

	case 'effacer':
		if(isset($_POST['idEntite']) && $_POST['typeEntite'] != 'textePrincipal'){
			if(!isset($_POST['etat'])) { $_POST['etat'] = 1; }
			if(is_numeric($_POST['etat'])){
				$_POST['etat']+=0;
				if($_POST['etat'] !=1) $_POST['etat']=0;
			}else{
				$_POST['etat'] = strtolower($_POST['etat']);
				$_POST['etat'] = ($_POST['etat'] == "true" || $_POST['etat'] == "vrai")?1:0;
			}
			$resultat = miseAJourDonneesEntite($db);
		}else{
			$resultat = "0¬Missing either etat or idEntite -or- typeEntite = 'textePrincipal' when it shouldn't be.";
		}
		break;

	case 'inserer':
		if(isset($_POST['titre']) && isset($_POST['contenu']) && isset($_POST['note']) && $_POST['typeEntite'] != 'textePrincipal'){
			$resultat = insererEntite($db);
		}else{
			$resultat = "0¬Missing either idRoman, typeEntite, titre, contenu or note -or- typeEntite = 'textePrincipal' when it shouldn't be.";
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
		Lire de la BD, selon la valeur de $_POST['typeEntite'], les donnes soit du Texte lui-même soit de l'une des sectiosn d'entitées
	*/
	#$arrChamps_genres_litteraires = array('nom', 'nro_question', 'texte', 'type_input', 'valeurs_defaut', 'bouton_fonction');
	$arrChamps_entites = array('ID_prev', 'ID_next', 'titre', 'contenu', 'note');
	$resultat = false;

	switch($_POST['typeEntite']){
		case 'quoi' :
		case 'ou' :
		case 'comment' :
		case 'pourquoi' :
		case 'qui' :
			$query = 'SELECT ID_entite, ' . implode(', ', $arrChamps_entites) . ' FROM entites WHERE ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '" AND deleted = 0 ORDER BY ID_prev ASC;';
			$mode=2;
			break;
		case 'textePrincipal' :
			$query = "SELECT contenu FROM roman_texte WHERE ID_roman=".$_POST['idRoman'].";";
			$mode=1;
			break;
		default:
			$resultat = "0¬[" . __FUNCTION__ . "] Invalid value for 'typeEntite'";
	}

	if($resultat === false){
		$result = $db->query ($query);
		if(false !== $result){
			if($mode == 1){
				/* On veux le texte ? Faire une simple lecture */
				$row = $result->fetch_row();
				$resultat = $row[0];
			}elseif($mode == 2){
				$resultat[0]['typeEntite'] = $_POST['typeEntite'];
				$resultat[0]['target'] = $_POST['target'];
				$resultat[0]['first'] = null;

				while ($row = $result->fetch_row()){
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
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a SELECT operation. (query = '$query')";
		}
	}
	return $resultat;
}


function miseAJourDonneesEntite($db){
	global $arrValidEntities;
	$resultat = false;

	switch($_POST['typeEntite']){
		case 'quoi' :
		case 'ou' :
		case 'comment' :
		case 'pourquoi' :
		case 'qui' :
			$query = 'UPDATE entites SET ';
			if(isset($_POST['contenu'])){ // Mise à jour intégrale
				/*
					TODO: validation des valeurs de contenu, titre, note
				*/
				$query .= "titre = \"{$_POST['titre']}\", contenu = \"{$_POST['contenu']}\", note = \"{$_POST['note']}\"";
			}else if(isset($_POST['prev'])){ // l'entite as été visuellement déplacée, la partie de manipulation des autres autour d'elle relève du code JS
				$query .= 'ID_prev = ' . $_POST['prev'] . ', ID_next = ' . $_POST['next'];
				if(isset($_POST['nvTypeEntite'])){
					if(!in_array($_POST['nvTypeEntite'], $arrValidEntities)){
						$resultat = "0¬[" . __FUNCTION__ . "] Invalid value for 'nvTypeEntite'";
					}
					$query .= ', typeEntite = "' . $_POST['nvTypeEntite'] . '"';
				}
			}else if(isset($_POST['etat'])){ // ont veux "effacer" l'entitee
				$query .= 'deleted = ' . $_POST['etat'];
			}// à moins d'erreur dans le code plus haut, je n'ai pas besoin d'un ELSE ultime

			$query .= ' WHERE ID_entite = ' . $_POST['idEntite'];
			#if(!isset($_POST['nvTypeEntite'])){
				$query .= ' AND typeEntite = "' . $_POST['typeEntite'] . '"';
			#}
			$query .= ';';
			break;
		case 'textePrincipal' :
			/*
				TODO: validation valeur de contenu, spécialement si les règles sont autres que pour les entités
			*/
			$query = 'UPDATE roman_texte SET contenu = "' . $_POST['contenu'] . '" WHERE ID_roman=' . $_POST['idRoman'] . ';';
			break;
		default:
			$resultat = "0¬[" . __FUNCTION__ . "] Invalid value for 'typeEntite'";
	}

	if($resultat === false){
		$result = $db->query ($query);
		if(false !== $result){
			if($db->affected_rows){
				$resultat = "1¬[" . __FUNCTION__ . "] UPDATE successful :: '$query'";
			}else{
				$resultat = "0¬[" . __FUNCTION__ . "] UPDATE didn't occur :: '$query'";
			}
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an UPDATE operation. (query = '$query')";
		}
	}
	return $resultat;
}


function insererEntite($db){ // pour le moment ne s'appliquerais qu'aux entitées
	$query = 'SELECT ID_entite FROM entites WHERE ID_next = 0 AND ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '";';

	$result = $db->query ($query);
	if(false !== $result){
		$row = $result->fetch_row();
		$ID_prev = $row[0]+0; // Le "+0" est pour les cas où le nouvel enregistrement est le premier pour un type d'entite, évite de faire un appel à $result->num_rows
		$query = 'INSERT INTO entites (ID_roman, ID_prev, ID_next, typeEntite, titre, contenu, note) VALUES (' . $_POST['idRoman'] . ', ' . $ID_prev . ', 0, "' . $_POST['typeEntite'] . '", "' . $_POST['titre'] . '", "' . $_POST['contenu'] . '", "' . $_POST['note'] . '");';

		$result = $db->query ($query);
		if(false !== $result){
			$ID_entite = $db->insert_id;
			$query = 'UPDATE entites SET ID_next = ' . $ID_entite . ' WHERE ID_entite = ' . $ID_prev . ';';

			$result = $db->query ($query);
			if(false !== $result){
				$resultat = "1¬[" . __FUNCTION__ . "] INSERT successful. New ID is " . $ID_entite;
			}else{
				$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an UPDATE operation. (query = '$query')";
			}
		}else{
			$resultat = "0¬[" . __FUNCTION__ . "] An error occured during an INSERT operation. (query = '$query')";
		}
	}else{
		$resultat = "0¬[" . __FUNCTION__ . "] An error occured during a SELECT operation. (query = '$query')";
	}
	return $resultat;
}

/* == EOF == */

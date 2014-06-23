<?php
/*

TODO:
	1. Créer un premier bloc (qui fini avec un commentaire / * ================ * / ) qui valide rapidement les incontournables comme oper, typeEntite, idRoman
	2. Réécrire le gros "if(oper = ceci){}" pour devenir un switch qui appelle des fonctions
	3. Les fonctions ne doivent plus valider si un param $_POST est disponible ou non, le switch le fait avant de les appeller (il doit chercher les params appropriés, ex: entre le Texte Principal et les Entitées, les besoins varient un peu, l'un peut vouloir un 'target', l'autre un 'idEntite' ou autre, à vérifier)
	4. voir s'il y as du code qu'il est possible d'éviter de fusionner

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

/*if($_POST['oper'] != 'lire' && $_POST['oper'] != 'ecrire'){
	echo '0¬Invalid value for $_POST["oper"]';
	exit();
}*/

$_POST['idRoman'] += 0;
if($_POST['idRoman'] <= 0){
	echo '0¬Invalid value for $_POST["idRoman"]';
	exit();
}

$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo "0¬(DB pas un objet) " . $db;
	exit();
}

$resultat = false;
//$mode=1;

if($_POST['oper'] == 'lire'){
/*
	Lire de la BD, selon la valeur de $_POST['typeEntite'], les donnes soit du Texte lui-même soit de l'une des sectiosn d'entitées
*/
	switch($_POST['typeEntite']){
		case 'quoi' :
		case 'ou' :
		case 'comment' :
		case 'pourquoi' :
		case 'qui' :
			#$resultat = '0¬Invalid value for $_POST["typeEntite"]';
			$query = 'SELECT ID_entite, ' . implode(', ', $arrChamps_entites) . ' FROM entites WHERE ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '" AND deleted = 0 ORDER BY ID_prev;';
			$mode=2;
			break;
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
			/* On as besoin d'un ID pour la balise qui recevra tout le contenu, en principe, ne devrais pas être passé mais bon, on peux corriger plus tard */
			if(!isset($_POST['target'])){
				echo '0¬Invalid value for $_POST["target"]';
				exit();
			}
			$resultat[0]['typeEntite'] = $_POST['typeEntite'];
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
}else if(
($_POST['oper'] == 'ecrire' && (isset($_POST['target']) || isset($_POST['contenu']) || isset($_POST['prev'])))
||
($_POST['oper'] == 'effacer' && isset($_POST['delete']))
){
/*
---Pour oper = lire
entrées::
typeEntite doit être l'un de : quoi, ou, comment, pourquoi, qui, textePrincipal
ID_roman doit etre un chiffre
target doit être le ID d'une balise qui recevra tout le contenu, p-ê trouver un moyen pour ne pas avoir à le passer ici, la nécessitée est née de mon obstination à ne pas vouloir laisser trainer une variable globale contenant la valeur ou le hardcoder dans la fonction de réception laquelle ne pourrais traiter alors qu'une seule valeur parmis "quoi, ou, comment, pourquoi, qui", la solution étant probablement 5 variations de la fonction et de la duplication inutile de code DONC une autre solution serait une fonction capable de savoir où elle en est et rappeller la fonction XHR pour passer d'un onglet (quoi, ou, comment, pourquoi, qui ) à l'autre....

sorties::
pour textePrincipal,  seulement le texte
pour les autres, à l'index #0 on as le target, càd le ID de la balise qui doit recevoir le code/l'affichage, le type de l'entite principalement pour différentier les ID des DIVs contenant  la fiche de l'entite et "first" contenant le ID_entite de celle qui est la première parce que son ID_prev=0

---Pour oper=ecrire et effacer...
*/



	switch($_POST['typeEntite']){
		case 'quoi' :
		case 'ou' :
		case 'comment' :
		case 'pourquoi' :
		case 'qui' :
			$query = 'UPDATE entites SET ';
			if(isset($_POST['target'])){ // cette option devrais disparaitre au profit de la suivante qui sauvegarde toute l'entitée
				$query .= $_POST['target'] . ' = "' . $_POST['donnees'] . '"';
			}else if(isset($_POST['contenu'])){ // Mise à jour intégrale
				$query .= 'titre = "' . $_POST['titre'] . '", contenu = "' . $_POST['contenu'] . '", note = "' . $_POST['note'] . '"';
			} else if(isset($_POST['delete'])){ // ont veux "effacer" l'entitee
				$query .= 'deleted = ' . (($_POST['delete'] == '1' || $_POST['delete'] == "true")?1:0);
			}else if(isset($_POST['prev'])){ // l'entite as été déplacée, la partie de manipulation des autres autour d'elle relève du code JS
				$query .= 'ID_prev = ' . $_POST['prev'] . 'ID_next = ' . $_POST['next'];
			}else{
				#if(!isset($_POST['target'])){
				#	echo '0¬Invalid value for $_POST["target"]';
					echo '0¬Expected either one of : target, contenu, delete, prev.';
					exit();
				#}
			}

			$query .= ' WHERE ID_entite = ' . $_POST['idEntite'] . ';';
			break;
		case 'textePrincipal' :
			#$mode=1;
			$query = 'UPDATE roman_texte SET contenu = "' . $_POST['donnees'] . '" WHERE ID_roman=' . $_POST['idRoman'] . ';';
			break;
		default: echo '0¬Invalid value for $_POST["typeEntite"]'; exit();
	}

	$result = $db->query ($query);
	if(false !== $result){
		$resultat = "1¬UPDATE successful :: " . $query;
	}else{
		$resultat = "0¬An error occured during an UPDATE operation. (query = '$query')";
	}
#}else if($_POST['oper'] == 'effacer'){ // mettre flag deleted = true
#	$resultat = "0¬Valid but unimplemented operation '" . $_POST['oper'] . "'";
}else if($_POST['oper'] == 'inserer'){ // pour le moment ne s'appliquerais qu'aux entitées
/*echo "0¬rendu a inserer";
exit();*/

	if(!isset($_POST['idRoman']) || !isset($_POST['typeEntite']) || !isset($_POST['titre']) || !isset($_POST['contenu']) || !isset($_POST['note'])){
		$resultat = "0¬Expected all of those : idRoman, typeEntite, titre, contenu, note";
	}else{
/*echo "0¬apres verification touts champs la";
exit();*/
	
		/*
			1. noter selon roman et typeEntite, le ID_entite de celui qui as next=0
			2. ecrire la nouvelle entite avec prev = ID_entite trouvé en etape 1 (et son next=0! :) )
			3. lire le nouvel ID_entite ($mysqli->insert_id) et copier dans celui en etape 1
			
			tests :
			- 1er d'un type
			- [x] Xeme d'un type
			- [x]donnees avec guillement et apostrophes
				* avec apostrophe passe bien, tester plus
				* pour les guillemets, je dois mettre \\" , trouver la bonne fonction pour escaper les chars, à moins de mettre des entities
			- [x] params manquant
		*/
		$query = 'SELECT ID_entite FROM entites WHERE ID_next = 0 AND ID_roman = ' . $_POST['idRoman'] . ' AND typeEntite = "' . $_POST['typeEntite'] . '";';

		/*echo "1¬Query = ' $query '";
		exit();*/

		$result = $db->query ($query);
		if(false !== $result){
			$row = $result->fetch_row();
			$ID_prev = $row[0]+0; // Le "+0" est pour les cas où le nouvel enregistrement est le premier pour un type d'entite, évite de faire un appel à $result->num_rows
			$query = 'INSERT INTO entites (ID_roman, ID_prev, ID_next, typeEntite, titre, contenu, note) VALUES (' . $_POST['idRoman'] . ', ' . $ID_prev . ', 0, "' . $_POST['typeEntite'] . '", "' . $_POST['titre'] . '", "' . $_POST['contenu'] . '", "' . $_POST['note'] . '");';

			/*echo "1¬ID_prev = $ID_prev :: Query = ' $query '";
			exit();*/

			$result = $db->query ($query);
			if(false !== $result){
				$ID_entite = $db->insert_id;
				$query = 'UPDATE entites SET ID_next = ' . $ID_entite . ' WHERE ID_entite = ' . $ID_prev . ';';

				/*echo "1¬Query = ' $query '";
				exit();*/
				$result = $db->query ($query);
				if(false !== $result){
					$resultat = "1¬INSERT successful. New ID is " . $ID_entite;
				}else{
					$resultat = "0¬An error occured during an UPDATE operation. (query = '$query')";
				}
			}else{
				$resultat = "0¬An error occured during an INSERT operation. (query = '$query')";
			}
		}else{
			$resultat = "0¬An error occured during a SELECT operation. (query = '$query')";
		}
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

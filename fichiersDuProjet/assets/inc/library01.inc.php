<?php

############
#
#	Retourne une chaine de $nombre tabulations
#	en tant que tel un peu inutile mais bon, moins long à écrire que la commande elle-même
#	écrite originalement pour "corriger" le code HTML généré par PHP afin qu'il soit plus lisible/indenté
#
function tabs($nombre){
	return str_repeat(chr(9),$nombre);
}


#############
#
#	Validation de chaines par Expression Régulière et autres mesures
#
function cleanUpString($chaine,$typeValidation='a1') {
	if (strlen($chaine)) {
		switch ($typeValidation) {
			/*case 'list': # ex: "1;Nereide"
				$chaine=preg_replace('/[^0-9a-zA-Z;]/','',$chaine);
				break;*/
			case 1:
				$chaine=preg_replace('/[^0-9]/','',$chaine);
				break;
			case 'a':
				$chaine=preg_replace('/[^a-zA-Z]/','',$chaine);
				break;
			/*case 'items': # ex: -1;0;0;0
				$chaine=preg_replace('/[^0-9\-;]/','',$chaine);
				break;*/
			/*case 'desc' : #ex mots avec espaces
				#$chaine=preg_replace('/[^0-9a-zA-Z,:;\' ]/','',$chaine);
				$chaine=str_replace('<', '&lt;', $chaine);
				$chaine=str_replace('>', '&gt;', $chaine);
				$chaine=str_replace('/', '|', $chaine);
				break;*/
			default : # 'a1'
				$chaine=preg_replace('/[^0-9a-zA-Z]/','',$chaine);
		}
	}

	return $chaine;
}


##############
#
# Encode la chaine
#
function encode($Chaine){
	return htmlentities($Chaine, ENT_COMPAT, 'ISO-8859-1');
}


##############
#
# Retourne une chaine représentant l'erreur JSON passée
#
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

##############
#
#	Retourne un tableau avec les clés répondant à la sous-chaine désirée
#
#	Parametres:
#		$arrHaystack : (array) le tableau à parcourir
#		$keyString : (string) la chaine recherchée
#		$anyWhere : (bool = false / optionnel) si TRUE, alors la sous-chaine peux être n'importe où dans la clé sondée
#		$caseInsensitive : (bool = false /optionnel) si TRUE, alors ignore la casse
#
#	Retour :
#		un tableau contenant les clés trouvées ou un tableau vide si erreur
#
function array_keys_like($arrHaystack, $keyString, $anyWhere = false, $caseInsensitive = false){
	// vérifier et forcer le type des paramètres
	if(!is_array($arrHaystack) || empty($arrHaystack) || !is_string($keyString) || !is_bool($anyWhere) || !is_bool($caseInsensitive)){return NULL;}

	$tableau = array();
	$findpos = ($caseInsensitive)?'stripos':'strpos';

	foreach($arrHaystack as $key => $val){
		$foundPos = $findpos($key, $keyString);

		// Soit on veux que $key commence impératigement par $keyString,
		// soit on ne veux que trouver $keyString dans $key
		if((!$anyWhere && $foundPos === 0) || ($anyWhere && $foundPos !== false)){
			$tableau[]=$key;
		}
	}

	return $tableau;
}

/* == EOF == */

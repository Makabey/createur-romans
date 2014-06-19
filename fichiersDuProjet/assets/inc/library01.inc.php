<?php

############
#
# Retourne une chaine de $nombre tabulations
# en tant que tel un peu inutile mais bon, moins long à écrire que la commande elle-même
#
function tabs($nombre){
	return str_repeat(chr(9),$nombre);
}


#############
#
# Validation de chaines par Expression Régulière et autres mesures
#
function cleanUpString($chaine,$typeValidation='a1') {
	if (strlen($chaine)) {
		switch ($typeValidation) {
			case 'list': # ex: "1;Nereide"
				$chaine=preg_replace('/[^0-9a-zA-Z;]/','',$chaine);
				break;
			case 1:
				$chaine=preg_replace('/[^0-9]/','',$chaine);
				break;
			case 'a':
				$chaine=preg_replace('/[^a-zA-Z]/','',$chaine);
				break;
			case 'items': # ex: -1;0;0;0
				$chaine=preg_replace('/[^0-9\-;]/','',$chaine);
				break;
			case 'desc' : #ex mots avec espaces
				#$chaine=preg_replace('/[^0-9a-zA-Z,:;\' ]/','',$chaine);
				$chaine=str_replace('<', '&lt;', $chaine);
				$chaine=str_replace('>', '&gt;', $chaine);
				$chaine=str_replace('/', '|', $chaine);
				break;
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

/* == EOF == */

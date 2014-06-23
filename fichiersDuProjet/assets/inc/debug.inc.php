<?php
/*
	But : accumuler les messages de débogguage pour les afficher à un endroit opportun du BODY et éviter de briser le flux de la page ou causer des erreurs phantômes.

	Utilisation du module:
		-Au début de la page appeller "clearDebugMsg" si nécessaire
		-Pour chaque point où un message de débogguage est souhaité, appeller "addToDebugMsg" en lui passant le message et/ou contenu désiré
		-Appeller "echoDebugMsg" là où l'affichage ne causera pas de problèmes
*/

$gblDebugReturnMessage=null;
#define("GBL_DEBUG", FALSE); // Mettre en mode Debug?
define("GBL_DEBUG", TRUE);

/*
	Vider la variable globale
*/
function clearDebugMsg(){
	global $gblDebugReturnMessage;

	$gblDebugReturnMessage=null;
}

/*
	Ajouter du contenu à la variable
*/
function addToDebugMsg($chaine){
	global $gblDebugReturnMessage;
	
	if(!empty($chaine)){
		$callers=debug_backtrace();
		if(isset($callers[1]['function'])){
			$appellant = " >> {$callers[1]['function']}";
		}else{
			$appellant = '';
		}
		$gblDebugReturnMessage .= "[DEBUG$appellant] $chaine<br />".PHP_EOL;
	}
}

/*
	Juste afficher le message
*/
function echoDebugMsg(){
	global $gblDebugReturnMessage;

	if(defined('GBL_DEBUG') && (GBL_DEBUG == true)){
		$sMsgSortie = $gblDebugReturnMessage;
		if(empty($gblDebugReturnMessage)){
			$sMsgSortie = '[echoDebugMsg] gblDebugReturnMessage est &lt;VIDE&gt; [/echoDebugMsg]';
		}else{
			echo '<p style="border:3px ridge grey; padding:10px;">',$sMsgSortie,'</p>',PHP_EOL;
		}
	}
}

/** == EOF == **/

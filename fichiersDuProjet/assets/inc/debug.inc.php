<?php
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

#############
#
# Idem que 'print/echo' mais affiche seulement quand la globale $gblDebugOn est TRUE.
#
function debug($Chaine){
	echo "[Avertissement] Il faut maintenant utiliser 'echoDebugMsg'!\n";
	echoDebugMsg();
}
// ? > // Parce qu'il n'y as que du PHP dans le fichier, on peux omettre le tag de fin
/** == EOF == **/

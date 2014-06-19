"use strict";

$(function(){
	function lireListeStylesLitteraires()
		/*
			On doit attrapper l'évènement SUBMIT directement sur le FORM parce que si on agit sur le
			CLICK d'un bouton et que le FORM n'est pas valide selon le BROWSER, la fonction du
			bouton est appellée malgré tout.
		*/
		var xhr = getXhr();
		var urlAuthentify="assets/xhr/creationProjet.xhr.php";
		var queryString;
		var xhrAnswer;

		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
			// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				xhrAnswer = xhr.responseText;
				/*xhrAnswer = xhrAnswer.split("\r\n");
				xhrAnswer = parseInt(xhrAnswer[0]);*/
				
				parseJSON...

				if(xhrAnswer == true){
					afficherGenres(xhrAnswer);
				}else{
					console.log("Une erreur est arrivée, traiter plus sérieusement plus tard...");
				}
			}
		}

		queryString = "etape=lireStyles";

		xhr.open("POST", urlAuthentify, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(queryString);

		return false;
	});
});

/* == EOF == */

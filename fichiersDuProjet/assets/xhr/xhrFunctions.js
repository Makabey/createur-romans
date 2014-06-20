/*
	Référence :: http://siddh.developpez.com/articles/ajax/
*/

function execXHR_Request(urlAuthentify, queryString, fct_callBack){
		/*
			On doit attrapper l'évènement SUBMIT directement sur le FORM parce que si on agit sur le
			CLICK d'un bouton et que le FORM n'est pas valide selon le BROWSER, la fonction du
			bouton est appellée malgré tout.
		*/
		var xhr = getXhr();
		//var urlAuthentify="assets/xhr/creationProjet.xhr.php";
		//var queryString;
		var xhrAnswer;

		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
			// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				xhrAnswer = xhr.responseText;
				console.log(xhrAnswer);
				/*xhrAnswer = xhrAnswer.split("\r\n");
				xhrAnswer = parseInt(xhrAnswer[0]);*/

				xhrAnswer = JSON.parse(xhrAnswer); // contraire :: JSON.stringify(array);

				if(false === xhrAnswer){
					console.log("Une erreur est arrivée, traiter plus sérieusement plus tard...");
				}else{
					fct_callBack(xhrAnswer);
				}
			}
		}

		//queryString = "etape=lireStyles";

		xhr.open("POST", urlAuthentify, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(queryString);

		return false;
	}


function getXhr(){
	var xhr = null;

	if(window.XMLHttpRequest){ // Firefox et autres
		xhr = new XMLHttpRequest();
	}else if(window.ActiveXObject){ // Internet Explorer
		try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
	}
	else { // XMLHttpRequest non supporté par le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
		xhr = false;
	}
	return xhr;
}

/* == EOF == */

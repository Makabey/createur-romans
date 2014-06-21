/*
	Référence :: http://siddh.developpez.com/articles/ajax/
*/

function execXHR_Request(urlAuthentify, queryString, fct_callBack, fct_callError){
	var xhr = getXhr();
	var xhrAnswer;

	if(false !== xhr){
		/*
			if (xhr && xhr.readyState != 0) {
				xhr.abort(); // On annule la requête en cours !
			}
		*/
	
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
			// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				//console.log(xhr.responseText);
				xhrAnswer = xhr.responseText.split('¬');
				//console.log(xhrAnswer);
				var retour = (xhrAnswer.length > 1)?xhrAnswer[1]:xhrAnswer[0];
				//console.log(retour);
				if(xhrAnswer[0] == false ){
					fct_callError(retour);
					//console.log('execXHR_Request = false');
				}else{
					fct_callBack(retour);
					//console.log('execXHR_Request = true');
				}
			}
		}

		xhr.open("POST", urlAuthentify, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(queryString);
	}
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

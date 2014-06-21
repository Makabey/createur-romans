<?php
$sPageTitle = "Acceuil | ";

include "../assets/inc/header.inc.php";
include "../assets/inc/db_access.inc.php";
?>
<script src="../assets/xhr/xhrFunctions.js"></script>
<script>
/*
===Ci-dessous, ce que chaque champs DOIT contenir pour la table "genres_littéraires"
	- pour input="text" :
			'nom' => le nom du genre tel qu'affiché à l'usager, ex :Policier, Drame
			'nro_question' => position/ordre de la question pour ce genre, c'est à dire que chaque genre as une question #1
			'texte' => texte de la question/qui doit apparaitre dans le label du form
			'type_input' => 'text' pour cette option (autre possibilité : 'select')
			'valeurs_defaut' => vide/NULL =ou= placeholder¤option1¤option2¤optionN-1 ex: "entrez un nom" =ou= "tapez une lettre pour inspiration¤Jack¤Sammy¤Tim" -> 'Jack¤Sammy¤Tim' deviennent 3 choix dans un datalist =ou= "¤choix1¤choix2" autrement dit, le placeholder est optionnel
			'bouton_fonction => vide/NULL =ou= si on veux une fonction attachée comme par exemple générer un nom de X à Y lettres "generer_nom(4,6)¤Générer un Nom", la partie après le "¤" est l'étiquette

	- pour input="select" :
			'nom' => le nom du genre tel qu'affiché à l'usager
			'nro_question' => position/ordre de la question pour ce genre, c'est à dire que chaque genre as une question #1
			'texte' => texte de la question/qui doit apparaitre dans le label du form
			'type_input' => 'select' pour cette option (autre possibilité : 'text')
			'valeurs_defaut' => ne peux PAS etre vide, liste des options séparée par des "¤" (la valeur sera la position numérique dans la liste d'options, au moment d'afficher on reprend le texte(?))
			'bouton_fonction => vide/NULL, ne sert pas pour les 'select'

===chronologie des fonctions:
	-xhrFunctions.js::execXHR_Request ( genres Littéraire [creationProjet.xhr.php =>  db_access.php])
	-attente du retour XHR
	-appel à "afficherGenres"
	-attente de réponse de l'usager
	-appel à '$("#button_nextQuestion").click'
	-xhrFunctions.js::execXHR_Request ( questions selon GL sélectionné [creationProjet.xhr.php =>  db_access.php])
	-attente du retour XHR
	-appel à "afficherQuestions"
	-attente de réponse de l'usager
	-appel à '$("#button_nextQuestion").click' mais cette fois, devrais détecter qu'on ne peux aller plus loin puisqu'on n'as qu'une page avec toutes les questions ensemble
*/
	$(function(){
		var donneesQuestions = null;
		var etapeAssistant = 0;

		$("#button_nextQuestion").click(function(){
			if(etapeAssistant == 0){
				var XHR_Query = "etape=lireQuestions&genre=" + $("#select_question").val();
				execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, afficherQuestions, traiterErreurs);
				$("#form_question").html('<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." />Récupération des Questions...</p>');
				$("#button_nextQuestion").hide();
				etapeAssistant++;
			}else if(etapeAssistant > 0){
				alert("Questions finies!");
			}else{
				alert("[button_nextQuestion.click] Erreur d'exécution!");
			}
		});

		$("#form_question").on("click", "button", function(){
			/*
			Cette fonction s'attache aux boutons créés dynamiquement durant la fonction "afficherQuestions"
			*/
			var strFonction = $(this).data("fonction").split('(');
			strFonction[1] = (strFonction[1].substring(0, strFonction[1].length-1)).split(',');
			var fn  = window[strFonction[0]];
			if(typeof fn === 'function') {
				fn.apply($(this), strFonction[1]); // Note : les fonctions appellées DOIVENT être en-dehors de $(document).ready
			}else{
				console.log("fn n'est pas une fonction");
			}
		});

		function traiterErreurs(msgErreur){
			/*
			Voir appels à "execXHR_Request",
			Sert à traiter l'erreur recue.
			Pour le moment l'erreur la plus commune devrais être "usager team_codeH inexistant"
			ce qui veux dire que l'on doit créer l'usager pour accèder à la BD (ne pas mélanger avec la table "usagers"
			parce que ce n'est pas du tout la même chose), voir le fichier db_access.inc.php pour le mot de passe
			*/
			alert("L'erreur suivante est survenue : '"+msgErreur+"'");
		}

		function afficherQuestions(donnees){
			/*
				Traite le retour de execXHR_Request("../assets/xhr/creationProjet.xhr.php", XHR_Query, afficherQuestions, traiterErreurs);
				Quand rencontre "text" crée une balise input::text et pour select, un select::option
			*/
			var contenu = null;
			var contenuDataList = '';

			donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
			$("#form_question").html('');
			for(var iCmpt_lignes=0; iCmpt_lignes<donnees.length; iCmpt_lignes++){
				contenuDataList = '';
				contenu = '<div><label for="questions'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['texte']+'</label>';
				if(donnees[iCmpt_lignes]['type_input'] == "text"){
					contenu += '<input type="text" name="questions[]" id="question'+iCmpt_lignes;
					if (donnees[iCmpt_lignes]['valeurs_defaut'] != null){
						donnees[iCmpt_lignes]['valeurs_defaut'] = donnees[iCmpt_lignes]['valeurs_defaut'] .split('¤');
						if(donnees[iCmpt_lignes]['valeurs_defaut'][0].length>0){
							contenu += '" placeholder="'+donnees[iCmpt_lignes]['valeurs_defaut'][0];
						}

						if(donnees[iCmpt_lignes]['valeurs_defaut'].length>1){
							contenu += '" list = "datalist_question'+iCmpt_lignes;
							for(var iCmpt_Options=1;iCmpt_Options<donnees[iCmpt_lignes]['valeurs_defaut'].length;iCmpt_Options++){
								contenuDataList += '<option>'+donnees[iCmpt_lignes]['valeurs_defaut'][iCmpt_Options]+'</option>';
							}
						}
					}
					contenu += '" />';
					if(contenuDataList.length != ''){
						contenu += '<datalist id="datalist_question'+iCmpt_lignes+'">'+contenuDataList+'</datalist>';
					}
				}else if(donnees[iCmpt_lignes]['type_input'] == "select"){
					contenu += '<select name="questions[]" id="question'+iCmpt_lignes+'">';
					donnees[iCmpt_lignes]['valeurs_defaut'] = donnees[iCmpt_lignes]['valeurs_defaut'] .split('¤');
					for(var iCmpt_Options=0;iCmpt_Options<donnees[iCmpt_lignes]['valeurs_defaut'].length;iCmpt_Options++){
						contenu += '<option>'+donnees[iCmpt_lignes]['valeurs_defaut'][iCmpt_Options]+'</option>';
					}
					contenu += '</select>';
				}
				if(donnees[iCmpt_lignes]['bouton_fonction']!=null){
					donnees[iCmpt_lignes]['bouton_fonction'] = donnees[iCmpt_lignes]['bouton_fonction'].split('¤');
					contenu += '<button type="button" class="bouton_question" data-fonction="'+donnees[iCmpt_lignes]['bouton_fonction'][0]+'" data-question="'+iCmpt_lignes+'">'+donnees[iCmpt_lignes]['bouton_fonction'][1]+'</button>';
				}
				contenu += "<span>"+(iCmpt_lignes+1)+"/"+donnees.length+"</span></div>";
				$("#form_question").append(contenu);
			}
			$("#button_nextQuestion").show();
		}

		function afficherGenres(donnees){
			/*
			Affiche un "select" lequel permet de sélectionner le Genre Littéraire
			*/
			var iCmpt = 0;

			donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);

			$("#select_question").html('');
			var sSelections = '';
			for(iCmpt=0;iCmpt<donnees.length;iCmpt++){
				sSelections += '<option value="'+donnees[iCmpt]+'">'+donnees[iCmpt]+'</option>';
			}
			$("#form_question>p").hide();
			$("#select_question").html(sSelections);
			$("#label_question").attr("for", "select_question");
			$("#label_question").text("Sélectionnez un genre littéraire : ");
			$("#label_question").show();
			$("#select_question").show();
			$("#button_nextQuestion").show();
		}

		$("#label_question").hide();
		$("#select_question").hide();
		$("#button_nextQuestion").hide();
		execXHR_Request("../assets/xhr/creationProjet.xhr.php", "etape=lireGenres", afficherGenres, traiterErreurs);
	});

function generer_nom(min_chars, max_chars, word_count){
	var nombre; 
	var nom_generer='';
	var iCmpt_Mots;
	var iCmpt_Lettres;

	min_chars = (min_chars-1 < 1)?1:min_chars-1;
	max_chars = (max_chars-1 < 1)?1:max_chars-1;
	max_chars = (max_chars >20)?1:max_chars;
	word_count = (word_count < 1)?1:word_count;
	word_count = (word_count > 5)?5:word_count;
	if(max_chars < min_chars){
		var tmp=max_chars;
		max_chars = min_chars;
		min_chars = tmp;
	}
	for(iCmpt_Mots=0;iCmpt_Mots<word_count;iCmpt_Mots++){
		if(iCmpt_Mots>0) nom_generer += " ";
		nom_generer += String.fromCharCode(Math.floor(Math.random() * (90 - 65) + 65)); // A-Z
		nombre = Math.floor(Math.random() * (max_chars - min_chars) + min_chars);
		for(iCmpt_Lettres=1;iCmpt_Lettres<nombre;iCmpt_Lettres++){
			nom_generer += String.fromCharCode(Math.floor(Math.random() * (122 - 97) + 97)); // a-z
		}
	}
	$("#question"+this.context.dataset.question).val(nom_generer);
}
</script>

<form id="form_question" method="post" action="index.php">
	<label id="label_question"></label>
	<select id="select_question"></select>
	<p><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." />Récupération des Genres Littéraires...</p>
</form>
<button form="form_question" type="button" id="button_nextQuestion">Suivant</button>
<?php
include "../assets/inc/footer.inc.php"
?>
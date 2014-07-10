"use strict";

/**********************
	CONFIGURATION / VARIABLES GLOBALES
**********************/
var gbl_DelaiSauvegarde_TextePrincipal = null; // La variable intervalle utilisée lorsqu'on tape
var iFrequenceSauvegarde_TextePrincipal = 7000; // Le delai a attendre depuis la dernière frappe dans le texte principal ou les notes avant de lancer la sauvegarde
var iDelaiOcculterMessageSauvegarde = 5000; // Laps de temps durant lequel le message confirmant la sauvegarde doit rester à l'écran
var balise_MainText = "main_write";
var balises_entites_base = "edition-boite-entites";
var gblRoman;
var gblEntites = []; //new Array();
var gblEntiteEnCoursEdition = -1; // si -1 alors aucune entitée en édition
var ongletCompositionCourant = 'textePrincipal';

/*
	Fonction globale
*/
function construireCodeEntite(curIndex){
	/*
		Normalement cette fonction recoit 1 paramêtre qui doit être un array contenant les détails de l'entité pour laquelle les balises doivent être construites
	*/
	var donnees;
	var contenu = '';
	var editable = '';

	if(arguments[1] === undefined){
		donnees = {'titre':"", 'contenu':"", 'note':""};
		curIndex = 0;
		editable = ' contenteditable="true"';
	}else{
		donnees = arguments[1];
	}

	// Toolbar
	contenu += '<div class="aide-memoire" data-idself="'+curIndex+'">';

	// Contenu
	contenu += '	<div class="aide-memoire-headings"><span'+editable+' placeholder="Taper un titre ici">'+donnees['titre']+'</span><span class="glyphicon glyphicon-pencil"></span><span class="glyphicon glyphicon-trash"></span></div>';
	contenu += '	<div class="aide-memoire-content">';
	contenu += '		<span'+editable+' placeholder="Entrer la description ici">'+donnees['contenu']+'</span>';
	contenu += '	</div>';
	contenu += '	<div class="aide-memoire-notes">';
	contenu += '		<span'+editable+' placeholder="Laisser une note">';
	if(donnees['note'] !== null){ contenu += donnees['note']; }
	contenu += '</span>';
	contenu += '	</div>';
	contenu += '	<div class="aide-memoire-boutons-edition">';
	contenu += '		<button type="button" class="btn btn-success" data-btntype="save"><span class="glyphicon glyphicon-ok"></span></button>';
	contenu += '		<button type="button" class="btn btn-danger" data-btntype="cancel"><span class="glyphicon glyphicon-remove"></span></button>';
	contenu += '	</div>';
	contenu += "</div>\n\n";

	return contenu;
}


/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
	(C'est à dire, quand la requête XHR s'est complètée correctement. Ici on réagit selon le
	type de la requête, que ce soit par un message de confirmation ou la manipulation des
	données de retour.)
**********************/
function insererEntiteRetour(donnees){
	var typeEntite = gblEntites['temp']['typeEntite'];
	var contenu;

	gblEntites[typeEntite][donnees] = {'ID_prev':gblEntites[typeEntite][0]['last'], 'ID_next':'0','titre':gblEntites['temp']['titre'], 'contenu':gblEntites['temp']['contenu'], 'note':gblEntites['temp']['note']};
	if(gblEntites[typeEntite][0]['last'] > 0){
		gblEntites[typeEntite][gblEntites[typeEntite][0]['last']]['ID_next'] = donnees;
	}
	gblEntites[typeEntite][0]['last'] = donnees;
	if(gblEntites[typeEntite][0]['first'] == 0){
		gblEntites[typeEntite][0]['first'] = donnees;
	}

	//$("#"+balises_entites_base+">div>div:last-child").data("idself", donnees);
	$("#"+balises_entites_base+">div").find("div[data-idself='0']").remove();
	//construireCodeEntite(donnees, false);
	contenu = construireCodeEntite(donnees, gblEntites[typeEntite][donnees]);
	$("#"+balises_entites_base+">div").append(contenu);
	$("#"+balises_entites_base+">div").find(".aide-memoire-boutons-edition").hide();

	//console.log(gblEntites[typeEntite]);
	//console.log(gblEntites['temp']);
	$("#"+balises_entites_base+">div").find("span[contenteditable='true']").removeAttr("contenteditable");
	$("#"+balises_entites_base+">div").find("div[data-idself='9999']").remove();
	gblEntiteEnCoursEdition = -1;
}

function MaJ_EntiteRetour(donnees){
	var typeEntite = gblEntites['temp']['typeEntite'];

	//console.log("[MaJ_EntiteRetour] Retour = ' "+donnees+" '");
	gblEntites[typeEntite][gblEntiteEnCoursEdition]['titre'] = gblEntites['temp']['titre'];
	gblEntites[typeEntite][gblEntiteEnCoursEdition]['contenu'] = gblEntites['temp']['contenu'];
	gblEntites[typeEntite][gblEntiteEnCoursEdition]['note'] = gblEntites['temp']['note'];
	//console.log(gblEntites[typeEntite]);
	//console.log(gblEntites['temp']);

	$("#"+balises_entites_base+">div").find("span[contenteditable='true']").removeAttr("contenteditable");
	gblEntiteEnCoursEdition = -1;
}

function effacerEntiteRetour(donnees){
	var typeEntite = gblEntites['temp']['typeEntite'];
	var ID_next;
	var ID_prev;
	var contenu = '';
	//console.log("[effacerEntiteRetour] Retour = ' "+donnees+" '");

	ID_next = gblEntites[typeEntite][gblEntiteEnCoursEdition]['ID_next'];
	ID_prev = gblEntites[typeEntite][gblEntiteEnCoursEdition]['ID_prev'];

	//console.log("typeEntite = "+typeEntite+" / ID_prev = " + ID_prev + " / ID_next = " + ID_next);

	if(ID_prev > 0) { gblEntites[typeEntite][ID_prev]['ID_next'] = ID_next; }
	if(ID_next > 0) { gblEntites[typeEntite][ID_next]['ID_prev'] = ID_prev; }

	if(ID_prev == 0) { gblEntites[typeEntite][0]['first'] = ID_next; }
	if(ID_next == 0) { gblEntites[typeEntite][0]['last'] = ID_prev; }

	$("#"+balises_entites_base+">div").find("div[data-idself='"+gblEntiteEnCoursEdition+"']").remove();

	// Mettre une case "vide"
	if(gblEntites[typeEntite][0]['first'] == 0){
		contenu += '<div class="aide-memoire" ';
		contenu += 'data-idself="9999">';
		contenu += '	<div class="aide-memoire-headings"><span>Aucune entitées pour ce type.</span></div>';
		contenu += "</div>\n\n";
		//donnees[0]['first'] = 0;
		//donnees[0]['last'] = 0;
		$("#"+balises_entites_base+">div").html(contenu);
	}

	delete gblEntites[typeEntite][gblEntiteEnCoursEdition];  // parce que c'est un objet !

	gblEntiteEnCoursEdition = -1;
}

function afficherEntites(donnees){
	/*
		Affiche les entitées contenues dans "donnees"

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"

		donnees : les donnees à traiter
		s'il y as un second parametre : indique qu'il ne faut pas pré-traiter les données avec JSON
	*/
	//console.log(donnees);
	var contenu='';
	var curIndex; // = donnees[0]['first'];
	var entiteOnglet = $("#"+balises_entites_base).find("ul .active").text();

	//	Préparer les données
	if(arguments[1] === undefined){
		donnees = JSON.parse(donnees);
		gblEntites[donnees[0]['typeEntite']] = donnees;
		//console.log("[afficherEntites] j'ai chargé les entites");
	}
	
	curIndex = donnees[0]['first'];
	contenu += '<div class="aide-memoire-toolbar"><span class="toolbar-title">'+entiteOnglet+'</span><span class="glyphicon glyphicon-plus"></span></div>';

	if((curIndex !== null) && (curIndex !== 0)){
		// 	Créer l'interface dans le parent donnees[0]['target']
		do{
			contenu += construireCodeEntite(curIndex, donnees[curIndex]);
			//console.log(curIndex);
			curIndex = donnees[curIndex]['ID_next'];
		}while(curIndex != 0);
	}else{
		//console.log("[afficherEntites] Aucune entitée de ce type attachée à ce Roman!");
		//console.log(donnees);
		//console.log(gblEntites);
		contenu += '<div class="aide-memoire" ';
		contenu += 'data-idself="9999">';
		contenu += '	<div class="aide-memoire-headings"><span>Aucune entitées pour ce type.</span></div>';
		contenu += "</div>\n\n";
		donnees[0]['first'] = 0;
		donnees[0]['last'] = 0;
	}
	$("#"+balises_entites_base+">div").html(contenu);
	$("#"+balises_entites_base+">div").find(".aide-memoire-boutons-edition").hide();
}

function afficherRoman(donnees){
	gblRoman = JSON.parse(donnees);
	//console.log(gblRoman);
	if(gblRoman.length !== 0){
		$("#"+balise_MainText).text(gblRoman['contenu']);
		$("h2").text(gblRoman['titre']);
	}else{
		$("#"+balise_MainText).text("[Cet usager n'as aucun Roman ou il y as eût une erreur de BD.]");
	}
	$("#"+balise_MainText).show();
	$("#"+balise_MainText+"~p").remove();
}

function sauvegarderTextePrincipal(id_balise){
	/*
		Si le texte Principal as été modifié, alors il est sauvegardé.
		L'état de $("#"+balise_MainText).data("dirtyBit") en décide
	*/
	if(ongletCompositionCourant === "textePrincipal"){
		gblRoman['contenu'] = $("#"+id_balise).val();
	}else{
		gblRoman['notes_globales'] = $("#"+id_balise).val();
	}

	sauvegarderTexte(lancerDelaiSauvegardeTextePrincipal, traiterErreurs, idRoman, gblRoman['contenu'], gblRoman['notes_globales']);
	//console.log($("#"+id_balise).val());
}

function lancerDelaiSauvegardeTextePrincipal(msgRetour){
	/*
		Lance un nouveau setTimeout à la fin duquel une tentative de sauvegarde sera faite
	*/
	//console.log(msgRetour);
	var dDate = new Date();//"1/1/1 3:3:3");
	var sJour = dDate.getDate();
	var sMois = dDate.getMonth() + 1;
	var sHeures = dDate.getHours();
	var sMinutes = dDate.getMinutes() ;
	var sSecondes = dDate.getSeconds();
	var sDate;

	if(sJour < 10){ sJour = '0'+sJour; }
	if(sMois < 10){ sMois = '0'+sMois; }
	if(sHeures < 10){ sHeures = '0'+sHeures; }
	if(sMinutes < 10){ sMinutes = '0'+sMinutes; }
	if(sSecondes < 10){ sSecondes = '0'+sSecondes; }

	sDate = sHeures + ":" + sMinutes + ":" + sSecondes + " " + sJour + "/" + sMois + "/" + dDate.getFullYear();
	$("#msg_confirm_save").text("Texte sauvegardé @ " + sDate).fadeIn('medium').delay(iDelaiOcculterMessageSauvegarde).fadeOut('slow');
}


/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS NÉGATIFS
**********************/
function traiterErreurs(msgErreur){
	/*
	Voir appels à "execXHR_Request",
	Sert à traiter l'erreur recue.
	Pour le moment l'erreur la plus commune devrais être "usager team_codeH inexistant"
	ce qui veux dire que l'on doit créer l'usager pour accèder à la BD (ne pas mélanger avec la table "usagers"
	parce que ce n'est pas du tout la même chose), voir le fichier db_access.inc.php pour le mot de passe
	*/

	if(msgErreur.substring(0,6) === "<br />"){ // Si commence par '<br />', on suppose que c'est une erreur PHP!
		msgErreur = "[PHP] " + strStripHTML(msgErreur);
	}

	alert("L'erreur suivante est survenue : '"+msgErreur+"'");
}


/**********************
	WRAPPERS
**********************/
function lireEntites(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite){
	var XHR_Query = "oper=lire&typeEntite="+typeEntite+"&idRoman="+idRoman;

	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function modifierEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, titre, contenu, noteEntite, idEntite){
	var XHR_Query;

	titre = encodeURIComponent (titre);
	contenu = encodeURIComponent (contenu);
	noteEntite = encodeURIComponent (noteEntite);
	XHR_Query = "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite+"&idEntite="+idEntite;
	//console.log(XHR_Query);
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function insererEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, titre, contenu, noteEntite){
	var XHR_Query;

	titre = encodeURIComponent (titre);
	contenu = encodeURIComponent (contenu);
	noteEntite = encodeURIComponent (noteEntite);
	XHR_Query = "oper=inserer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function effacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, idEntite){
	var etatDeleted = (arguments[4] !== undefined)?arguments[4]:1; // argument optionel
	var XHR_Query = "oper=effacer&idRoman="+idRoman+"&idEntite="+idEntite+"&etat="+etatDeleted;
	//console.log(XHR_Query);
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function chargerRoman(fctTraitementPositif, fctTraitementNegatif, idRoman){
	var XHR_Query = "oper=lire&typeEntite=textePrincipal&idRoman="+idRoman;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function sauvegarderTexte(fctTraitementPositif, fctTraitementNegatif, idRoman, nouveauTexte, nouvelleNote){
	var XHR_Query;

	nouveauTexte = encodeURIComponent (nouveauTexte);
	nouvelleNote = encodeURIComponent (nouvelleNote);
	XHR_Query = "oper=ecrire&typeEntite=textePrincipal&idRoman="+idRoman+"&contenu="+nouveauTexte+"&notes="+nouvelleNote;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}


/*********************
	FONCTIONS GLOBALES
	=N/A=
*********************/
function TraiterClickOnglets_Entites(ceci){
	/*
		Pour les onglets des entitées, s'occupe de relier l'event click au code XHR
	*/
	var typeEntite;
	var posSpace;
	var bProceder = true; // si est TRUE, continuer avec le changement d'onglet
	//console.log('click');
	if(!ceci.hasClass("active") && !ceci.hasClass("dropdown")){
		if(gblEntiteEnCoursEdition !== -1){
			bProceder = confirm("Attention!\n\nUne entitée est en cours d'édition, vous risquez de perdre des données!\n\nContinuer?");
			//console.log("bProceder = "+bProceder);
		}
		if(bProceder){
			gblEntiteEnCoursEdition = -1; // on force à "aucune entitée en mode édition"

			ceci.parents("#"+balises_entites_base+">ul").find(".active").removeClass("active");
			ceci.addClass("active");
			typeEntite = ceci.text();
			posSpace = typeEntite.indexOf(" ");
			if(posSpace > 0){
				typeEntite = typeEntite.substring(0, posSpace);
			}
			//console.log(typeEntite);

			typeEntite = typeEntite.toLowerCase();
			typeEntite = typeEntite.replace('ù', 'u');
			typeEntite = typeEntite.replace('é', 'e');
			if(typeEntite === "delit") { return; }// Si c'est le bouton "Délit", ignorer l'évènement
			if(gblEntites[typeEntite] !== undefined){
				console.log("afficher entites seulement");
				afficherEntites(gblEntites[typeEntite], false);
			}else{
				//console.log(typeEntite + " est vide");
				lireEntites(afficherEntites, traiterErreurs, idRoman, typeEntite);
			}
		}
	}
}


/**********************
	EVENT HANDLERS
**********************/
$(function () {
	// Handlers pour le textArea contenant le textePrincipal
	$("#"+balise_MainText).keyup(function(){
		clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout(function () { sauvegarderTextePrincipal(balise_MainText); }, iFrequenceSauvegarde_TextePrincipal);
	});

	$("#edition-boite-textePrincipal>ul>li").click(function(){
		if (!$(this).hasClass("active")){
			$(this).parent().children(".active").removeClass("active");
			$(this).addClass("active");

			//console.log(gblRoman['contenu']);
			//console.log(gblRoman['notes_globales']);

			if($(this).text() === "Composition"){
				gblRoman['notes_globales'] = $("#"+balise_MainText).val();
				//console.log(gblRoman['notes_globales']);
				ongletCompositionCourant = "textePrincipal";
				$("#"+balise_MainText).val(gblRoman['contenu']);
			}else{
				gblRoman['contenu'] = $("#"+balise_MainText).val();
				ongletCompositionCourant = "notesGenerales";
				$("#"+balise_MainText).val(gblRoman['notes_globales']);
			}
		}
	});

	$("#"+balises_entites_base+">ul>li").click(function(){
		TraiterClickOnglets_Entites($(this));
	});

	$("#"+balises_entites_base+">ul>li>ul>li").click(function(){
		TraiterClickOnglets_Entites($(this));
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-boutons-edition button', function(){
		/*
			Boutons "sauvegarder"/"annuler" des conteneurs "entitées"
		*/
		var idEntite = $(this).parents('.aide-memoire').data('idself');
		var spanChilds;
		var typeEntite;
		var iCmpt;

		//console.log($(this).data("btntype"));
		//console.log(idEntite);
		//return;

		if($(this).data("btntype") == "cancel"){ // BOUTON "ANNULER"
			if(idEntite == 0){
				// Si le bouton appartient à une entité dont le idself = 0, donc c'est une nouvelle entitée non-enregistrée
				//console.log("idself=0");
				$(this).parents('.aide-memoire').remove();
			}else{
				// annuler changements
				//console.log("annuler changements");
				typeEntite = $("#"+balises_entites_base+">ul .active a").text();
				typeEntite = typeEntite.toLowerCase();
				typeEntite = typeEntite.replace('ù', 'u');
				typeEntite = typeEntite.replace('é', 'e');

				$(this).parents('.aide-memoire').find("span[contenteditable='true']").removeAttr("contenteditable");
				spanChilds = $(this).parents('.aide-memoire').find("span");
				//console.log(spanChilds);
				//console.log(gblEntites[typeEntite][idEntite]);
				// Restaurer les valeurs selon la mémoire
				for(iCmpt=0;iCmpt<spanChilds.length;iCmpt++){
					switch(iCmpt){
						case 0: spanChilds[0].innerHTML = gblEntites[typeEntite][idEntite]['titre']; break;
						case 1: spanChilds[1].innerHTML = gblEntites[typeEntite][idEntite]['contenu']; break;
						case 2: spanChilds[2].innerHTML = gblEntites[typeEntite][idEntite]['note']; break;
					}
				}
			}
			gblEntiteEnCoursEdition = -1;
		}else{ // BOUTON "SAUVEGARDER"
			console.log($(this).data("btntype"));
			console.log(idEntite);
			//return;

			typeEntite = $("#"+balises_entites_base+">ul .active a").text();
			typeEntite = typeEntite.toLowerCase();
			typeEntite = typeEntite.replace('ù', 'u');
			typeEntite = typeEntite.replace('é', 'e');
			spanChilds = $(this).parents('.aide-memoire').find("span[contenteditable='true']");

			gblEntites['temp'] = new Array();
			gblEntites['temp']['titre'] = spanChilds[0].innerHTML;
			gblEntites['temp']['contenu'] = spanChilds[1].innerHTML;
			gblEntites['temp']['note'] = spanChilds[2].innerHTML;
			gblEntites['temp']['typeEntite'] = typeEntite;

			//console.log(gblEntites['temp']);
			//console.log(typeEntite);

			if(idEntite == 0){
				// si idself=0 alors insérer
				insererEntite(insererEntiteRetour, traiterErreurs, idRoman, typeEntite, gblEntites['temp']['titre'], gblEntites['temp']['contenu'], gblEntites['temp']['note']);
			}else{
				// sinon mise à jour
				//console.log(idEntite);
				modifierEntite(MaJ_EntiteRetour, traiterErreurs, idRoman, typeEntite, gblEntites['temp']['titre'], gblEntites['temp']['contenu'], gblEntites['temp']['note'], idEntite);
				//console.log(idEntite);
			}
		}
		$(this).parents(".aide-memoire").find(".aide-memoire-boutons-edition").hide();
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-toolbar .glyphicon-list', function(){
		console.log("click -- dragNdrop");
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-toolbar .glyphicon-pencil', function(){
		/*
			Ajout d'une entitée
		*/
		//console.log("click -- ajouter");
		var contenu = '';
		// Vérifie s'il y as ou non une entitée en édition ou si c'est une nouvelle entitée
		if(gblEntiteEnCoursEdition == -1){
			//console.log("je crée un enfant!");
			contenu = construireCodeEntite(0);
			$("#"+balises_entites_base+">div").append(contenu);
			gblEntiteEnCoursEdition = 0;
		}else if(gblEntiteEnCoursEdition == 0){
			alert("Erreur!\n\nUne nouvelle entitée est déjà en mode édition!");
			//console.log("nouvel enfant déjà en édition!");
		}else{
			alert("Erreur!\n\nUne entitée est déjà en mode édition");
			//console.log("Un enfant déjà en édition!");
		}
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-headings>span:nth-of-type(2)', function(){
		/*
			Éditer l'entitée
		*/
		//console.log("click -- editer");
		console.log("gblEntiteEnCoursEdition  = " + gblEntiteEnCoursEdition + " // $(this).parents('.aide-memoire').data('idself') = " + $(this).parents('.aide-memoire').data('idself'));
		if(gblEntiteEnCoursEdition == $(this).parents('.aide-memoire').data('idself')){
			//console.log("CET enfant est déjà en mode édition!");
			alert("Erreur!\n\nCette entitée est déjà en mode édition!");
		}else if(gblEntiteEnCoursEdition > -1){
			//console.log("un enfant est déjà en mode édition!");
			alert("Erreur!\n\nUne entitée est déjà en mode édition!");
		}else{
			$(this).parents(".aide-memoire").find(".aide-memoire-headings span:first-child").attr("contenteditable", "true");
			$(this).parents(".aide-memoire").find(".aide-memoire-content span").attr("contenteditable", "true");
			$(this).parents(".aide-memoire").find(".aide-memoire-notes span").attr("contenteditable", "true");
			$(this).parents(".aide-memoire").find(".aide-memoire-boutons-edition").show();
			gblEntiteEnCoursEdition = $(this).parents(".aide-memoire").data("idself");
		}
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-headings>span:last-of-type', function(){
		/*
			Effacer l'entitée
		*/
		var typeEntite;
		var etatDeleted = true;
		var bProceder = false;

		if(gblEntiteEnCoursEdition == -1){
			bProceder = confirm("Attention!\n\nVous êtes sur le point d'effacer cette entitée!\n\nContinuer?");
			//console.log("bProceder = "+bProceder);

			if(bProceder){
				gblEntiteEnCoursEdition = $(this).parents(".aide-memoire").data("idself");
				//console.log("click -- effacer (" + gblEntiteEnCoursEdition +")");
				typeEntite = $("#"+balises_entites_base+">ul .active a").text();
				typeEntite = typeEntite.toLowerCase();
				typeEntite = typeEntite.replace('ù', 'u');
				typeEntite = typeEntite.replace('é', 'e');
				gblEntites['temp'] = new Array();
				gblEntites['temp']['typeEntite'] = typeEntite;

				effacerEntite(effacerEntiteRetour, traiterErreurs, idRoman, gblEntiteEnCoursEdition, etatDeleted);
			}
		}else if(gblEntiteEnCoursEdition == $(this).parents('.aide-memoire').data('idself')){
			alert("Erreur!\n\nVeuillez annuler l'édition de cette entitée avant de lancer une autre opération!");
		}else{
			alert("Erreur!\n\nVeuillez terminer l'édition de l'entitée en cours avant de lancer une autre opération!");
		}
	});

	/*
		Bloc principal, si idRoman est bien initialisé alors charger le Roman correspondant avec ses entitées
	*/
	if(idRoman > 0){
		$("#"+balise_MainText).hide();
		chargerRoman(afficherRoman, traiterErreurs, idRoman);
		lireEntites(afficherEntites, traiterErreurs, idRoman, "qui");
	}else{
		console.log(baseURL+"index.php");
		window.location.replace(baseURL+"index.php");
	}
});

/* == EOF == */

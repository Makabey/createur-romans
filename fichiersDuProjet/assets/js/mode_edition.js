"use strict";
/*
	Le texte principal est lu au chargement de la page puis à chaque "iFrequenceSauvegarde_TextePrincipal" millisecondes, si $("#"+balise_MainText).data("dirtyBit") === true, la sauvegarde du texte principale est faite

	-Aussi- si l'usager clique le bouton "sauvegarder", le timeout est tué si $("#"+balise_MainText).data("dirtyBit") === true et la sauvegarde est forcée (pour les tests c'est 5 secs, je sais que c'est inutile/court, je pensais de 60 à 300 secondes une fois "en ligne")

===chronologie des fonctions:
	-chargerRoman
	-attente du retour XHR
	-appel à "afficherRoman"
	-setTimeout de "iFrequenceSauvegarde_TextePrincipal" millisecondes pour appel à sauvegarderTextePrincipal
	-si on as tapé dans le textarea contenant le document, $("#"+balise_MainText).data("dirtyBit") === true, la sauvegarde du texte principale est faite, attente XHR
	-xhrFunctions.js::execXHR_Request ( sauvegarde) => appel à lancerDelaiSauvegardeTextePrincipal
*/

/**********************
	CONFIGURATION / VARIABLES GLOBALES
**********************/
var gbl_DelaiSauvegarde_TextePrincipal = null; // SI on voulais arrêter le cycle d'enregistrement, il suffirais d'utiliser clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
var iFrequenceSauvegarde_TextePrincipal = 5000; // 5 secs, mériterais probablement une valeur entre 60 et 300 secs
var balise_MainText = "main_write";
//var balises_entites_base = "contenantEntites";
//var balises_entites_base = "edition-boite-entites>div";
var balises_entites_base = "edition-boite-entites";
var gblRoman;
var gblEntites = new Array();

/**********************
	EVENT HANDLERS
**********************/
$(function(){
	// Handlers pour le textArea contenant le textePrincipal
	$("#"+balise_MainText).data("dirtyBit", false);
	$("#"+balise_MainText).keyup(function(){
		$("#"+balise_MainText).data("dirtyBit", true);
	});

	$(".col-md-8>ul>li").click(function(){
		if(!$(this).hasClass("active")){
			$(this).parent().children(".active").removeClass("active");
			$(this).addClass("active");

			//console.log(gblRoman['contenu']);
			//console.log(gblRoman['notes_globales']);

			if($(this).text() == "Composition"){
				if($("#"+balise_MainText).data("dirtyBit") === true){
					gblRoman['notes_globales'] = $("#"+balise_MainText).val();
					//console.log(gblRoman['notes_globales']);
					$("#"+balise_MainText).data("dirtyBit", false);
				}
				$("#"+balise_MainText).val(gblRoman['contenu']);
			}else{
				if($("#"+balise_MainText).data("dirtyBit") === true){
					gblRoman['contenu'] = $("#"+balise_MainText).val();
					//console.log(gblRoman['contenu']);
					$("#"+balise_MainText).data("dirtyBit", false);
				}
				$("#"+balise_MainText).val(gblRoman['notes_globales']);
			}
		}
	});

	$("#"+balises_entites_base+">ul>li").click(function(){
		TraiterClickOnglets($(this));
	});

	$("#"+balises_entites_base+">ul>li>ul>li").click(function(){
		TraiterClickOnglets($(this));
	});

	//$(""#"+balises_entites_base .aide-memoire-toolbar>img:first-of-type").click(function(){
	//$("#"+balises_entites_base).on('click', '.aide-memoire-toolbar>img:first-of-type', function(){
	$("#"+balises_entites_base).on('click', '.aide-memoire-toolbar .glyphicon-pencil', function(){
		console.log("click -- dragNdrop");
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-toolbar .glyphicon-list', function(){
		console.log("click -- ajouter");
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-headings>img:first-of-type', function(){
		console.log("click -- editer");
	});

	$("#"+balises_entites_base).on('click', '.aide-memoire-headings>img:nth-of-type(2)', function(){
		var idSelf = $(this).parents(".aide-memoire").data("idself");
		console.log("click -- effacer (" + idSelf +")");
	});

	function TraiterClickOnglets(ceci){
		/*
			Pour les onglets des entitées, s'occupe de relier l'event click au code XHR
		*/
		var typeEntite;
		var posSpace;
		//console.log('click');
		if(!ceci.hasClass("active") && !ceci.hasClass("dropdown")){
			//ceci.parents(".col-md-4 ul").find(".active").removeClass("active");
			//ceci.parents("#maincontent>div:nth-child(2)>ul").find(".active").removeClass("active");
			ceci.parents("#"+balises_entites_base+">ul").find(".active").removeClass("active");
			ceci.addClass("active");
			typeEntite = ceci.text();
			posSpace = typeEntite.indexOf(" ");
			if(posSpace > 0){
				typeEntite = typeEntite.substring(0, posSpace);
			}
			console.log(typeEntite);
			//return;
			typeEntite = typeEntite.toLowerCase();
			typeEntite = typeEntite.replace('ù', 'u');
			typeEntite = typeEntite.replace('é', 'e');
			if(typeEntite == "delit") {
				// Si c'est le bouton "Délit", ignorer l'évènement
				return;
			}
			//if(gblEntites[typeEntite].length > 0){
			if(gblEntites[typeEntite] !== undefined){
				/*if(gblEntites[typeEntite][0]['first'] > 0){
					console.log(typeEntite + " as au moins 1 membre");
				}else{
					console.log(typeEntite + " as déjà été lu mais est vide!");
				}*/
				afficherEntites(gblEntites[typeEntite], false);
			}else{
				//console.log(typeEntite + " est vide");
				lireEntites(afficherEntites, traiterErreurs, idRoman, typeEntite);
			}
		//}else{
		//	console.log("L'onglet est deja active");
		}
	}

	/*$("#"+balises_entites_base).on('dblclick', 'div.aide-memoire', function(){
		console.log("aide-memoire :: click! ("+$(this).data("idself")+")");
		if($(this).data("idself") !== 0){
			$(this).find("span").attr("contenteditable", "true");
		}else{
			alert("Vous ne pouvez pas éditer cette entitée.");
		}
	});
	$("#"+balises_entites_base).on('blur', 'div.aide-memoire', function(){
		console.log("[aide-memoire] OnBlur!!");
		// comme l'event se déclenche même quand je clique un enfant, je dois trouver une autre solution ou comprendre comment comparer disons "target" avec les enfants et si c'en est pas un alors enlever les attr editable. Le fait que on veux mettre un bouton à mon sens ne change rien au fait que si on clique ailleurs, on devrait considérer l'édition finie!
	});*/

	/*$("#btn_save").click(function(){
		/ *
			Permet de forcer la sauvegarde du texte Principal -SI- le contenu as été modifié
		* /
		if($("#"+balise_MainText).data("dirtyBit") === true){
			clearTimeout(gbl_DelaiSauvegarde_TextePrincipal);
			sauvegarderTextePrincipal(balise_MainText);
			console.log("btn_save / DirtyBit :: True");
		}else{
			console.log("btn_save / DirtyBit :: False");
		}
	});*/

	/*$("#btn_lireEntites").click(function(){
		/ *
			Charger les entitees de type "qui"
		* /
		var typeEntite = "qui";
		var containerEntites = balises_entites_base + typeEntite;
		lireEntites(afficherEntites, traiterErreurs, idRoman, typeEntite, containerEntites);
	});

	$("#btn_saveEntite").click(function(){
		var typeEntite = "pourquoi";
		var contenu = 'blablablablabla';
		var noteEntite="";
		var titre = "test7";
		insererEntite(insererEntiteRetour, traiterErreurs, idRoman, typeEntite, titre, contenu, noteEntite);
	});

	$("#btn_updEntite").click(function(){
		var typeEntite = "comment";
		var idEntite = 19;
		var titre = "updated5";
		var contenu = 'whacka';
		var noteEntite = "hum";
		modifierEntite(MaJ_EntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, titre, contenu, noteEntite);
	});

	$("#btn_moveEntite").click(function(){
		var typeEntite = "qui";
		var nvTypeEntite = "quoi"; // <-- optionnel, ici pour illustrer que c'est supporté, de pouvoir déplacer de type une entitée
		var idEntite = 11;
		var id_prev = 13;
		var id_next = 0;

		deplacerEntite(deplacerEntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, id_prev, id_next, nvTypeEntite);
	});

	$("#btn_deleteEntite").click(function(){
		var typeEntite = "quoi";
		var idEntite = 11;
		var etatDeleted = false;
		effacerEntite(deplacerEntiteRetour, traiterErreurs, idRoman, typeEntite, idEntite, etatDeleted);
	});*/

	if(idRoman > 0){
		$("#balise_MainText").hide();
		chargerRoman(afficherRoman, traiterErreurs, idRoman);
		//désactiver en attendant modifications au CSS pour les boutons >>
		lireEntites(afficherEntites, traiterErreurs, idRoman, "qui");
	}else{
		window.location.replace(baseURL+"index.php");
	}
});


/**********************
	WRAPPERS
**********************/
function lireEntites(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite){//, containerEntites){
	var XHR_Query = "oper=lire&typeEntite="+typeEntite+"&idRoman="+idRoman;//+"&target="+containerEntites;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

/*
function modifierEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite, titre, contenu, noteEntite){
	var XHR_Query = "oper=ecrire&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite+"&idEntite="+idEntite;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function insererEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, titre, contenu, noteEntite){
	var XHR_Query = "oper=inserer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&titre="+titre+"&contenu="+contenu+"&note="+noteEntite;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function deplacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite, id_prev, id_next){
	/ *
		nvTypeEntite : optionnel, si donné déplacera l'entité vers ce nouveau type
	* /
	var nvTypeEntite = (arguments[7])?arguments[7]:null; // dernier parametre, si pas là forcer "null"; facon JS de le faire! :: parametre/argument optionel :: http://www.openjs.com/articles/optional_function_arguments.php
	var XHR_Query ="oper=deplacer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&prev="+id_prev+"&next="+id_next+"&idEntite="+idEntite;

	if(null !== nvTypeEntite){
		XHR_Query += "&nvTypeEntite="+nvTypeEntite
	}

	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function effacerEntite(fctTraitementPositif, fctTraitementNegatif, idRoman, typeEntite, idEntite){
	var etatDeleted = (arguments[5] != undefined)?arguments[5]:1; // argument optionel
	var XHR_Query = "oper=effacer&typeEntite="+typeEntite+"&idRoman="+idRoman+"&idEntite="+idEntite+"&etat="+etatDeleted;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}
*/

function chargerRoman(fctTraitementPositif, fctTraitementNegatif, idRoman){
	var XHR_Query = "oper=lire&typeEntite=textePrincipal&idRoman="+idRoman;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}

function sauvegarderTexte(fctTraitementPositif, fctTraitementNegatif, idRoman, nouveauTexte){
	// pour arguments[4], valeurs attendues soit "textePrincipal" ou "notesGlobales"
	var nouveauTexte = encodeURIComponent (nouveauTexte);
	var entiteASauvegarder = (arguments[4] !== undefined)?arguments[4]:"textePrincipal";
	var XHR_Query = "oper=ecrire&typeEntite="+entiteASauvegarder+"&idRoman="+idRoman+"&contenu="+nouveauTexte;
	execXHR_Request("../assets/xhr/mode_edition.xhr.php", XHR_Query, fctTraitementPositif, fctTraitementNegatif);
}


/*********************
	FONCTIONS GLOBALES
	=N/A=
*********************/


/**********************
	FONCTIONS DE TRAITEMENT DES RETOURS POSITIFS
	(C'est à dire, quand la requête XHR s'est complètée correctement. Ici on réagit selon le
	type de la requête, que ce soit par un message de confirmation ou la manipulation des
	données de retour.)
**********************/
/*
function insererEntiteRetour(donnees){
	console.log("[insererEntiteRetour] Retour = ' "+donnees+" '");
}

function deplacerEntiteRetour(donnees){
	console.log("[deplacerEntiteRetour] Retour = ' "+donnees+" '");
}

function MaJ_EntiteRetour(donnees){
	console.log("[MaJ_EntiteRetour] Retour = ' "+donnees+" '");
}
*/

function afficherEntites(donnees){
	/*
		Affiche les entitées contenues dans "donnees"

		Fait principalement de la génération de balise et de la copie de contenu/propriétés à partir du tableau "donnees"
	*/
	//	Préparer les données
	//if(arguments[1] !== undefined){
	if(arguments[1] === undefined){
		donnees = JSON.parse(donnees); // contraire :: JSON.stringify(array);
		gblEntites[donnees[0]['typeEntite']] = donnees;
		//console.log("[afficherEntites] j'ai chargé les entites");
	}

	//console.log(donnees);
	var contenu='';
	var curIndex = donnees[0]['first'];
	//var typeEntite = donnees[0]['typeEntite'];
	//var baliseParent = "#"+balises_entites_base; //+typeEntite; //donnees[0]['target'];
	var entiteOnglet = $("#"+balises_entites_base).find("ul .active").text();

	//contenu += '<div class="aide-memoire-toolbar"><span class="toolbar-title">'+donnees[0]['typeEntite']+'</span><img src="../assets/images/toolbars/list.png" alt="drag and drop" /><img src="../assets/images/toolbars/pencil_add.png" /></div>';
	contenu += '<div class="aide-memoire-toolbar"><span class="toolbar-title">'+entiteOnglet+'</span><span class="glyphicon glyphicon-pencil"></span><span class="glyphicon glyphicon-list"></span></div>';


	if(curIndex !== null){
		// 	Créer l'interface dans le parent donnees[0]['target']
		do{
			contenu += '<div class="aide-memoire" data-idself="'+curIndex+'">';

			contenu += '	<div class="aide-memoire-headings"><span>'+donnees[curIndex]['titre']+'</span><img src="../assets/images/toolbars/contract2_pencil.png" alt="Éditer cette entitée" /><img src="../assets/images/toolbars/trash_can_add.png" alt="Effacer cette entitée" /></div>';

			contenu += '	<div class="aide-memoire-content">';
			contenu += '		<span>(contenu -&gt;) '+donnees[curIndex]['contenu']+'</span>';
			contenu += '	</div>';
			contenu += '	<div class="aide-memoire-notes">';
			contenu += '		<span>(notes -&gt;) ';
			if(donnees[curIndex]['note'] !== null){
				contenu += donnees[curIndex]['note'];
				}
			contenu += '</span>';
			contenu += '	</div>';
			contenu += '	<div class="aide-memoire-boutons-edition">';
			contenu += '		<button type="button" data-btntype="save"><img src="../assets/images/toolbars/checkmark_pencil.png" alt="Accepter les changements" />Sauvegarder</button>';
			contenu += '		<button type="button" data-btntype="cancel"><img src="../assets/images/toolbars/close_pencil.png" alt="Annuler les changements" />Annuler</button>';
			contenu += '	</div>';
			contenu += "</div>\n\n";

			curIndex = donnees[curIndex]['ID_next'];
		}while(curIndex != 0);

		//$(baliseParent).html(contenu);
		//$("#"+balises_entites_base).html(contenu);
	}else{
		console.log("[afficherEntites] Aucune entitée de ce type attachée à ce Roman!");
		//console.log(donnees);
		//gblEntites[donnees[0]['typeEntite']][0]['first'] = 0;
		//console.log(gblEntites);
		contenu += '<div class="aide-memoire" ';
		contenu += 'data-idself="0">';
		contenu += '	<div class="aide-memoire-headings"><span>Aucune entitées pour ce type.</span></div>';
		contenu += "</div>\n\n";
		//$("#"+balises_entites_base).html(contenu);
	}
	$("#"+balises_entites_base+">div").html(contenu);
}

function afficherRoman(donnees){
	//$("#balise_attendez").hide();
	gblRoman = JSON.parse(donnees); // contraire :: JSON.stringify(array);
	//console.log(gblRoman);
	if(gblRoman.length !== 0){
		$("#"+balise_MainText).text(gblRoman['contenu']);
		$("h2").text(gblRoman['titre']);
		//gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("'+balise_MainText+'")', iFrequenceSauvegarde_TextePrincipal);
	}else{
		$("#"+balise_MainText).text("[Cet usager n'as aucun Roman ou il y as eût une erreur de BD.]");
	}
	$("#"+balise_MainText).show();
	$("#"+balise_MainText+"~p").hide();
}

function sauvegarderTextePrincipal(id_balise){
	/*
		Si le texte Principal as été modifié, alors il est sauvegardé.
		L'état de $("#"+balise_MainText).data("dirtyBit") en décide
	*/

	//$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 255, 0)")?"blue":"yellow");

	if($("#"+balise_MainText).data("dirtyBit") === true){
		sauvegarderTexte(lancerDelaiSauvegardeTextePrincipal, traiterErreurs, idRoman, $("#"+id_balise).val());
		console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: True");
	}else{
		gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("'+balise_MainText+'")', iFrequenceSauvegarde_TextePrincipal);
		console.log("sauvegarderTextePrincipal("+id_balise+") / DirtyBit :: False");
	}
}

function lancerDelaiSauvegardeTextePrincipal(msgRetour){
	/*
		Lance un nouveau setTimeout à la fin duquel une tentative de sauvegarde sera faite
	*/
	$("#"+balise_MainText).data("dirtyBit", false);
	gbl_DelaiSauvegarde_TextePrincipal = setTimeout('sauvegarderTextePrincipal("'+balise_MainText+'")', iFrequenceSauvegarde_TextePrincipal);
	$("#temoin_activite").css("background-color", ($("#temoin_activite").css("background-color") == "rgb(255, 0, 0)")?"green":"red");
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
	alert("L'erreur suivante est survenue : '"+msgErreur+"'");
}

/*
	FONCTIONS NOMMÉES DANS LA BD

	== N/A ==
*/


/* == EOF == */

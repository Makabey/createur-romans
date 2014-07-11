###Fichier d'Eric
####Contient tâches, idées, concepts, questions et "ToDo"

ToDo:
- [ ] a cause de a différence entre les versions de MySQL (ou phpMyAdmin en fait) j'ai changé le type de certains champs ou le défaut, maintenant il manquerait de gèrer manuellement tous les champs dateTime comme ex: date de création d'un roman ou d'inscription d'un usager
- [ ] plus de tests avec les gars
- [ ] plus de tests dans IE, Chrome, FF, tablette
- [ ] plus de tests tablette
  - [ ] le menu en mode portrait ne s'ouvre pas, il clignote ouvert/fermé une fois quand on touche ==> pb mm sur ordi
- [ ] hub_client :: case pour passer l'assistant?? (je crois que c'est possible en moins de 20 minutes)
- [x] hub_client :: petit 'x' sur les cases de romans pour "effacer" un roman?
- [x] hub_client :: sous-menu "retour a la selection roman / retour au hub" apparait?
- [x] faute à 000webhost!! ==>> il y as des '\' pour les apostrophes et les guillemets dans les entitées, le synopsis et les textes
- [x] changer index.php pour reflèter le changement de nom et mettre le texte d'Olivier
- [ ] mettre la (nouvelle) vidéo, si possible.
- [ ] Ok, créer un nouvel usager ne fonctionne pas sur IE8 (oui sur XP, je teste et prend congé de code, je sais, je sais c'est pas trop le temps)... s'identifier non plus O_o HAHAHAHAHA!!!
- [x] hub_client :: la case 'ajouter' n'affiche pas son '+' :'(
- [x] BUG : si un type d'entitée est vide et qu'on tente d'en ajouter, l'enregistement ne se fait pas ou mal, l'application pense qu'on est toujours en édition et quand on recharge le type, la nouvelle entitée n'y est pas... en fait elle apparait dans la BD; le problème est  probablement causé par  "l'entité" bidon là pour dire que ce type est vide, devrais recevoir l'ID 9999 et être tué quand on ajoute une entitée, APRÈS qu'elle ais été sauvée, par contre aucune obligation de recréer ce "bidon"
    - [x] je pense règlé, faire plus de tests!
	- [x] bug mémoire, reste là si effacé
	- [x] bug BD si un item (son prev = 0), on ajoute 1 et on efface le premier, le nouveau pointe encore sur le premier qui avait prev=0 MAIS JS recoit correctement que son first/last est ce seul entitée
- [ ] voir ce que j'ai fait pour le TP PHP et tenter d'éliminer le foutu code de l'hébergeur, s'il y as toujours des problèmes ONLINE
- [x] le textarea n'est pas correctement caché en attendant que le texte se charge (parait plus en ligne...)

Idées et principes abandonnés:
- [ ] autre idée : au lieu d'utiliser une variable "globale" pour retenir le texte et les notes (applicable aussi aux "entitées" dans un deuxième temps), utiliser le "web storage", si indisponible alors seulement là utiliser une var globale!
- [ ] bug : le retour de "execXHR_Request" elle-même n'est pas traité, c'est à dire que si la fonction retourne purement FALSE (le fureteur du client ne supporte pas XHR), cette erreur n'est traité (je crois) par aucun de mes "wrappers".
- [ ] (lié au dragNdrop) pour editionProjet.xhr.php>>oper=deplacer, voir ce que j'ai fait avec assistant_creation.xhr.php>>creerLeRoman pour ce qui est de la multi-mise à jour en une seule requête
-ne pas faire le truc d'inspiration!
-ne pas oublier le drag'n'drop, voir où mettre le fameux icône
- [ ] pour le probleme de note "deleted", sur restauration on la replace à la toute fin? càd que son prev devient celui qui as next==0 et elle hérite elle-même de next=0
- [ ] entitées et romans deleted, comment proposer la liste? mettre une sorte de poubelle?
- [ ] nouvelle page admin, affiche sur chaque ligne : nom roman, nom usager, genre littéraire, état DELETED, bouton DELETE (ou pour les deux derniers, bouton del/undel)
- [ ] page Édition : retirer le besoin pour 'typeEntite' partout où c'est possible, surtout considérant que, tant qu'on parle des entitées, on peux faire presque tout seulement avec leur ID_entite pour les identifier de façon unique.
	-ecrire :: possible si je part avec le fait que si "titre"(et note) est spécifié, l'entité pointée n'est pas le textePrincipal
	-déplacer :: possible aussi je crois
- [ ] page Édition : changer EFFACER comme suit : si on recoit un idEntite, c'est un quoi,etc... si on recoit un idRoman, c'est le textePrincipal/Roman table roman_details
- [ ] Page index (au moins) : encryption des données usager?
- [ ] Page index (au moins) : lire les APIs de G+ et F pour le login
- [ ] Page index (au moins) : lire à propos de et implémenter mdp encryptés? voir http://glynrob.com/javascript/client-side-hashing-and-encryption/
- [ ] Page index (au moins) : si on encrypte les données ET qu'on n'enregistre pas le MdP sur le serveur, par sécurité, ça veux dire qu'on peux associer les données au nom d'usager et que seul le bon mot de passe décrypte correctement les données, donc pas de validation MdP, je dois lire plus pour voir si j'ai bien compris comment implémenter un MdP qui n'est pas sauvé (même encrypté) sur le serveur. Implémenter?
- [ ] page Édition : voir http://www.fakenamegenerator.com/faq.php :: http://www.roguebasin.com/index.php?title=Random_name_generation :: http://www.godpatterns.com/2005/09/name-generators-for-npcs-to-populate.html
- [ ] page Édition : Page admin avec stats, #auteurs, #romans, etc???
- [ ] page Édition : lire plus sur le mécanisme de drag&drop pour savoir comment aborder le réordonnement des entitées --> http://www.html5rocks.com/en/tutorials/dnd/basics/ --> Modernizr --> pas compliqué juste 30-40 lignes de JS

============================================

####Page index (au moins) ::
- [x] rendre plus gracieux la manipulation des erreurs MySQL, surtout pour le bénéfice de XHR
- [x] créer la structure logique des tables
- [x] créer des scénarios avec données fictives pour voir si types des champs sont erronés ou si champs manquant
- [x] créer les tables et exporter un .SQL vers GH
- [x] lire à propos de et prévoir XSS
- [x] lire à propos de et prévoir SQL injection
- [x] fichier XHR + code JS pour query de validité usager (usr+pwd)
- [x] fichier XHR + code JS pour query de disponiblité nom usager, à moins de fusionner avec "validité" et d'utiliser les codes de retour ex:
 * 0=usager inexistant (usager libre ou nom mal tapé),
 * 1=usager existant (usager indisponible ou nom bien tapé),
 * 2=mot de passe invalide,
 * 3=mot de passe OK (-doit- sous-entendre usager existant) ; suppose que le champs PWD peux être vide et si c'est le cas la validation du PWD n'est pas faite donc pas de code 2 erroné
- [x] reprendre assets/inc/header.inc.php et corriger la description et les keywords, autant faire ça correctement pour le SEO ;)


####Page selectionProjet ::
- [x] fonction qui retourne les projets existants de l'usager en notation JSON (parce que plus efficace que ma façon de "parser" du TP de HTML5 et avant!!)
- [x] vérifier sinon coder la fonction qui retourne tout les romans de l'usager
	* fait mais manque intégration dans "hub_client.php"


####Page creationProjet ::
- [x] est-ce qu'on veux qu'il soit possible de suspendre l'assistant et reprendre au même endroit?
	* retiré
- [x] créer une page pour "créer" les genres littéraire ds la BD?
	* retiré
- [x] page admin pour pouvoir ajouter personnages.role_fonction, personnages.role_poid, lieux.type_environnement, lieux.type_acces ??
	* retiré
- [x] fonction qui ne retourne -que- les noms/ID des genres
- [x] déterminer si pour la lecture des données du genre choisi, une seule fonction retourne tout ou si on as une fonction qui initialise(avec retour de la première question et du nombre total de questions) et une qui demande la suite pour répondre FALSE s'il n'y as rien d'autres ?
- [x] fonction avec jQuery pour créer les balises nécessaire pour afficher les questions
- [x] fonction qui enregistre tout
- [x] fonction pour créer le Roman dans les tables roman_details et roman_texte
- [x] fonction qui encode les texte, doit être globale et utilisable par n'importe quel fichier (création + édition)
- [x] fonction qui DÉcode les texte, doit être globale et utilisable par n'importe quel fichier
- [x] faire la page des questions avec ce que Thomas as créé en page et en data
- [x] fonction sauvegardertexte pas finie, manque les params
- [x] changer la fonction afficherAttente, elle doit maintenant : 1. occulter le FORM et le BUTTON de 'next'; 2. afficher un DIV ou P dédié et placé -avant- le FORM; 3. créer une fonction opposée? L'idée est de pouvoir cacher le FORM durant la recomposition de son contenu indépendamment; 4. lui donner aussi un param pour le message! si pas là, mettre un défaut :)


####Page editionProjet
- [x] décider si mettre "roman.contenu" dans fichier texte séparé au lieu de MySQL? <-- pê exagéré pour la démo??
 * Dans la BD
- [x] décider si mettre "notes.contenu" aussi ds fichiers séparés? (format JSON pour retenir états sticky, deleted, etc... noms genre <{nom_auteur}_{ID_projet}_{ID_note}>.json)
 * Dans la BD
- [x] charger les "notes" à la demande (XHR) s'il y en as pour plus que 2mo (total, pas chaque) <-- mécanisme inutile pour la démo ?
- [x] gestion des balises (Liens entre les entitées sont des "A") à l'intérieur des "entitées" et du "document", c'est à dire trouver moyen simple de supporter et implémenter l'idée tout en évitant d'enregistrer les balises comme partie intégrante des blocs (de texte)
 * retiré
- [x] fonction XHR pour enregister les changements aux entitées (contenu, deleted, sticky)
- [x] fonction XHR pour enregistrer le document
- [x] permettre de dragger une "note" sur le document et ça fait copy-paste?
 * retiré
- [x] permettre de dragger un personnage, un lieu ou un "autres" sur le document et ça copie le nom + lien?
 * retiré
 - [x] bug : s'il n'y as aucune entrée dans la table romans_details mais que celle correspondant dans romans_texte existe, agit comme s'il ne manquait rien, donc changer le code pour tout passer par romans_details, surtout que c'est elle qui décide si un roman est deleted ou non; en fait le bug persiste avec les entitées! oui bon, dans le cas des entitées, en principe une fois le code bien écrit il sera impossible de les charger ^_^'...
 - [x] Une fois le CSS fini pour les toolbars de la page Edition, ne pas oublier de restaurer le code dans header.inc (~ln 5), mode_edition.js (~ln210 >> lireEntites) et enlever dans mode_edition.php le code direct en recopiant les changements dans mode_edition.js (~ln331 afficherEntite)
- [x] autosave texte principal, solution 1: lancer intervalle, si var1 et var2 sont 0 alors ne rien faire; si on tape (keyup) var1++; si qd intervalle arrive var1 et var2 différents alors var2=var1; si var1==var2 et tous deux !== 0 alors sauvegarder et var1 et var2 === 0
- [x] autosave texte principal, solution 2: pour la sauvegarde, il faudrait changer pour qu'il y ais ces variables (booleennes):
	= mainText_DirtyBit_GUI si on as modifié le texte présentement à l'écran, remplace data-dirtybit
	= mainText_DirtyBit_Disk_Texte si on as copié le texte en mémoire mais qu'il n'as pas été enregistré ds la BD
	= mainText_DirtyBit_Disk_Notes si on as copié les notes en mémoire mais qu'elles n'ont pas été enregistrées ds la BD
- [x] autosave texte principal, solution 3: autosave, autre idée : un timeinterval aux secondes, onKeyUp on enregistre la date en timestamp, une autre variable enregistre le timestamp de la dernière sauvegarde; si le temps au moment du timeInterval entre les deux timeStamps >= disons 7 secondes, alors on tue l'intervalle puis on copie ds la BD le texte et au retour de l'opération XHR, on copie le timestamp courant vers le "dernier timestamp"(de sauvegarde)
- [x] petit bug : le bouton "lire entitées" de la page Édition ne lit pas tout les entitées, un peu normal, il faut autant de requêtes que de types, pour la démo je pourrais le faire (tout les appels)
- [x] bug : encodage pas au point ou cest pcq jai pas updater page edition avec les ameliorations de creation... hum ... ??? O_o spécifiquement les guillemets
- [x] menage code mort!!!
- [x] tout re-commenter
- [x] page de test pour formulaire + table pour le supporter
- [x] Onglets entités
	- [x] phase 1: onclick aller prendre parent, du parent dire aux enfants de perdre classe "active", donne a enfant cliquer classe active
	- [x] phase 2: tjrs onclick détecter si texte balise <a>== $(this)>a.text si oui on as cliquer celui qui est actif ou juste lire si as classe actif, si oui ne rien faire
	- [x] phase 3: onclick si onglet différent tester si ds var globale on as les données pour le nouvel onglet sinon charger les données
- [x] scinder code pour récupérer les entitées entre fct qui lit les données et fct qui génère les balises
- [x] onglet fct qui lit, si pour un type il y as 0 entrées ds la BD alors "first" ==0 pour savoir que au moins as été lu... malgré que si l'entrée array existe, c'est qu'on as au moins essayé de lire...
- [x] copier ce qui est pertinent du code pour les onglets entités vers les onglet textePrincipal et Notes globales
- [x] changer création Roman ds BD pour enregistrer le synopsis aussi dans champs notes
- ~~ [] créer fct qui ajoute des boutons pour modifier les entitées et fct onblur qui fait ESC/restaure le texte à partir de la mémoire. Btns poppent... non ~~
- [x] btns pour modifier les entitées sont ajouté à la création, pê ajouter une couche de DIV (si overflow hidden pas suffisant ou que visuellement ça fct pas)? laisser les gars faire mieux
	- [x] {entitées} faire la copie des span vers la mémoire quand l'usager clique le bouton [accepter] -et- vers la BD
	- [x] {entitées} faire la copie de la mémoire vers les span quand l'usager clique le bouton [annuler]
- [x] ajouter mini-toolbar pour faire l'ajout d'entitées
- [x] ajouter mini-toolbar aux entitées pour edit (ou commence avec un dbl-click?) et delete
- [x] faire un trim (g/d) des espaces avant d'enregistrer une donnée (titre, texte, nom, etc); ajouter ceci (aussi) à la fonction real_escape_string ?
- [x] page Édition : BUG :: si on change l'état DELETED, les entitées pointées par PREV/NEXT de l'entitée retirée ne sont -pas- corrigées ni au retrait, ni à la récupération, à corriger dès que possible
- [x] page Édition : Tester plus pour savoir si j'ai un bug avec la fonction "miseAJourDonneesEntite" ou si c'est simplement que j'essaie de sauver une fraction avant l'ordi et donc que non seulement le flag "DirtyBit" dans le code JS est toujours vrai mais en plus qu'il n'y as rien à changer, aucune erreur et donc que je devrais changer le code pour NE PAS renvoyer d'erreur quand "rows_affected" == 0...

 =====

char / varchar :: max 255
blob (case sensitive Text) / text :: max 2^8 (note: les espaces en début et fin ne sont pas trimmés)
voir :: http://dev.mysql.com/doc/refman/5.5/en/storage-requirements.html
et :: http://dev.mysql.com/doc/refman/5.5/en/data-types.html

structure des tables de BD ::

représentation des questions/réponses pour l'assistant dans la BD :
- genres_litteraires
 * ID_ligne_genre: Unique, clé primaire, uTinyInt
 * ID_genre : uSmallInt
 * nom : varchar(50)
 * nro_question : uTinyInt
 * texte : varchar(255)
 * type_input (text, select, ...) : varchar(20)
 * suggestions : Text
 - pour input="text" : chaine et si elle comporte un "¤" alors tout ce qui le suit est un 'placeholder', s'il y as encore un "¤" alors on suppose que ce qui suit sont des valeurs pour un datalist, ex: "jaune¤couleur du pantalon¤rouge¤vert¤noir "
 - pour select : liste séparée par des "¤" (la valeur sera la position numérique dans la liste d'options, au moment d'afficher on reprend le texte(?))
 * bouton_fonction : varchar(40);
 - si valeur = NUL alors pas de bouton, sinon nom de la fonction liée et après le "¤" c'est le texte qui doit apparaitre ex: "tirer_nom(4, 20, 2)¤Générer" (ici je pense : min lng, max lng, nombre de mots)
 - le bouton "(question) Suivant" est toujours ajouté par le code pour toutes les questions et la dernière (question) dicte qu'on doit mettre "Fin" à la place pour ensuite compiler les réponses en synopsis.

- usagers:
 * ID_usager : Unique, clé primaire, uMediumInt
 * pseudo : varchar(30)
 * nom : varchar(50)
 * motdepasse : varchar(20)
 * courriel : varchar(40)
 * dateInscription : datetime

- roman:
 * ID_roman : Unique, clé primaire, uInt
 * ID_usager : uMediumInt
 * ID_genre : bit(8) (si 0 alors "page blanche", si cloné/sequel alors même que "parent", si plus qu'un c'est le total mais la limite est que genres_litteraires.ID_genre ne peux pas dépasser 127)
 * titre : varchar(50)
 * synopsis : Text
 * contenu : mediumText
 * date_creation : datetime
 * date_dnrEdition : datetime
 * choix_assistant : Text, les choix fait lors de la création séparés par "¤" si on as utilisé l'assistant, sinon NUL
 * deleted : bit (1)

- personnages:
 * ID_personnage : Unique, clé primaire, uInt
 * ID_roman : uInt
 * ID_prev : uInt, index du personnage à afficher -avant- ou 0 si premier
 * ID_next : uInt, index du personnage à afficher -après- ou 0 si dernier
 * ~~IDs_references : Text, liste des IDs des autres entitées référencées sous la forme "[lettre][chiffres]" séparés par "¤" où [lettre] est le type tel que [p]ersonnage/ [n]ote / [l]ieux/ [a]utres suivi du ID_[perso/lieu/note/autres], donne ~5039 références max @~11 char+1séparateur/référence vs varchar(255) qui donnait ~18 ~~
 * nom : varchar(50)
 * sexe : enum[femme, homme]
 * role_fonction : enum [protagoniste, antagoniste, figurant, intérêt amoureux, ...]
 * ~~role_poid: enum [primaire, secondaire, tertiaire, ...]~~
 * taille_cm : uSmallInt
 * poids_kg : uSmallInt
 * description : Text, tout les autres détails, dont "background"
 * sticky : bit (1), si TRUE alors (discuter laquelle des trois options prendre) soit l'entitée apparait en tête de sa liste, soit elle apparait dans un onglet dédié (stickied), soit les deux
 * deleted : bit (1)

- lieux:
 * ID_lieu : Unique, clé primaire, uInt
 * ID_roman : uInt
 * ID_prev : uInt, index du lieu à afficher -avant- ou 0 si premier
 * ID_next : uInt, index du lieu à afficher -après- ou 0 si dernier
 * ~~IDs_references : Text, liste des IDs des autres entitées référencées sous la forme "[lettre][chiffres]" séparés par "¤" où [lettre] est le type tel que [p]ersonnage/ [n]ote / [l]ieux/ [a]utres suivi du ID_[perso/lieu/note/autres], donne ~5039 références max @~11 char+1séparateur/référence vs varchar(255) qui donnait ~18 ~~
 * ~~type_environnement enum[intérieur, extérieur, sous-terrain, sous-marin, sous vide (espace)]~~
 * ~~type_acces enum[privé, publique, sur invitation]~~
 * nom : varchar(50)
 * taille_approx_m3 : uMediumInt (mesure en mètres cubes)
 * description : Text, tout les autres détails, dont "background"
 * sticky : bit (1), si TRUE alors (discuter laquelle des trois options prendre) soit l'entitée apparait en tête de sa liste, soit elle apparait dans un onglet dédié (stickied), soit les deux
 * deleted : bit (1)

- notes :
 * ID_note : Unique, clé primaire, uInt
 * ID_roman : uInt
 * ID_prev : uInt, index de note à afficher -avant- ou 0 si premier
 * ID_next : uInt, index de note à afficher -après- ou 0 si dernier
 * ~~IDs_references : Text, liste des IDs des autres entitées référencées sous la forme "[lettre][chiffres]" séparés par "¤" où [lettre] est le type tel que [p]ersonnage/ [n]ote / [l]ieux/ [a]utres suivi du ID_[perso/lieu/note/autres], donne ~5039 références max @~11 char+1séparateur/référence vs varchar(255) qui donnait ~18 ~~
 * contenu : Text
 * sticky : bit (1), si TRUE alors (discuter laquelle des trois options prendre) soit l'entitée apparait en tête de sa liste, soit elle apparait dans un onglet dédié (stickied), soit les deux
 * deleted : bit (1)

- autres (ex:bateaux, avion, coffre d'outils, le Tardis,...):
 * ID_autres : Unique, clé primaire, uInt
 * ID_roman : uInt
 * ID_prev : uInt, index de "autres" à afficher -avant- ou 0 si premier
 * ID_next : uInt, index de "autres" à afficher -après- ou 0 si dernier
 * ~~IDs_references : Text, liste des IDs des autres entitées référencées sous la forme "[lettre][chiffres]" séparés par "¤" où [lettre] est le type tel que [p]ersonnage/ [n]ote / [l]ieux/ [a]utres suivi du ID_[perso/lieu/note/autres], donne ~5039 références max @~11 char+1séparateur/référence vs varchar(255) qui donnait ~18 ~~
 * nom : varchar(50)
 * description : Text
 * sticky : bit (1), si TRUE alors (discuter laquelle des trois options prendre) soit l'entitée apparait en tête de sa liste, soit elle apparait dans un onglet dédié (stickied), soit les deux
 * deleted : bit (1)


=====

mettre ici une copie du manuel mais avec ce qui se passe derrière et fonctionnellement? ex: usager veux changer de projet/roman : 1. tout saved? non-> proposer save / oui -> 2. header(location:index mode ouverture), etc... -décrire le fonctionnement interne (JS, PHP/Classes, MySQL, etc).

création d'un roman à partir d'un autre: on clone direct les entitées et c'est tout.

Classe cNote qui contient un membre privé pour son texte, un membre privé pour son ID, un membre "array of integers" pour retenir ses liens dont l'index 0 est l'id de son précédent (ou valeur = 0 s'il est le premier du projet), l'index 1 est l'id de son suivant (ou 0 s'il est le dernier du projet) et le reste sont les ids vers lesquels il pointe tel que d'autres cNote qui selon l'usager, auront une pertinence comme je le fait dans "O5".

Classe cPersonnage enfant de cNote, traite son membre texte comme une suite de renseignements séparés par un '¤' ou autre de telle facon à garder les renseignements voulu par l'utilisateur, ex:un usager mettrais l'éducation et un autre les talents. À l'installation de l'application, un nouveau cPersonnage aura une série de défauts plausibles tel que nom, âge et sexe mais en laissant le choix de les effacer ou en ajouter autant pour l'instance en cours, le projet en cours que vers un (nouveau) cPersonnage modèle (remplace celui créé par l'installation).

Classe cLieu enfant de cNote, avec des possibilités semblables à cPersonnage en terme de fiche défaut pour l'application, fiche défaut pour le projet et fiche sur mesure pour une instance

Classe cProjet contient les renseignements tel que son nom, son auteur (instance de cPersonnage) et les id racines pour les cNote personnages, lieu, etc

== EOF ==

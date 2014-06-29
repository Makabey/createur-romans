###Fichier d'Eric
####Contient tâches, idées, concepts, questions et "ToDo"

To Do:
- [x] bug : encodage pas au point ou cest pcq jai pas updater page edition avec les ameliorations de creation... hum ... ??? O_o spécifiquement les guillemets
- [x] menage code mort!!!
- [x] tout re-commenter
- [ ] vérifier sinon coder la fonction qui retourne tout les romans de l'usager
- [ ] écrire la fonction de création d'usager
- [ ] écrire la fonction de lecture usager, voir en fait la requête plus bas (sous "page index")
- [x] page de test pour formulaire + table pour le supporter
- [ ] Changer la sauvegarde pour qu'elle s'effectue si le dirtyBit est ON -et- qu'on as plus tapé depuis x secondes. Autre possibilitée, detecter si le "web storage" est permis/disponible et si OUI alors sauve localement aux 15 secondes et aux 300 secs sur le web si on ne le force pas par l'interface (bouton)
- [x] faire la page des questions avec ce que Thomas as créé en page et en data
- [x] fonction sauvegardertexte pas finie, manque les params
- [x] voir "Page creationProjet"
- [x] changer la fonction afficherAttente, elle doit maintenant : 1. occulter le FORM et le BUTTON de 'next'; 2. afficher un DIV ou P dédié et placé -avant- le FORM; 3. créer une fonction opposée? L'idée est de pouvoir cacher le FORM durant la recomposition de son contenu indépendamment; 4. lui donner aussi un param pour le message! si pas là, mettre un défaut :)
- [x] bug : s'il n'y as aucune entrée dans la table romans_details mais que celle correspondant dans romans_texte existe, agit comme s'il ne manquait rien, donc changer le code pour tout passer par romans_details, surtout que c'est elle qui décide si un roman est deleted ou non; en fait le bug persiste avec les entitées! oui bon, dans le cas des entitées, en principe une fois le code bien écrit il sera impossible de les charger ^_^'...
- [ ] petit bug : le bouton "lire entitées" de la page Édition ne lit pas tout les entitées, un peu normal, il faut autant de requêtes que de types, pour la démo je pourrais le faire (tout les appels)
- [ ] bug : le retour de "execXHR_Request" elle-même n'est pas traité, c'est à dire que si la fonction retourne purement FALSE (le fureteur du client ne supporte pas XHR), cette erreur n'est traité (je crois) par aucun de mes "wrappers".
-[ ] pour editionProjet.xhr.php//oper=deplacer, voir ce que j'ai fait avec assistant_creation.xhr.php//creerLeRoman pour ce qui est de la multi-mise à jour en une seule requête

====

####Page index (au moins) ::
- [ ] rendre plus gracieux la manipulation des erreurs MySQL, surtout pour le bénéfice de XHR
- [x] créer la structure logique des tables
- [ ] créer des scénarios avec données fictives pour voir si types des champs sont erronés ou si champs manquant
- [x] créer les tables et exporter un .SQL vers GH
- [ ] lire à propos de et prévoir XSS
- [ ] lire à propos de et prévoir SQL injection
- [ ] lire à propos de et implémenter mdp encryptés? voir http://glynrob.com/javascript/client-side-hashing-and-encryption/
- [ ] encryption des données usager?
- [ ] lire les APIs de G+ et F pour le login
- [ ] fichier XHR + code JS pour query de validité usager (usr+pwd)
- [ ] fichier XHR + code JS pour query de disponiblité nom usager, à moins de fusionner avec "validité" et d'utiliser les codes de retour ex:
 * 0=usager inexistant (usager libre ou nom mal tapé),
 * 1=usager existant (usager indisponible ou nom bien tapé),
 * 2=mot de passe invalide,
 * 3=mot de passe OK (-doit- sous-entendre usager existant) ; suppose que le champs PWD peux être vide et si c'est le cas la validation du PWD n'est pas faite donc pas de code 2 erroné
- [ ] si on encrypte les données ET qu'on n'enregistre pas le MdP sur le serveur, par sécurité, ça veux dire qu'on peux associer les données au nom d'usager et que seul le bon mot de passe décrypte correctement les données, donc pas de validation MdP, je dois lire plus pour voir si j'ai bien compris comment implémenter un MdP qui n'est pas sauvé (même encrypté) sur le serveur. Implémenter?
- [x] reprendre assets/inc/header.inc.php et corriger la description et les keywords, autant faire ça correctement pour le SEO ;)
- [ ] voir http://www.fakenamegenerator.com/faq.php :: http://www.roguebasin.com/index.php?title=Random_name_generation :: http://www.godpatterns.com/2005/09/name-generators-for-npcs-to-populate.html

####Page selectionProjet ::
- [ ] fonction qui retourne les projets existants de l'usager en notation JSON (parce que plus efficace que ma façon de "parser" du TP de HTML5 et avant!!)

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
- [ ] fonction qui enregistre tout
- [x] fonction pour créer le Roman dans les tables roman_details et roman_texte
- [ ] fonction qui encode les texte, doit être globale et utilisable par n'importe quel fichier (création + édition)
- [ ] fonction qui DÉcode les texte, doit être globale et utilisable par n'importe quel fichier

####Page editionProjet
- [x] décider si mettre "roman.contenu" dans fichier texte séparé au lieu de MySQL? <-- pê exagéré pour la démo??
 * Dans la BD
- [x] décider si mettre "notes.contenu" aussi ds fichiers séparés? (format JSON pour retenir états sticky, deleted, etc... noms genre <{nom_auteur}_{ID_projet}_{ID_note}>.json)
 * Dans la BD
- [x] charger les "notes" à la demande (XHR) s'il y en as pour plus que 2mo (total, pas chaque) <-- mécanisme inutile pour la démo ?
- [x] gestion des balises (Liens entre les entitées sont des "A") à l'intérieur des "entitées" et du "document", c'est à dire trouver moyen simple de supporter et implémenter l'idée tout en évitant d'enregistrer les balises comme partie intégrante des blocs (de texte)
 * retiré
- [ ] Page admin avec stats, #auteurs, #romans, etc???
- [x] fonction XHR pour enregister les changements aux entitées (contenu, deleted, sticky)
- [x] fonction XHR pour enregistrer le document
- [ ] lire plus sur le mécanisme de drag&drop pour savoir comment aborder le réordonnement des entitées --> http://www.html5rocks.com/en/tutorials/dnd/basics/ --> Modernizr --> pas compliqué juste 30-40 lignes de JS
- [x] permettre de dragger une "note" sur le document et ça fait copy-paste?
 * retiré
- [x] permettre de dragger un personnage, un lieu ou un "autres" sur le document et ça copie le nom + lien?
 * retiré
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

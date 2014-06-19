###Fichier d'Eric
####Contient tâches, idées, concepts, questions et "ToDo"

To Do:

====

	représentation des questions/réponses pour l'assistant dans la BD :
		ID_genre,
		nro_question,
		texte,
		type_input (text, select, ...),
		valeurs_defaut
			(pour "text":chaine et si elle comporte un ";" alors tout ce qui le suit est un 'placeholder',
			pour "select":liste séparée par des ";" (la valeur est la position dans la liste, plutôt que de mettre une valeur dans ce champs, et au moment d'afficher, on reprend le texte(?)), ...),
		bouton_fonction
				(si "nul" alors pas de bouton, sinon ex: "tirer_nom(4, 20, 2);générer" (ici je pense : min lng, max lng, nombre de mots) et après le ";" c'est le texte qui doit apparaitre) 
				// le bouton "(question) suivant" est toujours ajouté par le code pour toutes les questions et la dernière (question) dicte qu'on doit mettre "fin" à la place pour ensuite compiler les réponses en synopsis.

* structure des tables de BD ::
  - usagers:
    * ID_usager : Unique, clé primaire, uInt
	* pseudo : varchar(20)
	* nom : varchar(50)
	* motdepasse : varchar(25)
    * courriel : varchar(30)
	* dateInscription : datetime
	
  - personnages:
    * ID_personnage : unique, clé primaire
	* ID_roman : uInt
	* ID_prev : uInt, index du personnage à afficher -avant- ou 0 si premier
	* ID_next : uInt, index du personnage à afficher -après- ou 0 si dernier
	* IDs_references, varchar(200), liste des IDs des autres entitées référencées sous la forme "[lettre][chiffres]" séparés par point-virgule où [lettre] est le type tel que [p]ersonnage/ [n]ote / [l]ieux/ [a]utres
	* nom : varchar(50)
	* sexe : boolean
	* role_fonction : enum [protagoniste, antagoniste, figurant, intérêt amoureux, ...]
	* role_poid: enum [primaire, secondaire, tertiaire, ...]
	* taille_cm : uInt
	* poids_kg : uInt
	* description : varchar(500), tout les autres détails, dont "background"
	* sticky : boolean, si malgré la position calculée, doit apparaitre parmis les premiers (ou sur l'onglet "stickied" si c'est ce qu'on fait)
	* deleted : boolean
	
  - lieux, sticky o/n, deleted o/n
  - notes : ID note(parmis tout les romans), ID roman, ID_Prev/Next (si Prev ou Next=0 alors en tête/pied de liste), liste ID autres entitées référencées, sticky o/n, deleted o/n
  - autres (ex:bateaux, avion, coffre d'outils, le Tardis,...): ID autres, ID roman, ID_Prev/Next (si on permet de les réordonner, sinon 0 pour les deux), identifiant/nom, description, sticky o/n, deleted o/n
  - roman: ID_roman, ID_usager, titre, synopsis, date_creation, date_dnrEdition, deleted o/n, ID_genre (si 0 alors "page blanche", si cloné/sequel alors même que "parent", si plus qu'un, séparés par ";")

 =====
  
Les données dans une BD MySQL. 

Liens entre les entitées sont des "A"

mettre ici une copie du manuel mais avec ce qui se passe derrière et fonctionnellement? ex: usager veux changer de projet/roman : 1. tout saved? non-> proposer save / oui -> 2. header(location:index mode ouverture), etc... -décrire le fonctionnement interne (JS, PHP/Classes, MySQL, etc).

création d'un roman à partir d'un autre:  on clone direct les entitées et c'est tout.

XSS

SQL injection...

mdp encryptés?

corps du document dans fichier texte séparé au lieu de MySQL? <-- pê exagéré pour la démo

corps des "notes" aussi ds fichier séparés? (format JSON, noms genre <auteur_projet_ID>.json)

charger les "notes" à la demande (XHR) s'il y en as pour plus que 2mo (total, pas chaque) <-- donc mécanisme inutile pour la démo

* lire les APIs de G+ et F pour le login

Classe cNote qui contient un membre privé pour son texte, un membre privé pour son ID, un membre "array of integers" pour retenir ses liens dont l'index 0 est l'id de son précédent (ou valeur = 0 s'il est le premier du projet), l'index 1 est l'id de son suivant (ou 0 s'il est le dernier du projet) et le reste sont les ids vers lesquels il pointe tel que d'autres cNote qui selon l'usager, auront une pertinence comme je le fait dans "O5".

Classe cPersonnage enfant de cNote, traite son membre texte comme une suite de renseignements séparés par un ';' ou autre de telle facon à garder les renseignements voulu par l'utilisateur, ex:un usager mettrais l'éducation et un autre les talents. À l'installation de l'application, un nouveau cPersonnage aura une série de défauts plausibles tel que nom, âge et sexe mais en laissant le choix de les effacer ou en ajouter autant pour l'instance en cours, le projet en cours que vers un (nouveau) cPersonnage modèle (remplace celui créé par l'installation).

Classe cLieu enfant de cNote, avec des possibilités semblables à cPersonnage en terme de fiche défaut pour l'application, fiche défaut pour le projet et fiche sur mesure pour une instance

Classe cProjet contient les renseignements tel que son nom, son auteur (instance de cPersonnage) et les id racines pour les cNote personnages, lieu, etc

== EOF ==

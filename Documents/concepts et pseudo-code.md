#Concepts et pseudo-code

au premier login de l'usager, soit on luil donne un projet exemple, soit on le guide tout de suite dans la création de son premier projet.

-pour éviter trop d'onglets (entitées), il faudrait en limiter le nombre (4-5?).



Les données seront dans une BD MySQL. Si la chose est bien fait, il ne sera pas trop difficile de réécrire certaines parties pour utiliser du XML et du CSV dans le but de pouvoir tout recréer (la structure des oeuvres) sans trop de tracas en advenant une corruption de la BD ou un désir de recopier dans un autre projet/logiciel le contenu généré.


-ici un "lien" est une balise "A" pour les liens internes; si c'est un lien externe, il aurais une classe CSS pour le souligner et nécessite que l'insertion soit faite par les outils de l'application. 


mettre ici une copie du manuel mais avec ce qui se passe derrière et fonctionnellement? ex: usager veux changer de projet/roman : 1. tout saved? non-> proposer save / oui -> 2. header(location:index mode ouverture), etc... -décrire le fonctionnement interne (JS, PHP/Classes, MySQL, etc).

##Installation (hébergeur, équipe de développement)
- Copier les fichiers dans le répertoire www
- Exécuter l'upload de la base MySQL
- Tester l'application

création d'un roman à partir d'un autre: si on faisait quelque chose d'ultra efficace, dans la table "roman" on nommerait par ID les personnages, lieux et autres sous le roman lui-même, ce qui permettrait de mettre une référence dans le nouveau roman (pour économiser de l'espace de base de données) et à chaque sauvegarde d'une entitée autre qu'une note (qui elles fonctionnent à l'inverse, c'est à dire que ce sont elles qui dictent à qui elle appartiennent) serait modifiée, on vérifierait si elle appartient à un autre roman et c'est seulement à ce moment qu'on clonerait si la réponse est positive. Dans les faits on clone direct et c'est tout.


XSS
SQL injection...
mdp encryptés?
corps du document dans fichier texte séparé au lieu de MySQL? <-- pê exagéré pour la démo
corps des "notes" aussi ds fichier séparés (format JSON, noms genre <auteur_projet_ID>.json)
charger les "notes"  à la demande (XHR) s'il y en as pour plus que 2mo (total, pas chaque) <-- donc mécanisme inutile pour la démo

tables :
usagers:ID, pseudo, nom, mdp, prefCss
personnages:ID personnage(parmis tout les romans), ID roman, ID_Prev/Next (si on permet de les réordonner, sinon 0 pour les deux), liste ID autres entitées référencées sous la forme [lettre][chiffres] où [lettre] est le type tel que [p]ersonnage/[n]ote/etc, nom, sexe, role (protagoniste primaire, secondaire, antagoniste, rempllssage,...), taille, description
lieux
notes : ID note(parmis tout les romans), ID roman, ID_Prev/Next (si Prev ou Next=0 alors en tête/pied de liste), liste ID autres entitées référencées
autres (ex:bateaux, avion, coffre d'outils, le Tardis,...): ID autres, ID roman, ID_Prev/Next (si on permet de les réordonner, sinon 0 pour les deux), identifiant/nom, description


5. Scinder les idées en concepts/pseudo-code;

=Partie #5 (incomplet)
L'application web sera partagée entre une BD MySQL pour accélérer certaines parties (comme les liens entre les blocs de données; un bloc défini comme personnage, note, texte) et la gestion de fichiers XML qui contiendront principalement les blocs de texte plus long que 64 caractères.

À l'installation, scénario plausible :
- l'usager arrive sur un "index.php" lequel pose quelques questions comme le nom par lequel il veux être adressé par l'application, la type de projet par défaut, le thème d'affichage (CSS, couleurs, etc) par défaut pour un nouveau projet, la façon de proposer l'ouverture d'un projet existant tel que par le menu ou des tuiles (qui seront remplacées par l'interface après le chargement), mot de passe 'root' de MySQL (si changé), etc.
- à l'envois, un second fichier initialise toutes les parties BD/XML/Config, les défauts tels que le projet 'NUL' et une oeuvre exemple, et quand tout vas et fini, il écrase le "index.php" pour ensuite renommer "index.prod.php" (préexistant) en "index.php" et le redirige là.
- L'application est installée et prête à travailler.

Classe cNote qui contient un membre privé pour son texte, un membre privé pour son ID, un membre "array of integers" pour retenir ses liens dont l'index 0 est l'id de son précédent (ou 0 s'il est le premier du projet), l'index 1 est l'id de son suivant (ou 0 s'il est le dernier du projet) et le reste sont les ids vers lesquels il pointe tel que d'autres cNote qui selon l'usager, auront une pertinence comme je le fait dans "O5".

Classe cPersonnage enfant de cNote, traite son membre texte comme une suite de renseignements séparés par un ';' ou autre de telle facon à garder les renseignements voulu par l'utilisateur, ex:un usager mettrais l'éducation et un autre les talents. À l'installation de l'application, un nouveau cPersonnage aura une série de défauts plausibles tel que nom, âge et sexe mais en laissant le choix de les effacer ou en ajouter autant pour l'instance en cours, le projet en cours que vers un (nouveau) cPersonnage modèle (remplace celui créé par l'installation).

Classe cLieu enfant de cNote, avec des possibilités semblables à cPersonnage en terme de fiche défaut pour l'application, fiche défaut pour le projet et fiche sur mesure pour une instance

Classe cProjet contient les renseignements tel que son nom, son auteur (instance de cPersonnage) et les id racines pour les cNote personnages, lieu, etc

utilisation de JSON :
- 1 tableau global en mémoire pour tout contenir
- Niveau 1 : nom (ex: donnees)
- Niveau 2 : type d'entitée (ex donnees['personnage'])
- Niveau 3 : ID (donnees['personnage'][0])
- Niveau 4 : données de l'entitée (ex: donnees['personnage'][0][id_precedent/id_suivant/ids_lies[]/nom/prenom/sexe)
  * Explication du niveau 4 :
    - id_precedent (défaut, c'est à dire que cet index fait partie de -toutes- les entitées, peu importe ce qu'elle sont) : le ID de l'entitée crée et/ou classée avant celle-ci, sert principalement pour des entitées de type "notes"
    - id_suivant (défaut) : idem que precedent mais suivant
    - ids_liees[] (defaut) : les IDs d'autres entitees liees a celle-ci, plus pour les notes qui font references à d'autres notes mais n'exclu pas un personnage qui pointe un autre personnage comme un ami ou un membre de famille
    - nom, prenom, sexe : champs que l'utilisateur peux modifier, renommer, ajouter ou supprimer (structure de ce type d'entitée) mais surtout renseigner (ce qui fait partie de l'identitée de l'entitée, le texte qui intéresse réellement l'usager); ils font partie de l'identitée de l'entitée, que ce soit un personnage, un lieu ou une note

== EOF ==

createur-romans
===============

Outils web local de création de romans, nouvelle, mémoire ou autre demandant une organisation assez simple des informations et des liens entre elles.

Projet:

1. Faire un plan (cette section);
2. Élaborer le but de l'application dans les grandes lignes;
3. Lister les fonctionnalités, les possibilitées, les outils;
4. Écrire un manuel, comment créer un projet, quels types de projets il est possible de gérer, comment créer chaque type d'idées (personnages, lieux, note, etc), comment lier les divers items ensemble, etc, etc...
5. Scinder les idées en concepts/pseudo-code;

=Partie #2 (incomplet)
Cette application as pour but d'être primairement un outils pour l'écriture de romans, avec la gestion des diverses parties de celui-ci tel que: notes, résumé/synopsis, personnages, lieux, chapitres, sections, nom de l'oeuvre, fiche de l'auteur, etc. L'outil pourra aussi être utilisé pour d'autres types tel que prise de notes avec liens entre elles, mémoire (pour autant que je sache, c'est comme un roman mais sans personnages, en terme de chapitre/sections); pour les types de documents autres que roman, un personnage "moi" et un lieu "ici" pourraient être créés si l'application ne peux fonctionner sans cela.

=Partie #3 (incomplet)
-L'application utilisera une base de données et des fichiers locaux à l'ordinateur de l'usager, il nécessitera l'installation d'un serveur Apache ou IIS tel que WAMP. L'application sera donc à toutes fin pratique "offline" pour protèger la propriété intellectuelle de l'auteur. Le principe est difficile à accepter en 2014 mais certains pourraient l'apprécier. De plus, l'application sera livrée avec des instructions et s'installera semi-automatiquement, l'installation du serveur Apache/IIS et la copie de l'archive décompressée dans le bon répertoire sera la responsabilité de l'usager. Toutes sauvegarde de sécurité, autre qu'un éventuel mécanisme de suivi de changement/version sera lui-aussi à la charge de l'usager. L'application pourra s'initialiser en créant la base de données, l'usager et les tables nécessaire.
-bibliothèque scindée en: liens (ouvre le fureteur système), moodboard, images, extraits/citations, etc. L'idée de la bibliothèque est d'avoir des ressources qui ne font pas partie d'un quelconque projet/document. Des sources d'inspiration.
-gestionnaire (ajout/modification/suppression) de personnages, de lieux, de notes, de document/projet (roman, mémoire), chapitres (autant les nommer que pouvoir les réordonner).
-Le projet NUL sera créé par défaut dès qu'on initialise l'application durant son installation ou qu'on la lance pour la première fois, servira aussi pour tests avant d'avoir tout les outils de gestion des projets.
-Au début du développement, quand on crée une entitée (lieu/personnage/note) elle fait partie du projet NUL, plus tard (dans le développement) toute nouvelle entité sera attaché au projet présentement ouvert OU si on as pas encore ouvert de projets, attachée au projet NUL. C'est à dire que j'aurai absolument besoin d'un projet auquel rattacher une entitée.
-Assistant de création ePub?
-J'aimerai avoir des panneaux pour les types/entitées créées, s'il n'y as pas au moins un élément d'un type, le panneau de ce type n'apparaît pas : personnages, lieux, notes; ils sont placés à droite de l'interface et s'étendent vers la gauche quand on clique leur titre en poussant ceux qui le précèdent (ex: ordre est "perso, lieux, notes" et on clique "lieux" alors en grandissant, "lieux" pousse "personnages") et en révélant leur contenu qui devient disponible/cliquable. 
-que ce soit dans une note, une fiche personnage, un lieu ou le document lui même, si le logiciel trouve un nom de personnage/lieu connu ou un numéro de note, il insère un lien vers la fiche (note, lieu, personnage) correspondante.
-les notes auront par défaut un numéro que l'on pourra référencer par "#0000" dans le texte et qui insèrera (comme ci-dessus) un lien, il pourrait aussi être possible de les nommer par étiquette.
-fonction de recherche qui passe au travers du projet en cours et de la bibliothèque.
-ici un "lien" est une balise "A" pour les liens internes; si c'est un lien externe, il aurais une classe CSS pour le souligner et nécessite que l'insertion soit faite par les outils de l'application. 
 
=Partie #4 (à écrire)
Création d'un nouveau projet:
Sous le menu Fichier, sélectionnez Nouveau projet. Remplissez la fiche tel que le nom, le chemin (si autre que celui par défaut) et le type de projet. Pour attribuer un nouvel auteur, voyez la section Entitees ou cliquez [+], sinon sélectionnez l'un des auteurs dans le select. Entrez une courte description du projet puis cliquez sur [creer]. Prochaine etape, creer votre chef-d'oeuvre au fil de vos mots!


Menu Entitees
Auteurs : gestion, creation, effacement des auteurs; habituellement, il n'y aura que vous.
Nouvelle Note
Nouveau Personnage
Nouveau Lieu

 
=Partie #5 (incomplet)
L'application web sera partagée entre une BD MySQL pour accélérer certaines parties (comme les liens entre les blocs de données; un bloc défini comme personnage, note, texte) et la gestion de fichiers XML qui contiendront principalement les blocs de texte plus long que 64 caractères.

À l'installation, scénario plausible : 
-l'usager arrive sur un "index.php" lequel pose quelques questions comme le nom par lequel il veux être adressé par l'application, la type de projet par défaut, le thème d'affichage (CSS, couleurs, etc) par défaut pour un nouveau projet, la façon de proposer l'ouverture d'un projet existant tel que par le menu ou des tuiles (qui seront remplacées par l'interface après le chargement), mot de passe 'root' de MySQL (si changé), etc.
-à l'envois, un second fichier initialise toutes les parties BD/XML/Config, les défauts tels que le projet 'NUL' et une oeuvre exemple, et quand tout vas et fini, il écrase le "index.php" pour ensuite renommer "index.prod.php" (préexistant) en "index.php" et le redirige là.
-L'application est installée et prête à travailler.

Classe cNote qui contient un membre privé pour son texte, un membre privé pour son ID, un membre "array of integers" pour retenir ses liens dont l'index 0 est l'id de son précédent (ou 0 s'il est le premier du projet), l'index 1 est l'id de son suivant (ou 0 s'il est le dernier du projet) et le reste sont les ids vers lesquels il pointe tel que d'autres cNote qui selon l'usager, auront une pertinence comme je le fait dans "O5".

Classe cPersonnage enfant de cNote, traite son membre texte comme une suite de renseignements séparés par un ';' ou autre de telle facon à garder les renseignements voulu par l'utilisateur, ex:un usager mettrais l'éducation et un autre les talents. À l'installation de l'application, un nouveau cPersonnage aura une série de défauts plausibles tel que nom, âge et sexe mais en laissant le choix de les effacer ou en ajouter autant pour l'instance en cours, le projet en cours que vers un (nouveau) cPersonnage modèle (remplace celui créé par l'installation).

Classe cLieu enfant de cNote, avec des possibilités semblables à cPersonnage en terme de fiche défaut pour l'application, fiche défaut pour le projet et fiche sur mesure pour une instance

Classe cProjet contient les renseignements tel que son nom, son auteur (instance de cPersonnage) et les id racines pour les cNote personnages, lieu, etc

== EOF ==

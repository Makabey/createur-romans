#Assistant créateur de romans

##Le nom est poche!
Oui, il faut trouver autre chose...

##Qu'est-ce que c'est?
Le but primaire de cette application est d'être un outils pour l'écriture de romans (ou autre document demandant une organisation assez simple des informations et des liens entre elles); avec la gestion des diverses parties de celui-ci tel que: nom de l'oeuvre, fiche de l'auteur, résumé/synopsis, notes, personnages, lieux, chapitres, sections, etc.

L'outil pourrais probablement être aussi utilisé pour d'autres types de documents tel que prise de notes avec liens entre elles, mémoires, scénario de métrages ou pièce de théâtre.

##Quelle forme prendra l'outils?
Ce sera une application web (utilisant un serveur Apache local, tel que [sous Windows] WAMP ou EasyPHP) locale. Pourquoi locale?

1. Simplement parce qu'avec le "**Cloud**" il y as possibilité que le serveur soit compromis ou disparaisse;
2. Avec un contenu publique (fonctionnalitée à venir et dont l'utilisation/l'activation sera optionnelle pour l'usager, *si et quand* un module de support "**Cloud**" serais ajouté), si vous avez une bonne idée d'histoire, vous risquez de vous faire plagier;
3. Par paranoia et volontée de contrôler totalement vos données;

Il n'est pas impossible qu'une fonctionnalitée de sauvegarde ("*backup*") vers le "**Cloud**" soit ajoutée beaucoup plus tard, et encore plus tard une fonctionnalitée de publication sur le site hébergeant l'application (mais il serait en fait préférable d'utiliser la fonctionnalitée de publication "*ePub*" à la place).

##Quelles seront les fonctionnalitées?

##L'application supportera la création d'entitées telles que :

1. Notes
2. Personnages
3. Lieux/véhicules de très grande taille (vaisseaux spaciaux, navires tel porte-avions, avions, etc)
4. Objets/machines/véhicules de moyenne à petite taille (véhicule utilitaire sport, trottinette, etc)
5. Chapitres
6. Oeuvres(roman, nouvelle, mémoire)


##Gabarit des entitées :
1. Des gabarits pour chaque type d'entitées seront fournis;
2. Ceux-ci seront modifiables pour refléter les besoins de l'auteur, tel qu'un besoin chez l'un de spécifier l'origine ethnique de chaques personnages au travers d'un choix (plutôt que dans la description générale) ou chez un autre auteur la préférence de mode de vie alimentaire (omnivore, végétaRien, végétaLien, etc);
3. Il sera possible de modifier les gabarits par défaut de l'application;
4. Il sera aussi possible de créer des gabarits spécifiques au projet chargé (à neuf ou à partir de ceux de l'application);

##Liaison entre les entitées (notes, personnages, etc) :
Les entitées pourront êtres liées entre elles (automatiquement par l'application ou manuellement), surtout les notes qui pourraient se référencer l'une-l'autre et/ou référencer un personnage et/ou un lieu (et/ou toute autre entitées);

Par exemple, une note nomme 1 lieu, 2 personnages et fait référence (pour rappel à l'auteur) à 4 autres notes qui iraient, dans l'ordre chronologique de lecture de l'oeuvre et selon ces notes elles-même, autant en aval qu'en amont de la présente note (ou transversalement pour les personnages et lieux). L'ordre des entitées-notes sera dictée par le lien direct (par opposition à discret, c'est à dire la mécanique de référencement) entre elles et non par leur ordre numérologique ou de création. Ex: les notes #23, 493 et 129 sont en ordre pour raconter l'histoire mais elles ont été créée dans l'ordre chronologique de 23, 129 et 493 (avec tout les numéros entre) puis réordonnées. Des notes pourront être liées à un chapitre ou un paragraphe afin de permettre à l'auteur de se rappeller les notes qui ont été réellement utilisées et à quel moment du récit.


##### tout ce qui suit n'as pas été traité...

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
 

(...)

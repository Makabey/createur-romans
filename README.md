# Assistant de création de romans (Ver 0.1.0)

## Qu'est-ce que c'est ?
Un assistant là pour vous aider à effacer le malaise de la page blanche! Il est à même de suggèrer une structure narrative à votre histoire; choisissez le genre, répondez aux questions et vous aurez un synopsis sur lequel bâtir votre histoire. Il offre aussi des outils de gestion des personnages, des lieux et des mémos que vous génèrerez assurément en cours d'écriture.

## [Présentation disponible en format Prezi (lien externe, nécessite Adobe Flash)](http://prezi.com/dgath3ouob29/et-scribimus/)

## Quelles en sont les fonctionnalitées ?
* Un assistant de création de synopsis basé sur les genres littéraires, pour vous inspirer;
* Des fiches pour chacunes des questions : Qui, Quoi, Où, Pourquoi, Comment, Quand;
* Un nombre de fiches illimitées;
<!--
* ~~Des gabarits pour chaque type d'entitées (personnages, lieux, mémos), même une section "autres" pour ces autres entitées possibles tel que Bureau, Avion ou Animaux;~~
* ~~Liaison entre les entitées, il suffit de les nommer dans leur texte, par exemple, nommer le personnage A dans la fiche du personnage B mettra un lien vers ce premier;~~
* ~~Un "moodboard" où vous pourrez épingler ces sources d'inspiration qui vous tiennent à coeur);~~
* ~~Assistant de création ePub et PDF;~~
* ~~Une fonction de recherche, pour retrouver ces passages ou mentions de personnages que vous croyez avoir écris;~~
-->
* ... Et plus à venir!

## Tailles et limites
### Y as-t'il une limite de nombre et/ou de taille de romans ?
Non

### Y as-t'il une limite de nombre d'entitées ?
Pas pour le moment

### Le type doit-t'il impérativement être un roman ?
En fait non; pour pouvez aussi écrire d'autres type de document

### Sous quelle licence est-ce que mon oeuvre existe ou peut/doit être distribuée et/ou publiée ?
Sous celle que vous voulez. La présente application ne sert que d'assistant. La totalité du texte de l'oeuvre, des notes et des fiches vous appartiennent intégralement. Nous n'avons aucun lien, accord ou partenaria avec une compagnie de publication sous quelque forme que ce soit.

### L'application sera t'elle disponible dans d'autres langues ?
Non, pas pour le moment.

### L'application est t'elle payante ?
Pas pour le futur proche.

## Installation
* Installez un serveur web, sous Windows vous avez l'embarras du choix (Wamp, Xamp, Easy-php, etc...); pour Linux la pile LAMP(Apache) ou LEMP(nGinx);
* Importez "createurromans.sql", la base de données est nommée 'createurromans';
* Modifiez le fichier "/createur-romans/inc/db_access.inc.php" si nécessaire;
* Il y as deux usagers 'démo' :

Usager | Mot de passe | Utilité
------ | ------------ | -------
admin | adminadmin | un administrateur pour démontrer le choix menu administratif
usager1 | motdepasse | un usager ordinaire avec l'ébauche d'un roman


## Avertissement !
* Le présent projet en était un collaboratif pour l'obtention d'un diplôme, par conséquent certains aspects n'ont pas été correctement implémenté, faute de connaissance, d'expérience et de temps;
* Les mots de passe ne sont pas chiffrés;
* Beaucoup des buts initiaux n'ont pas été implémentés;
* Le code est loin d'être optimisé;

<!--
ToDo :
bogues :
- changer les données d'un usager, ex: son nom/prénom, ne fonctionne pas réellement.
- revoir tout le code pour améliorer ;p

- http://www.webmproject.org/tools/
- http://monochrome.sutic.nu/2010/06/14/video-editing-with-blender.html#section.10.4
- TOUCH :: http://www.html5rocks.com/fr/mobile/touch/

16. [DB, AJAX] Passer en tout ou en partie les lectures en AJAX, ex: dans le catalogue, quand on clique une section {tout, puzzle, etc}, on remplace le contenu du array que PHP charge avec AJAX mais on ne fait rien (de différent) quand on clique les boutons prev/next jouet
17. [DB] Passer de CSV à MySQLi
18. [DB, AJAX] Passer la page d'ajouts des produits à AJAX pour lecture/écriture, l'exeption initiale est le chargement par PHP de la liste des items connus
19. [PHP] Pousser les fonctions qui s'occupent des items vers une/des classes?
20. [PHP] Pousser le panier plus loin pour qu'il sépare et reconnaisse les couleurs de jouets de façon à pouvoir commander le même en plusieurs couleurs?
21. [PHP, JS] Quand on ajoute un item au panier, qu'il y en as déjà ET que c'est la même couleur, demander si on doit ajouter à la quantité ou remplacer la quantité? Ou alors par défaut on ajoute ET on l'indique en haut de la page pour que le client sache ce qu'il s'est passé, le faire même pour un nouvel ajout/item
25. empêcher l'usager de retourner au panier quand on as cliqué "Payer" (et "vider panier" ?), voir :: https://developer.mozilla.org/en-US/docs/Web/API/Location.replace
-->

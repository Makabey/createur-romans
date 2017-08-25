# Assistant de création de romans (Ver 0.1.0)

## Qu'est-ce que c'est?
Un assistant là pour vous aider à effacer le malaise de la page blanche! Il est à même de suggèrer une structure narrative à votre histoire; choisissez le genre, répondez aux questions et vous aurez un synopsis sur lequel bâtir votre histoire. Il offre aussi des outils de gestion des personnages, des lieux et des mémos que vous génèrerez assurément en cours d'écriture.

## [Présentation Prezi (lien externe, nécessite Adobe Flash)] (http://prezi.com/dgath3ouob29/et-scribimus/?utm_campaign=share&utm_medium=copy)

## Quelles en sont les fonctionnalitées?
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
### Y as-t'il une limite de nombre et/ou de taille de romans?
Non

### Y as-t'il une limite de nombre d'entitées?
Pas pour le moment

### Le type doit-t'il impérativement être un roman?
En fait non; pour pouvez aussi écrire d'autres type de document

### Sous quelle licence est-ce que mon oeuvre existe ou peut/doit être distribuée et/ou publiée?
Sous celle que vous voulez. La présente application ne sert que d'assistant. La totalité du texte de l'oeuvre, des notes et des fiches vous appartiennent intégralement. Nous n'avons aucun lien, accord ou partenaria avec une compagnie de publication sous quelque forme que ce soit.

### L'application sera t'elle disponible dans d'autres langues?
Non, pas pour le moment.

### L'application est t'elle payante?
Pas pour le futur proche.

## Installation
* Installez un serveur web, sous Windows vous avez l'embarras du choix (Wamp, Xamp, Easy-php, etc...); pour Linux la pile LAMP(Apache) ou LEMP(nGinx);
* Importez "createurromans.sql", la base de données est nommée 'createurromans';
* Modifiez le fichier "/createur-romans/inc/db_access.inc.php" si nécessaire;
* Il y as deux usagers 'démo' :
** 'admin', mot de passe 'adminadmin';
** 'usager1', mot de passe 'motdepasse';

## Avertissement !
* Le présent projet en était un collaboratif pour l'obtention d'un diplôme, par conséquent certains aspects n'ont pas été correctement implémenté, faute de connaissance, d'expérience et de temps;
* Les mots de passe ne sont pas chiffrés;
* Beaucoup des buts initiaux n'ont pas été implémentés;
* Le code est loin d'être optimisé;

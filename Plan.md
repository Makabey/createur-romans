To Do ::  décrire d'abord l'interface, puis les menus et enfin le fonctionnement interne (JS, PHP/Classes, MySQL, etc). Voler du ReadMe et de la réserve de texte le max de renseignements pour composer ici

structure des répertoires:
- racine:
  * .htaccess
  * index.php
  * !lisezMoi.txt -> ce qu'est l'application, comment la lancer, ce qui est nécessaire de faire
  * install.bat, init_db.sql, config.ini
  * pages (répertoires)
    - les fichiers pages autre que index.php
  * js (répertoires)
    - le javascript
  * css (répertoires)
    - les feuilles CSS
  * inc (répertoires)
    - les fichiers PHP qui sont des "includes
    - les fichiers PHP appellés par JS/AJAX, réfléchir s'ils ne devraient pas avoir leur propre répertoire....
  * media (répertoires)
    - Audio/Video nécessaire
  * images (répertoires)
  * xml
    - app(répertoire) : les fichiers par défaut fournit avec l'application
    - usager (répertoire) : les fichiers de l'usager avec des noms probablement comme &lt;nomDeLUsager_NomProjet_NomEntitée_dateTime&gt;

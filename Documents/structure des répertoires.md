#Structure et emplacement suggéré des fichiers

- racine:
  * .htaccess
  * index.php
  * pages (répertoire)
    - les fichiers pages autre que index.php
  * js (répertoire)
    - le javascript
    - JSON (sous-répertoire), les fichiers de données
  * css (répertoire)
    - les feuilles CSS
  * inc (répertoires)
    - les fichiers PHP qui sont des "includes"
  * media (répertoire)
    - Audio/Video, si nécessaire
  * images (répertoire)
    - logos (sous-répertoire)
  * xhr
    - les fichiers PHP appellés par JS/AJAX et le fichier JS contenant les fonctions liées ("xhrFunctions.js" du projet PHP)
  * usagers (répertoire)
    - &lt;Usager&gt; (répertoire) L'usager, même s'il n'y en as qu'un seul, devrais contenir les documents puisque jugés trop gros pour mettre dans la BD

== EOF ==
#Structure et emplacement suggéré des fichiers

- racine:
  * .htaccess
  * index.php
  * !lisezMoi.txt -> ce qu'est l'application, comment l'installer et la lancer
  * install.bat, init_db.sql, config.ini
  * pages (répertoires)
    - les fichiers pages autre que index.php
  * js (répertoires)
    - le javascript
    - JSON (répertoire), les fichiers données, probablement plus facile à utiliser que le XML
  * css (répertoires)
    - les feuilles CSS
  * inc (répertoires)
    - les fichiers PHP qui sont des "includes"
  * media (répertoires)
    - Audio/Video nécessaire
  * images (répertoires)
  * xhr
    - les fichiers PHP appellés par JS/AJAX et le fichier JS contenant les fonctions liées
  * xml
    - les fichiers par défaut fournit avec l'application
  * usagers (répertoire)
    - &lt;Usager&gt; (répertoire) L'usager, même s'il n'y en as qu'un seul
      * &lt;projet&gt; (répertoire)
        - xml  (répertoire) les fichiers de l'usager (contenu, entitées, etc) avec des noms probablement comme &lt;NomEntitée_dateTime&gt;
        - json idem que xml, la raison de la nécessité est au cas où je décide d'utiliser JSON en remplacement de XML, c'est vraiment pour le moment une question de prévoir le coup.

<?php
$sPageTitle = "(demo) mode Édition | ";

include "../assets/inc/header.inc.php";
?>
<form id="form_question" method="post" action="index.php"></form>
<p id="balise_attendez"><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." />Récupération du document...</p>
<textarea id="main_write" form="form_question" cols="80" rows="20"></textarea>
<button type="button" id="btn_save" form="form_question">Sauvegarder</button>
<div id="temoin_activite"></div>
<button type="button" id="btn_lireEntites" form="form_question">Lire Entitées</button>
<button type="button" id="btn_saveEntite" form="form_question">INSERT Entitées</button>
<button type="button" id="btn_updEntite" form="form_question">UPDATE Entitées</button>
<div id="container_entites"></div>
<?php
include "../assets/inc/footer.inc.php"
?>

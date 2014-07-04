
<script>
var x = JSON.parse('<?php
$_POST['etape']= 'lireQuestions';
$_POST['genre'] = 'Policier';

include "assets/xhr/assistant_creation.xhr.php";

?>');
console.log (x);

//x = JSON.parse(x);
//console.log (x);
</script>
<?php
include "../fichiersDuProjet/assets/inc/library01.inc.php";


$tableau = array("valeur1" => "rien", "valeur2" => "kekshode", "clue1" => "blaks", "pfft" => "chaud", "Valeur3" => "kekshode2");

$t2 = array_keys_like($tableau, "valeur");

var_dump($t2);

var_dump(array_keys_like($tableau, "Valeur", false, true));

var_dump(array_keys_like($tableau, "ff", true));
<?php
session_start();

unset($_SESSION['usager']);

header("Location:/fichiersDuProjet/index.php");
exit();

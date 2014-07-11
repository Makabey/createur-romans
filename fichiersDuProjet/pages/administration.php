<?php
$sPageTitle = "Administration | ";

require_once "../assets/inc/db_access.inc.php";
require_once "../assets/inc/library01.inc.php";
require "../assets/inc/header.inc.php";

$db = db_connect();

if(!is_object($db)){
	// On suppose ici que $db contient une erreur texte et non un objet
	echo $db;
	exit();
}

if(isset($_GET['oper'])){
	$_GET['state'] = ($_GET['state'] == 'true')?1:0;
	if($_GET['oper'] == 'user'){
		$query = "UPDATE `usagers` SET `deleted` = {$_GET['state']} WHERE `ID_usager` = {$_GET['id'] };";
	}else{
		$query = "UPDATE `roman_details` SET `deleted` = {$_GET['state']} WHERE `ID_roman` = {$_GET['id'] };";
	}
	$resultat = $db->query ($query);
	//echo $query, PHP_EOL;
}

echo "<h1>Page d'Administration</h1>", PHP_EOL;

$query = "SELECT `usagers`.`ID_usager`, `usagers`.`pseudo`, `usagers`.`deleted`, `roman_details`.`titre`, `roman_details`.`date_creation`, `roman_details`.`deleted`, `genres_litteraires_noms`.`nom`, `roman_details`.`ID_roman`, `usagers`.`est_admin` ";
$query .= "FROM `usagers`, `roman_details`, `genres_litteraires_noms` WHERE `genres_litteraires_noms`.`ID_genre` = `roman_details`.`ID_genre` ";
$query .= "AND `usagers`.`ID_usager` = `roman_details`.`ID_usager` ";
$query .= "ORDER BY `usagers`.`pseudo`;";

$resultat = $db->query ($query);

if(false !== $resultat){
	$num_rows = $resultat->num_rows;
	if($num_rows){
		$dnrUsager = '';
		
		while ($row = $resultat->fetch_row()){
			if($dnrUsager != $row[1]){
				if($dnrUsager != ''){
					echo '</table>', PHP_EOL, PHP_EOL;
				}
				echo "<table data-idusager=\"{$row[0]}\">", PHP_EOL, "<tr><th><button type=\"button\" class=\"";
				if($row[8]){
					echo 'adminUser';
				}else{
					echo ($row[2] == 0)?'active':'inactive';
				}
				echo "\" data-usrblacklisted=\"{$row[2]}\">";
				echo ($row[2] == 0)?'Couper':'Restaurer';
				echo " l'accès de {$row[1]}</button></th>";
				echo "<th>Titre</th><th>Genre</th><th>Date de création</th><th>Changer l'état</th></tr>", PHP_EOL;
				$dnrUsager = $row[1];
			}
			echo "<tr><td>&nbsp;</td><td>{$row[3]}</td><td>{$row[6]}</td><td>{$row[4]}</td><td data-idroman=\"{$row[7]}\"><button type=\"button\" class=\"";
			echo ($row[5] == 0)?'active':'inactive';
			echo "\" data-noveldeleted=\"{$row[5]}\">";
			echo ($row[5] == 0)?'Effacer':'Restaurer';
			echo '</button></td></tr>', PHP_EOL;
		}
		echo '</table>', PHP_EOL, PHP_EOL;
	}else{
		echo '<p>Aucune données.</p>';
	}
}else{
	echo '<p>Une erreur est survenue lors de la lecture des données.</p><p>Erreur : ', $db->error , '</p>';
}

require "../assets/inc/footer.inc.php"
?>


	</div>
	<footer>
		<div id="footer">
		 <div class="container">
			<p class="text-muted">Création: Eric Robert, Thomas A. Séguin et Olivier Berthier</p>
		 </div>
		</div>
	</footer>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="<?=$rootDomaine?>xhr/xhrFunctions.js"></script>
	<script src="<?=$rootDomaine?>js/functions.js"></script>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script>
		"use strict";
		/* Variables nécessaires pour le fichier JS qui suit, si applicable */
		<?php
		// Variables générées par PHP pour JS
		if($sNomDeCettePage != 'index'){
			if(isset($_SESSION['pseudo'])){
				$pseudo = $_SESSION['pseudo'];
				$idRoman = (isset($_SESSION[$pseudo]['idRoman']))?$_SESSION[$pseudo]['idRoman']:-1;

				echo "var idUsager = {$_SESSION[$pseudo]['idUsager']};", PHP_EOL;
				echo "var idRoman = $idRoman;", PHP_EOL;
			}
		}
		?>
	</script>
	<!-- Fichier JS spécifique à la page -->
	<?php
		$cheminFichier = $rootDomaine . 'js/'.$sNomDeCettePage.'.js';
		echo '<script src="', $cheminFichier, '"></script>', PHP_EOL;
	?>
 </body>
</html>
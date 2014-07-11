
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
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="<?php echo $rootDomaine; ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo $rootDomaine; ?>assets/xhr/xhrFunctions.js"></script>
	<script src="<?php echo $rootDomaine; ?>assets/js/functions.js"></script>
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
		$cheminFichier = $rootDomaine . 'assets/js/'.$sNomDeCettePage.'.js';
		echo '<script src="', $cheminFichier, '"></script>', PHP_EOL;
	?>
 </body>
</html>
	
	</div>
	<footer role="footer">
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
		/*window.addEventListener("load", function(){ // J'utilise un listener pour éviter de marcher sur les platebandes de jQuery
			// mettre ici le code qui pourrait servir à plusieurs pages.
		});*/
		/* Variables nécessaires pour le fichier JS qui suit, si applicable */
		<?php
		// Variables générées par PHP pour JS
		/*switch($sNomDeCettePage){
			case 'index':
				break;
		}*/
		if($sNomDeCettePage != 'index'){
			if(isset($_SESSION['usager'])){
				$usager = $_SESSION['usager'];
				$idRoman = (isset($_SESSION[$usager]['idRoman']))?$_SESSION[$usager]['idRoman']:0;
				echo "var idUsager = $usager;", PHP_EOL;
				
				if(isset($_GET['idRoman']))$idRoman = $_GET['idRoman']; // pour tests seulement
				
				echo "var idRoman = $idRoman;", PHP_EOL;
			#}else{
			#	echo 'document.location.href="' . $rootDomaine . 'index.php"'; // Si $_SESSION['usager'] ne contient rien, c'est qu'on essaie d'accèder à une page sans se logguer
			# en principe, la ligne précédente est redondante, il y as un truc semblable dans le header
			}
		}
		?>
	</script>
	<!-- Fichier JS spécifique à la page -->
	<?php
		if(file_exists($rootDomaine . 'assets/js/'.$sNomDeCettePage.'.js')){
			echo '<script src="' . $rootDomaine . 'assets/js/',$sNomDeCettePage,'.js"></script>',PHP_EOL;
		}
	?>
  </body>
</html>
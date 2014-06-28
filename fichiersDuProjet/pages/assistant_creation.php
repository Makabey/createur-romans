<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!--Custom-style-->
    <link href="../assets/css/custom-style.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
   <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <div class="starter-template">
        <h1>Assistant à la structuration d'un roman</h1>
		<h2></h2>
        <p class="lead">Choisissez une valeur parmis les suggestions</p>
      </div>
      <div class="form">
        <div class="form-inner">
          <!--<fieldset>
            <div class="first-input">
              <label for="crime-delit">Choisir un crime ou entrer une valeur</label>
              <input list="crime-delit">
              <datalist id="crime-delit">
                <option value="Meurtre">
                <option value="Vol">
                <option value="Viol">
                <option value="Enlevement">
                <option value="Chantage">
                <option value="Torture">
                <option value="Terrorisme">
                <option value="Conspiration">
              </datalist>
              <textarea placeholder="entrer une courte descrition"></textarea>
        </div><!- -first-input- ->
        <div class="second-input">
            <label for="mobile">Choisir un mobile ou entrer une valeur</label>
            <input list="mobile">
            <datalist id="mobile">
              <option value="passionnel">
              <option value="Vengeance">
              <option value="Folie">
              <option value="Financier">
              <option value="Rançon">
              <option value="Ideologique">
              <option value="Survie">
            </datalist>
            <textarea placeholder="entrer une courte descrition"></textarea>
        </div><!- -second-input- -> 
        <div class="third-input">
             <label for="coupable">Choisir un couplable ou entrer une valeur</label>
            <input list="coupable">
            <datalist id="coupable">
              <option value="psychopathe">
              <option value="tueur en série">
              <option value="terroriste">
              <option value="monsieur tout le monde">
              <option value="femme fatale">
              <option value="tueur à gage">
              <option value="victime d'un complot">
            </datalist>
            <textarea placeholder="entrer une courte descrition"></textarea>
        </div><!- -third-input- ->
        <div class="fourth-input">
          <label for="victime">Choisir une victime ou entrer une valeur</label>
            <input list="victime">
            <datalist id="victime">
              <option value="homme">
              <option value="femme">
              <option value="enfant">
              <option value="groupe">
              <option value="animal">
            </datalist>
            <textarea placeholder="entrer une courte descrition"></textarea>
          </div><!- -fourth-input- ->
         
          </fieldset>-->
		  	<p id="waitP"><img src="../assets/images/wait_circle2.png" class="waitCircle" alt="Attendez..." /><span></span></p>
			<form id="form_question" method="post" action="index.php" autocomplete="off">
				<fieldset></fieldset>
			</form>
			<button id="button_nextQuestion" form="form_question" type="submit">Suivant</button>
        </div>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/xhr/xhrFunctions.js"></script>
	<script src="../assets/js/assistant_creation.js"></script>
	<script src="../assets/js/functions.js"></script>
	<script>
		$(function(){
			// Au lancement de la page, tout de suite charger les genres littéraires...
			afficherAttente();
			lireGenresLitteraires_Noms(afficherGenres, traiterErreurs);
		});
	</script>
  </body>
</html>
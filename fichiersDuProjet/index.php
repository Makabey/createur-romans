
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">

    <title>Créateur Roman</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
	

    <!-- Custom css -->
    <link href="assets/css/theme.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

	 <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="assets/images/logo.png" alt="créateur roman, bienvenue" />Créateur Roman</a>
        </div>
        <div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Identifiant" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Mot de passe" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Connexion</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <div class="container">
		<div class="titre">
			<h1>Créateur Roman</h1>
			<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
			<p><a href="#" class="btn btn-primary btn-lg" role="button">En savoir plus</a></p>
			<div class="video">
				<video width="100%" height="370">
				  <source src="movie.mp4" type="video/mp4">
				  <source src="movie.ogg" type="video/ogg">
				Your browser does not support the video tag.
				</video>
			</div>
		</div>
		
		
		<form class="form-signin" role="form">
			<h2 class="form-signin-heading">S'inscrire</h2>
			<p>C'est rapide et gratuis !</p>
			<input type="user" class="form-control" placeholder="Choisir un identifiant" required>
			<input type="email" class="form-control" placeholder="Votre courriel" required>
			<input type="password" class="form-control" placeholder="Créer un mot de passe" required>
			<input type="password" class="form-control" placeholder="Confirmer le mot de passe" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit">S'inscrire</button>
		</form>
    </div>
	
	<div id="footer">
      <div class="container">
        <p class="text-muted">Création: Éric Robert, Thomas A. Séguin et Olivier Berthier</p>
      </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>

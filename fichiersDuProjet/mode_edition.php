
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">

    <title>Créateur Roman | Édition d'un roman</title>

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

  <body class="mode_edition">

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
		
		<!-- CONNEXION -->
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
		<!--/CONNEXION -->
		
      </div>
    </div>

    <div class="container">
		<input type="text" value="Titre de l'oeuvre" class="titre-roman form-control" />
		<div class="col-md-8 custom-box-content">
			<ul class="nav nav-tabs custom-ul">
			  <li class="active"><a href="#">Composition</a></li>
			  <li class=""><a href="#">Notes</a></li>
			</ul>
			<div class="wrap-composition">
				<textarea name="composition" class=""></textarea>
			</div>
		</div>
		<div class="col-md-4 custom-box-content">
			<ul class="nav nav-tabs custom-ul">
				<li class="active"><a href="#">Qui</a></li>
				<li class=""><a href="#">Quoi</a></li>
				<li class=""><a href="#">Où</a></li>
				<li class=""><a href="#">Quand</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Délit <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li class=""><a href="#">Comment</a></li>
						<li class=""><a href="#">Pourquoi</a></li>
					</ul>
				</li>
			</ul>
			<div class="wrap-aide-memoire">
				<span class="en-tete-aide-memoire">Vos personnages</span>
				<div class="aide-memoire">
					<div class="aide-memoire-headings">
						<span>En-tête #1</span>
					</div>
					<div class="aide-memoire-content">
						<span>
							Description #1 <br>
							Il faudra mettre une limite de texte pour les discriptions... Ex: 200 mots<br><br>
							Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
						</span>
					</div>
				</div>
				<div class="aide-memoire">
					<div class="aide-memoire-headings">
						<span>En-tête #2</span>
					</div>
					<div class="aide-memoire-content">
						<span>
							Description #2 <br>
							Il faudra mettre une limite de texte pour les discriptions... Ex: 200 mots<br><br>
							Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
						</span>
					</div>
				</div>
				<div class="aide-memoire">
					<div class="aide-memoire-headings">
						<span>En-tête #3</span>
					</div>
					<div class="aide-memoire-content">
						<span>
							Description #3 <br>
							Il faudra mettre une limite de texte pour les discriptions... Ex: 200 mots<br><br>
							Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
						</span>
					</div>
				</div>
				<div class="aide-memoire">
					<div class="aide-memoire-headings">
						<span>En-tête #4</span>
					</div>
					<div class="aide-memoire-content">
						<span>
							Description #4 <br>
							Il faudra mettre une limite de texte pour les discriptions... Ex: 200 mots<br><br>
							Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
						</span>
					</div>
				</div>
				<div class="aide-memoire">
					<div class="aide-memoire-headings">
						<span>En-tête #5</span>
					</div>
					<div class="aide-memoire-content">
						<span>
							Description #5 <br>
							Il faudra mettre une limite de texte pour les discriptions... Ex: 200 mots<br><br>
							Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
						</span>
					</div>
				</div>
			</div>
		</div>
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

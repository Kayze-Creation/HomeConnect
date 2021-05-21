<?php
/***********************************************************************
 * Projet Communication Sans Fil
 * HomeConnect
 * ---------------------------------------------------------------------
 * Page de connexion
 * ---------------------------------------------------------------------
 * Crée par Adrien Vivaldi et Quentin Escobar
 * Version 1.0 
 * 
 **********************************************************************/

session_start(); // On démarre une session
require_once('Extensions/config.php'); // Connexion à la bdd
ini_set('display_errors', 'off'); // On retire l'affichage des erreurs si vrai

if(isset($_POST['formconnexion'])) 
{
  $mdpconnect = sha1($_POST['mdpconnect']);
  if (!empty($mdpconnect)) 
  {
    $reqlogin = $bdd->prepare("SELECT * FROM homeconnect_login WHERE password = ?");
    $reqlogin->execute(array($mdpconnect));
    $loginexist = $reqlogin->rowCount();
    if ($loginexist == 1) 
    {
      $logininfo = $reqlogin->fetch();
      $_SESSION['id'] = $logininfo['id'];
		  header("location: index.php?id=".$_SESSION['id']);
    }
    else
    {
      $erreur = "<b style=\"color:red;\">Le mot de passe est erroné !</b>";
    }
  }
  else
  {
	$erreur = "<b style=\"color:red;\">Tous les champs doivent être complétés !</b>";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title>HomeConnect - Connexion</title>
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
</head>

<body onload="getTime()">

  <div class="container">
    <div id="showtime"></div>
    <div class="col-lg-4 col-lg-offset-4">
      <div class="lock-screen">
        <h2><a data-toggle="modal" href="#myModal"><i class="fa fa-lock"></i></a></h2>
        <p ><b>déverrouiller</b></p>

        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Bienvenue</h4>
              </div>
              <form  method="post">
                <div class="modal-body">
                  <p class="centered"><img   width="80" src="img/logo.png"></p>
                  <input type="password" name="mdpconnect" placeholder="Mot de passe" autocomplete="off" class="form-control placeholder-no-fix">
                  <?=$erreur?>
                </div>
                <div class="modal-footer centered">
                  <button data-dismiss="modal" class="btn btn-theme04" type="button">Annuler</button>
                  <button class="btn btn-theme03" type="submit" name="formconnexion">Connexion</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="lib/jquery.backstretch.min.js"></script>
  <script>
    $.backstretch("img/home.jpg", {
      speed: 500
    });
  </script>
  <script>
    function getTime() {
      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m);
      s = checkTime(s);
      document.getElementById('showtime').innerHTML = h + ":" + m + ":" + s;
      t = setTimeout(function() {
        getTime()
      }, 500);
    }

    function checkTime(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
  </script>
</body>

</html>

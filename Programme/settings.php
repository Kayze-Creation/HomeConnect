<?php
/***********************************************************************
 * Projet Communication Sans Fil
 * HomeConnect
 * ---------------------------------------------------------------------
 * 
 * 
 * ---------------------------------------------------------------------
 * Crée par Adrien Vivaldi et Quentin Escobar
 * Version 1.0 
 * 
 **********************************************************************/

session_start();
require_once('Extensions/config.php'); // Connexion à la bdd
ini_set('display_errors', 'off'); // On retire l'affichage des erreurs si vrai

//****************************************************************************
if (isset($_GET['id']) AND $_GET['id'] > 0) 
{   
    $getid = intval($_GET['id']);
    $reqlogin = $bdd->prepare('SELECT * FROM homeconnect_login WHERE id = ?');
    $reqlogin->execute(array($getid));
    $logininfo = $reqlogin->fetch();

    if (isset($_SESSION['id']) AND $logininfo['id'] == $_SESSION['id']) {
//****************************************************************************
?>

<?php 
if (isset($_SESSION['id'])) 
{
  $reqlogin = $bdd->prepare("SELECT * FROM homeconnect_login WHERE id = ?");
	$reqlogin->execute(array($_SESSION['id']));
	$login = $reqlogin->fetch();

  if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']))
  {
    $mdp1 = sha1($_POST['newmdp1']);
    $mdp2 = sha1($_POST['newmdp2']);
    
    if ($mdp1 == $mdp2) 
    {
      $newmdplength = strlen($_POST['newmdp1']);  
      if ($newmdplength >= 3) 
      {
        $insertmdp = $bdd->prepare('UPDATE homeconnect_login SET password = ? WHERE id = ?');
        $insertmdp->execute(array($mdp1, $_SESSION['id']));
        //header('location: settings.php?id='.$_SESSION['id']);
        $msg = "<b style=\"color:green;\">Mot de passe mise à jour avec succès !<b>";
      }
      else
      {
        $msg = "<b style=\"color:red;\">Votre mot de passe doit contenir au moins 3 caractères !<b>";
      }
    }
    else
    {
    $msg = "<b style=\"color:red;\">Vos deux mots de passes ne correspondent pas !<b>";
    }
  }
}
?>

<?php // Récuperation des derniers données de la table homeconnect_data (données des capteurs)
$req_last_data = $bdd->query('SELECT * FROM homeconnect_data ORDER BY id DESC LIMIT 1');
$last_data = $req_last_data->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title>HomeConnect - paramètres</title>
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
</head>

<body>
  <section id="container">
    
    <?php include("Extensions/menu.php"); ?>

    <aside>
      <div id="sidebar" class="nav-collapse ">
        <ul class="sidebar-menu" id="nav-accordion">
          <li class="mt">
            <a href="index.php?id=<?=$_SESSION['id']?>">
              <i class="fa fa-dashboard"></i>
              <span>Tableau de bord</span>
              </a>
          </li>

          <li>
            <a href="temperature.php?id=<?=$_SESSION['id']?>">
              <i class="fa fa-fire"></i>
              <span>Température</span>
              <span class="label label-theme pull-right mail-info"><?=$last_data['temperature']?>°C</span>
              </a>
          </li>

          <li>
            <a href="humidite.php?id=<?=$_SESSION['id']?>">
              <i class="fa fa-tint"></i>
              <span>Humidité</span>
              <span class="label label-theme pull-right mail-info"><?=$last_data['humidity']?>%</span>
              </a>
          </li>

          <li>
            <a href="luminosite.php?id=<?=$_SESSION['id']?>">
              <i class="fa fa-adjust"></i>
              <span>Luminosité</span>
              <span class="label label-theme pull-right mail-info"><?=$last_data['luminosity']?> LUX</span>
              </a>
          </li>

          <li>
            <a href="notification.php?id=<?=$_SESSION['id']?>">
              <i class="fa fa-bell"></i>
              <span>Notifications</span>
              </a>
          </li>

        </ul>
      </div>
    </aside>

    <section id="main-content">
      <section class="wrapper site-min-height">
        <div class="row mt">
          <div class="col-lg-12">
            <div class="row content-panel">
              <div class="col-md-4 profile-text mt mb centered">
                <div class="right-divider hidden-sm hidden-xs">
                  <h4>Adrien Vivaldi</h4>
                  <h6>PHP + MySQL</h6>
                  <h4>Quentin Escobar</h4>
                  <h6>Arduino + TTN</h6>
                </div>
              </div>

              <div class="col-md-4 profile-text">
                <h3>HomeConnect</h3>
                <h6>Projet CSF</h6>
                <p>HomeConnect, notre projet a pour but de récupérer les informations telles que la température, 
                  l'humidité ou la luminosité d'une pièce afin de les analyser et de les utilisers pour de la domotique. 
                  Il possède un site web complet et un système de notifications via Discord pour être notifier directement depuis son smartphone.</p>
              </div>

              <div class="col-md-4 centered">
                <div class="profile-pic">
                  <p><img src="img/logo.png"></p>
                  <p>
                    <a href="https://github.com/Kayze-Creation/HomeConnect/tree/main/Programme" target="_BLANK">
                      <button class="btn btn-theme"><i class="fa fa-code"></i> code source</button>
                    </a>
                    <a href="https://github.com/Kayze-Creation/HomeConnect" target="_BLANK">
                      <button class="btn btn-theme02"><i class="fa fa-github"></i> GitHub</button>
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-12 mt">
            <div class="row content-panel">
              <div class="panel-heading">
                <ul class="nav nav-tabs nav-justified">
                  <li class="active">
                    <a data-toggle="tab" href="#overview">Présentation</a>
                  </li>
                  <li>
                    <a data-toggle="tab" href="#edit">Mot de passe</a>
                  </li>
                </ul>
              </div>

              <div class="panel-body">
                <div class="tab-content">
                  <div id="overview" class="tab-pane active">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="detailed mt">
                          <h4>Avancée du projet</h4>
                          <div class="recent-activity">
                            <div class="activity-icon bg-theme"><i class="fa fa-cloud"></i></div>
                            <div class="activity-panel">
                              <h5>4 MAI 2021</h5>
                              <p>Creation de la base de donnée et commencement du développement web.</p>
                            </div>
                            <div class="activity-icon bg-theme"><i class="fa fa-code"></i></div>
                            <div class="activity-panel">
                              <h5>3 MAI 2021</h5>
                              <p>Creation du module HTTP de TTN.<br>Creation du fichier PHP charger de recevoir les données de TTN.</p>
                            </div>
                            <div class="activity-icon bg-theme04"><i class="fa fa-rocket"></i></div>
                            <div class="activity-panel">
                              <h5>2 AVRIL 2021</h5>
                              <p>Commencement du projet !</p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6 detailed">
                        <h4>Information complémentaire</h4>
                        <div class="row centered mt mb">
                          <div class="col-sm-4">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>30,00 €</h3>
                            <h6>COÊT MOYEN DU PROJET</h6>
                          </div>
                          <div class="col-sm-4">
                            <h1><i class="fa fa-clock-o"></i></h1>
                            <h3>37 H</h3>
                            <h6>TEMPS DE DÉVELOPPEMENT</h6>
                          </div>
                          <div class="col-sm-4">
                            <h1><i class="fa fa-rss-square"></i></h1>
                            <h3>3</h3>
                            <h6>CAPTEUR UTILISÉS</h6>
                          </div>
                        </div>

                        <h4>technologies utilisées</h4>
                        <div class="collum centered">
                          <div class="row-md-8 row-md-offset-2">
                            <div id="block_logo">
                              <div class="logo" style="margin-bottom: 20px;">
                                <img src="img/logo-arduino.png" alt="" width="90">
                              </div>
                              <div class="logo" style="margin-bottom: 20px;">
                                <img src="img/logo-lora.png" alt="" width="90">
                              </div>
                              <div class="logo" style="margin-bottom: 20px;">
                                <img src="img/logo-ttn.png" alt="" width="90">
                              </div>
                              <div class="logo" style="margin-bottom: 20px;">
                                <img src="img/logo-php.png" alt="" width="90">
                              </div>
                              <div class="logo" style="margin-bottom: 20px;">
                                <img src="img/logo-mysql.png" alt="" width="90">
                              </div>
                            </div>
                          </div>
                        </div>

                        <h4>Capteur utilisés</h4>
                        <div class="row centered">
                          <h5>1 x Capteur de <b>temperature</b></h5>
                          <h5>1 x Capteur <b>d'humidité</b></h5>
                          <h5>1 x Capteur <b>ultrason</b></h5>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="edit" class="tab-pane">
                    <div class="row">
                      <div class="col-lg-8 col-lg-offset-2 detailed mt">
                        <h4 class="mb">Modifer le mot de passe</h4>
                        <form role="form" class="form-horizontal" method="post">
                          <div class="form-group">
                            <label class="col-lg-2 control-label">Nouveau mot de passe</label>
                            <div class="col-lg-6">
                              <input type="password" placeholder="Nouveau mot de passe" class="form-control" name="newmdp1">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-lg-2 control-label">Confirmation du mot de passe</label>
                            <div class="col-lg-6">
                              <input type="password" placeholder="Confirmation" class="form-control" name="newmdp2">
                            </div>
                          </div>
                          <div style="margin-bottom:20px;"><?=$msg?></div>
                          <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                              <button class="btn btn-theme" type="submit">Sauvegarder</button>
                              <a href="index.php?id=<?=$_SESSION['id']?>">
                                <button class="btn btn-theme04" type="button">Annuler</button>
                              </a>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </section>

    <?php include("Extensions/footer.php"); ?>

  </section>

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/common-scripts.js"></script>

  <style>
    @media only screen and (min-width: 600px) {
      #block_logo {
        display:flex;
        flex-direction:row;
        justify-content:center;
        margin-top:30px;
      }
      .logo {
        margin-right:20px;
      }     
    }
  </style>

</body>

</html>

<?php } else {include("Extensions/erreur_login.php");}?>
<?php } else { header('location: login.php'); } ?>
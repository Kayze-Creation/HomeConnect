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

<?php // Récuperation des derniers données de la table homeconnect_data (données des capteurs)
$req_last_data = $bdd->query('SELECT * FROM homeconnect_data ORDER BY id DESC LIMIT 1');
$last_data = $req_last_data->fetch();
?>

<?php // Moyenne de la temperature par jour
$semaine = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
for ($i = 0; $i <= 7; $i++) {
$moyenne=$bdd->query('SELECT avg(humidity) AS moyenne FROM homeconnect_data WHERE jour="'.$semaine[$i].'"');
$moyenne=$moyenne->fetch(); 
$liste_moyenne[] = abs($moyenne['moyenne']);
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
  <title>HomeConnect - Tableau de bord</title>
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>
</head>

<body>
  <section id="container">

    <?php include("Extensions/menu.php"); ?>

    <aside>
      <div id="sidebar" class="nav-collapse ">
        <ul class="sidebar-menu" id="nav-accordion">
          <li class="mt">
            <a class="active" href="index.php?id=<?=$_SESSION['id']?>">
              <i class="fa fa-dashboard"></i>
              <span>Tableau de bord</span>
              </a>
          </li>

          <div>
            <li>
              <a href="temperature.php?id=<?=$_SESSION['id']?>">
                <i class="fa fa-fire"></i>
                <span>Température</span>
                <span class="label label-theme pull-right mail-info"><div id="data1"><?=$last_data['temperature']?>°C</div></span>
                </a>
            </li>

            <li>
              <a href="humidite.php?id=<?=$_SESSION['id']?>">
                <i class="fa fa-tint"></i>
                <span>Humidité</span>
                <span class="label label-theme pull-right mail-info"><div id="data2"><?=$last_data['humidity']?>%</div></span>
                </a>
            </li>

            <li>
              <a href="luminosite.php?id=<?=$_SESSION['id']?>">
                <i class="fa fa-adjust"></i>
                <span>Luminosité</span>
                <span class="label label-theme pull-right mail-info"><div id="data3"><?=$last_data['luminosity']?> LUX</div></span>
                </a>
            </li>
          </div>

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
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-9 main-chart">
            <div class="border-head">
              <h3>Tableau de bord</h3>
            </div>

            <div class="col-md-4 mb">
                <div class="weather pn">
                  <i class="fa fa-cloud fa-4x"></i>
                  <h2 id="temp"><?=$last_data['temperature']?> º C</h2>
                  <h4>TEMPÉRATURE</h4>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>HUMIDITÉ</h5>
                  </div>
                  <canvas id="serverstatus01" height="120" width="120"></canvas>
                  <script>
                    var doughnutData = [{
                        value: <?=$last_data['humidity']?>,
                        color: "#FF6B6B"
                      },
                      {
                        value: <?=100-$last_data['humidity']?>,
                        color: "#fdfdfd"
                      }
                    ];
                    var myDoughnut = new Chart(document.getElementById("serverstatus01").getContext("2d")).Doughnut(doughnutData);
                  </script>
                  <div class="row">
                    <div class="col-sm-6 col-xs-6 goleft">
                      <p>Moyenne :</p>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                      <h2 id="hum"><?=$last_data['humidity']?> %</h2>
                    </div>
                  </div>
                </div>
                <!-- /grey-panel -->
              </div>

              <div class="col-md-4 mb">
                <div class="weather pn">
                  <i class="fa fa-sun-o fa-4x"></i>
                  <h2 id="lum"><?=$last_data['luminosity']?> LUX</h2>
                  <h4>LUMINOSITÉ</h4>
                </div>
            </div>

            <div class="row mt">

            </div>
      
            <div class="row">
  
            </div>

            <div class="row">
      
            </div>

            <div class="row">

            </div>

          </div>

          <?php include("Extensions/aside_right.php"); ?>
     
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
  <script src="lib/jquery.sparkline.js"></script>
  <script src="lib/common-scripts.js"></script>
  <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="lib/gritter-conf.js"></script>
  <script src="lib/sparkline-chart.js"></script>
  <script src="lib/zabuto_calendar.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      var unique_id = $.gritter.add({

        title: '',
        text: '',
        image: '',
        sticky: false,
        time: 8000,
        class_name: 'my-sticky-class'
      });

      return false;
    });
  </script>
  <script type="application/javascript">
    $(document).ready(function() {
      $("#date-popover").popover({
        html: true,
        trigger: "manual"
      });
      $("#date-popover").hide();
      $("#date-popover").click(function(e) {
        $(this).hide();
      });

      $("#my-calendar").zabuto_calendar({
        action: function() {
          return myDateFunction(this.id, false);
        },
        action_nav: function() {
          return myNavFunction(this.id);
        },
        ajax: {
          url: "show_data.php?action=1",
          modal: true
        },
        legend: [{
            type: "text",
            label: "Évènement spécial",
            badge: "00"
          },
          {
            type: "block",
            label: "Évènement régulier",
          }
        ]
      });
    });

    function myNavFunction(id) {
      $("#date-popover").hide();
      var nav = $("#" + id).data("navigation");
      var to = $("#" + id).data("to");
      console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }
  </script>

  <script src="lib/ajax.js"></script>
  <script>
    function data1()
    {
    $.ajax({
    type: 'GET',
    url: 'load/data1.php',
    success: function(data){$('#data1').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('data1();',2000);
    }); 
/******************************************************/
    function data2()
    {
    $.ajax({
    type: 'GET',
    url: 'load/data2.php',
    success: function(data){$('#data2').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('data2();',2000);
    });
/******************************************************/
    function data3()
    {
    $.ajax({
    type: 'GET',
    url: 'load/data3.php',
    success: function(data){$('#data3').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('data3();',2000);
    });
/******************************************************/
    function etat_serveur()
    {
    $.ajax({
    type: 'GET',
    url: 'load/etat_serveur.php',
    success: function(data){$('#etat_serveur').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('etat_serveur();',2000);
    }); 
    /******************************************************/
    function temp()
    {
    $.ajax({
    type: 'GET',
    url: 'load/temp.php',
    success: function(data){$('#temp').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('temp();',2000);
    });
    /******************************************************/
    function hum()
    {
    $.ajax({
    type: 'GET',
    url: 'load/hum.php',
    success: function(data){$('#hum').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('hum();',2000);
    }); 
    /******************************************************/
    function lum()
    {
    $.ajax({
    type: 'GET',
    url: 'load/lum.php',
    success: function(data){$('#lum').html(data);}
    });
    }
    $('document').ready(function(){
    setInterval('lum();',2000);
    });  
  </script>
    
</body>
</html>

<?php } else {include("Extensions/erreur_login.php");}?>
<?php } else { header('location: login.php'); } ?>
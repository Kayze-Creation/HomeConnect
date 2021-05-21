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
$moyenne=$bdd->query('SELECT avg(temperature) AS moyenne FROM homeconnect_data WHERE jour="'.$semaine[$i].'"');
$moyenne=$moyenne->fetch(); 
$liste_moyenne[] = abs($moyenne['moyenne']);
}
?>

<?php 
$jour = strftime('%A');
for ($i = 0; $i <= 23; $i++) {
  $moyenne_graph=$bdd->query('SELECT avg(temperature) AS moyenne_graph FROM homeconnect_data WHERE heure="'.$i.'" AND jour="'.$jour.'"');
  $moyenne_graph=$moyenne_graph->fetch(); 
  $liste_graph[] = abs($moyenne_graph['moyenne_graph']);
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
  <title>HomeConnect - Température</title>
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
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

          <div>
            <li>
              <a class="active" href="temperature.php?id=<?=$_SESSION['id']?>">
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
              <h3>Température</h3>
            </div>

            <h3>Température sur la semaine:</h3>
            <div class="custom-bar-chart">
              <ul class="y-axis">
                <li><span>100°C</span></li>
                <li><span>80°C</span></li>
                <li><span>60°C</span></li>
                <li><span>40°C</span></li>
                <li><span>20°C</span></li>
                <li><span>0°C</span></li>
              </ul>
              <div class="bar">
                <div class="title">Lundi</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[0]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[0]?>%</div>
              </div>
              <div class="bar ">
                <div class="title">Mardi</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[1]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[1]?>%</div>
              </div>
              <div class="bar ">
                <div class="title">Mercredi</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[2]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[2]?>%</div>
              </div>
              <div class="bar ">
                <div class="title">Jeudi</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[3]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[3]?>%</div>
              </div>
              <div class="bar">
                <div class="title">Vendredi</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[4]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[4]?>%</div>
              </div>
              <div class="bar ">
                <div class="title">Samedi</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[5]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[5]?>%</div>
              </div>
              <div class="bar">
                <div class="title">Dimanche</div>
                <div class="value tooltips" data-original-title="<?=$liste_moyenne[6]?>°C" data-toggle="tooltip" data-placement="top"><?=$liste_moyenne[6]?>%</div>
              </div>
            </div>

            <div id="morris">
              <div class="row mt">
                <div class="col-lg-12">
                  <div class="content-panel">
                    <h4><i class="fa fa-angle-right"></i> Température Aujourd'hui :</h4>
                    <div class="panel-body">
                      <div id="hero-graph" class="graph"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           

          </div>

          <?php include("Extensions/aside_right.php"); ?>
     
        </div>
      </section>
    </section>
    
    <?php include("Extensions/footer.php"); ?>

  </section>

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/raphael/raphael.min.js"></script>
  <script src="lib/morris/morris.min.js"></script>
  <script src="lib/common-scripts.js"></script>

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
  </script>

  <script>
    var Script = function () {
      $(function () {
        // data stolen from http://howmanyleft.co.uk/vehicle/jaguar_'e'_type
        var tax_data = [
            {"period": "00h", "licensed": <?php echo  $liste_graph[0]; ?>},
            {"period": "01h", "licensed": <?php echo  $liste_graph[1]; ?>},
            {"period": "02h", "licensed": <?php echo  $liste_graph[2]; ?>},
            {"period": "03h", "licensed": <?php echo  $liste_graph[3]; ?>},
            {"period": "04h", "licensed": <?php echo  $liste_graph[4]; ?>},
            {"period": "05h", "licensed": <?php echo  $liste_graph[5]; ?>},
            {"period": "06h", "licensed": <?php echo  $liste_graph[6]; ?>},
            {"period": "07h", "licensed": <?php echo  $liste_graph[7]; ?>},
            {"period": "08h", "licensed": <?php echo  $liste_graph[8]; ?>},
            {"period": "09h", "licensed": <?php echo  $liste_graph[9]; ?>},
            {"period": "10h", "licensed": <?php echo  $liste_graph[10]; ?>},
            {"period": "11h", "licensed": <?php echo  $liste_graph[11]; ?>},
            {"period": "12h", "licensed": <?php echo  $liste_graph[12]; ?>},
            {"period": "13h", "licensed": <?php echo  $liste_graph[13]; ?>},
            {"period": "14h", "licensed": <?php echo  $liste_graph[14]; ?>},
            {"period": "15h", "licensed": <?php echo  $liste_graph[15]; ?>},
            {"period": "16h", "licensed": <?php echo  $liste_graph[16]; ?>},
            {"period": "17h", "licensed": <?php echo  $liste_graph[17]; ?>},
            {"period": "18h", "licensed": <?php echo  $liste_graph[18]; ?>},
            {"period": "19h", "licensed": <?php echo  $liste_graph[19]; ?>},
            {"period": "20h", "licensed": <?php echo  $liste_graph[20]; ?>},
            {"period": "21h", "licensed": <?php echo  $liste_graph[21]; ?>},
            {"period": "22h", "licensed": <?php echo  $liste_graph[22]; ?>},
            {"period": "23h", "licensed": <?php echo  $liste_graph[23]; ?>},
        ];
        Morris.Line({
          element: 'hero-graph',
          data: tax_data,
          xkey: 'period',
          ykeys: ['licensed'],
          labels: ['Température'],
          lineColors:['#4ECDC4']
        });  
      });
    }();
  </script>

</body>
</html>

<?php } else {include("Extensions/erreur_login.php");}?>
<?php } else { header('location: login.php'); } ?>
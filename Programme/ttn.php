<?php
/***********************************************************************
 * Projet Communication Sans Fil
 * HomeConnect
 * ---------------------------------------------------------------------
 * Page de réception des données de The Things Network via fichier JSON,
 * et envoi des data reçu sur une Base de données en MyQSL.
 * ---------------------------------------------------------------------
 * Crée par Adrien Vivaldi et Quentin Escobar
 * Version 1.0 
 **********************************************************************/

require_once('Extensions/config.php'); // Connexion à la bdd
ini_set('display_errors', 'off'); // On retire l'affichage des erreurs si vrai
$write_log = true; // Autorise ou non l'écriture de log

$ttn_post = file('php://input'); // Récuperation des données
$data = json_decode($ttn_post[0]); // Récuperation de l'encodage JSON et convertion en variable PHP sur $data

// Création des variables PHP via le JSON décoder
$sensor_temperature = $data->payload_fields->temperature_1; // La valeur du capteur de température s'inscrit sur la variable PHP sensor_temperature
$sensor_humidity = $data->payload_fields->relative_humidity_2; // La valeur du capteur d'humidité s'inscrit sur la variable PHP sensor_temperature
$sensor_luminosity = $data->payload_fields->luminosity_3; // La valeur du capteur de luminosité s'inscrit sur la variable PHP sensor_luminosity

$ttn_app_id = $data->app_id; //
$ttn_dev_id = $data->dev_id; //
$ttn_time = $data->metadata->time; //

// Récupere des informations complémentaire
$server_datetime = date("Y-m-d H:i:s");
$jour = strftime('%A');
$heure = date("H");

// On sécurise les données récuperée via la fonction PHP htmlspecialchars
$ttn_app_id = htmlspecialchars($ttn_app_id);
$ttn_dev_id = htmlspecialchars($ttn_dev_id);
$ttn_time = htmlspecialchars($ttn_time);
$sensor_temperature = htmlspecialchars($sensor_temperature);
$sensor_humidity = htmlspecialchars($sensor_humidity);
$sensor_luminosity = htmlspecialchars($sensor_luminosity);

// Insertion des varibles dans la table homeconnect_data de notre base de données MySQL via requêtes préparées : fonctions prepare et execute (propre au PDO)
// pour plus de sécurité (injection SQL) et plus de rapidité.
$insertbdd = $bdd->prepare("INSERT INTO homeconnect_data(datetime, app_id, dev_id, ttn_time, jour,heure, temperature, humidity, luminosity) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insertbdd->execute(array($server_datetime, $ttn_app_id, $ttn_dev_id, $ttn_time, $jour,$heure, $sensor_temperature, $sensor_humidity, $sensor_luminosity));

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Vérification capteurs et serveur

if(($sensor_temperature AND $sensor_humidity AND $sensor_luminosity) != NULL) {
    $full_donnee = true;
} else {
    $full_donnee = false;
}
if($ttn_time != NULL) {
    $server_connect = true;
} else {
    $server_connect = false;
}

$insert_server_bdd = $bdd->prepare("INSERT INTO homeconnect_server(datetime, full_donnee, connect) VALUES(?, ?, ?)");
$insert_server_bdd->execute(array($server_datetime, $full_donnee, $server_connect));  

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Notification

$reqnotif= $bdd->prepare("SELECT * FROM homeconnect_notif WHERE id = 1");
$reqnotif->execute(array());
$notif = $reqnotif->fetch();
$reqnotif2= $bdd->prepare("SELECT * FROM homeconnect_notif WHERE id = 2");
$reqnotif2->execute(array());
$notif2 = $reqnotif2->fetch();
$reqnotif3= $bdd->prepare("SELECT * FROM homeconnect_notif WHERE id = 3");
$reqnotif3->execute(array());
$notif3 = $reqnotif3->fetch();

if($sensor_temperature >= $notif['temp'] AND $notif['valeurs'] != 1) {
    include("Extensions/notif_temp.php");
}
if($sensor_humidity >= $notif2['hum'] AND $notif2['valeurs'] != 1) {
    include("Extensions/notif_hum.php");
}
if($sensor_luminosity >= $notif3['lum'] AND $notif3['valeurs'] != 1) {
    include("Extensions/notif_lum.php");
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Ecriture de log
if ($write_log == true) {
    file_put_contents('Logs/log_ttn_post.txt', $ttn_post[0] . PHP_EOL, FILE_APPEND);
}
?>
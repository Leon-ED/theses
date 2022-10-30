<?php
require_once("../config/config.php");


$stats = array();

// Nombre de thèses
$sql = "SELECT COUNT(*) AS nombre_these FROM these";
$sth = $conn->prepare($sql);
$sth->execute();
$stats["nombre_these"] = $sth->fetch()["nombre_these"];

// Nombre de thèses par langue

// en français
$sql = "SELECT COUNT(langue) FROM these WHERE langue LIKE 'fr'";
$sth = $conn->prepare($sql);
$sth->execute();

$nombre_fr = $sth->fetch()["COUNT(langue)"];

// en anglais
$sql = "SELECT COUNT(langue) FROM these WHERE langue LIKE 'en'";
$sth = $conn->prepare($sql);
$sth->execute();

$nombre_en = $sth->fetch()["COUNT(langue)"];

// d'autres langues
$sql = "SELECT COUNT(langue) FROM these WHERE langue NOT LIKE 'en' AND langue NOT LIKE 'fr'";
$sth = $conn->prepare($sql);
$sth->execute();

$nombre_autre = $sth->fetch()["COUNT(langue)"];


$stats["nombre_these_langue"] = array("fr" => $nombre_fr, "en" => $nombre_en, "autre" => $nombre_autre);



// Nombre directeurs de thèses
$sql = "SELECT COUNT(DISTINCT idPersonne) AS nombre_directeurs FROM a_dirige";
$sth = $conn->prepare($sql);
$sth->execute();
$stats["nombre_directeurs"] = $sth->fetch()["nombre_directeurs"];

// Nombre d'auteurs
$sql = "SELECT COUNT(DISTINCT idPersonne) AS nombre_auteurs FROM a_ecrit";
$sth = $conn->prepare($sql);
$sth->execute();
$stats["nombre_auteurs"] = $sth->fetch()["nombre_auteurs"];





//On encode le JSON et envoie la réponse au client

header('Content-Type: application/json');
echo json_encode($stats);








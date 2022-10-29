<?php
require_once("../config/config.php");

function getStatsTheses()
{
    global $conn;

    $stats = array();

    // Nombre de thèses
    $sql = "SELECT COUNT(*) AS nombre_theses FROM these";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_theses"] = $sth->fetch()["nombre_theses"];

    // Nombre d'établissements
    $sql = "SELECT COUNT(*) AS nombre_etablissements FROM etablissement";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_etablissements"] = $sth->fetch()["nombre_etablissements"];



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

    return $stats;
}

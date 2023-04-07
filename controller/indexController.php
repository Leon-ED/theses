<?php

/**
 * S'occupe de gérer l'affichage de la page view/index.php
 */
$stats = getStatsTheses(); //On récupère les statistiques globales

$nombre_theses = $stats['nombre_theses'];
$nombre_etablissements = $stats['nombre_etablissements'];
$nombre_directeurs = $stats['nombre_directeurs'];
$nombre_auteurs = $stats['nombre_auteurs'];


// Message d'erreur si aucune thèse n'est trouvée
$bootstrap_alert = "";
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $bootstrap_alert = '
    <section class="bootstrap-alert">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Aucune thèse n\'a été trouvée pour cette recherche.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </section>
    ';
}

/**
 * Affiche les statistiques globales sur les thèses
 * 
 * @return array $stats : tableau contenant les statistiques
 */
function getStatsTheses(): array
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

    foreach ($stats as $key => $value) {
        $stats[$key] = number_format($value, 0, ',', ' ');
    }
    return $stats;
}

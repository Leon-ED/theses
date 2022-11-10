<?php
require_once("../Model/indexModel.php");
$stats = getStatsTheses();

$nombre_theses = $stats['nombre_theses'];
$nombre_etablissements = $stats['nombre_etablissements'];
$nombre_directeurs = $stats['nombre_directeurs'];
$nombre_auteurs = $stats['nombre_auteurs'];

if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $bootstrap_alert = '
    <section class="bootstrap-alert">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Aucune thèse n\' a été trouvée pour cette recherche.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </section>
    ';
}

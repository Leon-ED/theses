<?php
require_once("../config/config.php");
require_once("../include/html.header.inc.php");

require_once("../controller/indexController.php");
?>

<body>
    <h1 class="text-center" style="margin-top:10%;">Recherche des thèses françaises</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="d-flex" action="./search.php" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Chercher une thèse par titre, auteur, sujet ou mots-clé" name="search" aria-label="Recherche">
                    <button class="btn btn-outline-success" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?= $bootstrap_alert ?>
<section class="dashboard">
    <div class="card widget-flat text-center" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Nombre de thèses</h5>
            <h1 class="card-subtitle mt-3 mb-3 text-muted"><?= $nombre_theses ?></h1>
        </div><?= $nombre_  ?>
    </div>
    <div class="card widget-flat text-center" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Nombre d'auteurs</h5>
            <h1 class="card-subtitle mt-3 mb-3 text-muted"><?= $nombre_auteurs ?></h1>
        </div>
    </div>
    <div class="card widget-flat text-center" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Nombre de directeurs</h5>
            <h1 class="card-subtitle mt-3 mb-3 text-muted"><?= $nombre_directeurs  ?></h1>
        </div>
    </div>
    <div class="card widget-flat text-center" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Etablissements</h5>
            <h1 class="card-subtitle mt-3 mb-3 text-muted"><?= $nombre_etablissements  ?></h1>
        </div>
    </div>
</section>

</html>
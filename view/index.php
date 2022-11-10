<?php
$file = "index.php";
require_once("../config/config.php");
require_once("../include/html.header.inc.php");
include_once("../controller/indexController.php");
?>

<body>
    <?php require_once("../include/nav.inc.php") ?>

    <h1 class="text-center" style="margin-top:1%;">Recherche des thèses françaises</h1>
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

<?= require_once("../include/dashboard.inc.php"); ?>
</html>
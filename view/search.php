<?php
try {
    $file = "search.php";
    require_once("./config/config.php");
    require_once("./include/html.header.inc.php");
    require_once("./controller/searchController.php");
    require_once("./controller/graphsController.php");
} catch (PDOException $e) {
    echo $e->getMessage();
    die("Une erreur est survenue lors de la recherche");
}
$graphsController = new GraphsController(true, $theses);


?>

<body>
    <?php require_once("./include/nav.inc.php"); ?>
    <h3 id="top" class="search-result-h">Résultats de recherche :</h2>
        <?= $alert ?>
        <?php require_once("./include/dashboard.inc.php"); ?>
        <?php require_once("./include/graphs.inc.php"); ?>
        <h3 class="search-result-h">Thèses :</h3>
        <!-- Section qui contient les résultats de la recherche -->
        <section class="these-list">
            <?php echoThese($theses); ?>
        </section>
        <button id="retour"><a href="#top" style="color: white">Retour en haut</a></button>
</body>


</html>
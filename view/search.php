<?php
try {
    $file = "search.php";
    require_once("../config/config.php");
    require_once("../include/html.header.inc.php");
    require_once("../controller/searchController.php");
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<body>
    <?php require_once("../include/nav.inc.php") ?>

    <h3 class="search-result-h">Résultats de recherche :</h2>
        <?php require_once("../include/dashboard.inc.php"); ?>
        <h3 class="search-result-h">Thèses :</h3>

        <!-- Section qui contient les résultats de la recherche -->
        <section class="these-list">
            <?php echoThese($theses); ?>
        </section>





</body>

</html>
<?php
require_once("../config/config.php");
require_once("../include/html.header.inc.php");
require_once("../controller/searchController.php");

?>

<body>
    <?php require_once("../include/nav.inc.php") ?>

    <h3 class="search-result-h">Résultats de recherche :</h2>
        <?php require_once("../include/dashboard.inc.php"); ?>
        <h3 class="search-result-h">Thèses :</h3>

        <section class="these-list">
            <div class="these-card">
                <div class="these-card-header">
                    <h2 class="these-card-title"><a href="#">Titre très très long d'une thèse qui est tout aussi longue que ce titre très très trèèèès loooong.</a></h2>
                    <div class="these-card-infos">
                        <p>par <span class="these-card-author"><a href="#"><span>Steven Growton</span></a></p>
                        <p>le : <span class="these-card-date">20/20/2020</span></p>
                    </div>
                </div>
                <div class="these-card-body">
                    <p>Sous la direction de : <a href="#"><span>Steven Growton</span></a> </p>
                    <p>Discipline: <a href="#"><span>Mathématiques</span></a> , <a href="#"><span>Cuisine</span></a> </p>
                    <p>Mots-clés: <a href="#"><span>Mathématiques</span></a> , <a href="#"><span>Cuisine</span></a> , <a href="#"><span>Mathématiques</span></a> , <a href="#"><span>Cuisine</span></a> </p>


                </div>
            </div>


            <?php echoThese($theses); ?>
        </section>





</body>

</html>
<?php
require_once("../Model/searchModel.php");

$resultats = getSearchResults();
$stats = getStatsFromResults($resultats);

$nombre_theses = $stats['nombre_theses'];
$nombre_auteurs = $stats['nombre_auteurs'];
$nombre_directeurs = $stats['nombre_directeurs'];
$nombre_etablissements = $stats['nombre_etablissements'];

$theses = createTheseFromResults($resultats);



/**
 * Affiche le résumé de chaque thèse d'une liste
 * 
 * @param array $listeThese : liste de thèses
 * @return void
 * @throws Exception Si un élément n'est pas un objet thèse
 */
function echoThese(array $listeThese): void
{
    global $conn;
    foreach ($listeThese as $these) {
        if (!is_a($these, 'These')) {
            throw new Exception("Cette thèse n'est pas un objet thèse.");
        }
?>
            <div class="these-card">
                <div class="these-card-header">
                    <h2 class="these-card-title"><a href="<?= $these->getLink() ?>"><?= $these->getTitre(); ?></a></h2>
                    <div class="these-card-infos">
                        <p>par <span class="these-card-author"><a href="#"><span><?= $these->getAuteur($conn); ?></span></a></p>
                        <p>le : <span class="these-card-date"><?= $these->getDateSoutenance() ?></span></p>
                    </div>
                </div>
                <div class="these-card-body">
                    <p>Sous la direction de : <a href="#"><span><?= $these->getDirecteur($conn); ?></span></a> </p>
                    <p>Discipline: <a href="#"><?= $these->getDiscine() ?></a> </p>

                </div>
            </div>


    <?php
    }
}
//                     <p>Mots-clés: <?php echoTheseMotsCles($these) </p>

/**
 * Affiche le code HTML des mots-clés d'une thèse
 * @param These $these
 * @return void
 */
function echoTheseMotsCles(These $these): void
{
    
    global $conn;
    $str = "";
    $liste_mots = $these->getMotsCles($conn);
    foreach ($liste_mots as $motCle) {
        $str = $str . "<a href='?mc=$motCle[idMot]'><span>$motCle[mot]</span></a> , ";
    }
    substr($str, 0, -2);
}

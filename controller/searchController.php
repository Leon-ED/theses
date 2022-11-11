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
function echoThese(array $listeThese)
{
    global $conn;
    foreach ($listeThese as $these) {
        if (!is_a($these, 'These')) {
            throw new Exception("Cette thèse n'est pas un objet thèse.");
        }
?>
        <div class="these-card">
            <div class="these-card-header">
                <h2 class="these-card-title"><a href="<?= $these->getLink() ?>"><?= htmlspecialchars($these->getTitre()); ?></a></h2>
                <div class="these-card-infos">
                    <p>par <span class="these-card-author"><a href="#"><?= echoAuteurs($these); ?></a></p>
                    <p>le : <span class="these-card-date"><?= htmlspecialchars($these->getDateSoutenance()) ?></span></p>
                </div>
            </div>
            <div class="these-card-body">
                <p>Sous la direction de : <a href="#"><span><?= echoDirecteurs($these); ?></span></a> </p>
                <p>Discipline: <a href="#"><?= htmlspecialchars($these->getDiscine()) ?></a> </p>
                <p>Établissement : <a href="#"><?= echoEtablissements($these) ?></a> </p>
                <p>Mots-clés: <?php echoTheseMotsCles($these) ?> </p>
            </div>
        </div>


<?php
    }
}
//                     

/**
 * Affiche le code HTML des mots-clés d'une thèse
 * @param These $these
 * 
 */
function echoTheseMotsCles(These $these)
{

    global $conn;
    $str = "";
    $liste_mots = $these->getMotsCles($conn);
    if (is_string($liste_mots)) {
        echo $liste_mots;
        return;
    }
    foreach ($liste_mots as $motCle) {
        $mot = htmlspecialchars($motCle['mot']);
        $id = urlencode(htmlspecialchars($motCle['id']));
        $str = $str . "<a href='?mc=$id'><span>$mot</span></a> , ";
    }
    // Supprime la dernière virgule
    $str = substr($str, 0, -2);
    echo $str;
}

/**
 * Affiche le code HTML des directeurs d'une thèse
 * @param These $these
 * 
 */
function echoDirecteurs(These $these)
{
    global $conn;
    $str = "";
    $directeurs = $these->getDirecteur($conn);
    foreach ($directeurs as $directeur) {
        $nom = htmlspecialchars($directeur);
        $url = urlencode($nom);
        $str = $str .  "<a href='?dir=$url'><span>$nom</span></a> , ";
    }
    // Supprime la dernière virgule
    $str = substr($str, 0, -2);
    echo $str;
}

/**
 * Affiche les auteurs d'une thèse
 */
function echoAuteurs(These $these)
{
    global $conn;
    $str = "";
    $auteurs = $these->getAuteur($conn);
    foreach ($auteurs as $auteur) {
        $nom = htmlspecialchars($auteur);
        $url = urlencode($nom);
        $str = $str .  "<a href='?aut=$url'><span>$nom</span></a> , ";
    }
    // Supprime la dernière virgule
    $str = substr($str, 0, -2);
    echo $str;
}

function echoEtablissements(These $these)
{
    global $conn;
    $str = "";
    $etablissements = $these->getEtablissement($conn);
    foreach ($etablissements as $etablissement) {
        $nom = htmlspecialchars($etablissement);
        $url = urlencode($nom);
        $str = $str .  "<a href='?etab=$url'><span>$nom</span></a> , ";
    }
    // Supprime la dernière virgule
    $str = substr($str, 0, -2);
    echo $str;
}

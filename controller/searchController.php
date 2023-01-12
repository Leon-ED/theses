<?php

/**
 * S'occupe de gérer l'affichage de la page view/search.php
 */
$resultats = getSearchResults();

$stats = getStatsFromResults($resultats);

$nombre_theses = $stats['nombre_theses'];
$nombre_auteurs = $stats['nombre_auteurs'];
$nombre_directeurs = $stats['nombre_directeurs'];
$nombre_etablissements = $stats['nombre_etablissements'];

$theses = createTheseFromResults($resultats); //Récupère la liste d'objets These
$alert = "";
if (isset($_SESSION["erreur_recherche"])) {
    $alert = "<p class='text-center'>" . $_SESSION["erreur_recherche"] . "</p>";
    unset($_SESSION["erreur_recherche"]);
}


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
 * Affiche les mots-clés d'une thèse
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
 * Affiche les directeurs d'une thèse
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
 * @param These $these
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

/**
 * Affiche les établissements d'une thèse
 * @param These $these
 */
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

/**
 * Renvoie la liste des idTheses et nnt correspondant aux résultats de la recherche
 * Recherche selon les titres, la discipline,les mots-clés et l'auteur
 *
 * @return array 
 */
function getSearchResults(): array
{
    global $conn;


    //On vérifie que le formulaire a bien été envoyé
    if (!isset($_GET) || empty($_GET)) {
        header("Location: ./");
        exit();
    }

    // if (isset($_GET["dir"]) || isset($_GET["etab"]) || isset($_GET["aut"]) || isset($_GET["mc"])) {
    //     return getResultAvances();
    // }
    if (isset($_GET["random"])) {
        return getResultRandom();
    }

    if (strlen($_GET["search"]) < 3) {
        $_SESSION["erreur_recherche"] = "La recherche doit contenir au moins 3 caractères, voici 10 thèses au hasard";
        //refresh current page
        return getResultRandom();
    }

    $recherche = "%$_GET[search]%";
    // On fait la recherche sur les titres, la discipline et le nnt
    $sql = "
    SELECT DISTINCT these.idThese, these.nnt FROM these
    WHERE titre_fr LIKE :recherche
    OR resume_fr LIKE :recherche
    OR resume_en LIKE :recherche
    OR titre_en LIKE :recherche
    OR discipline LIKE :recherche
    OR these.nnt LIKE :recherche
    OR dateSoutenance LIKE :recherche
    ORDER BY dateSoutenance DESC
    ";

    //Recherche sur les auteurs
    $sql2 = "
    SELECT these.nnt,
       these.idThese
        FROM these
        INNER JOIN a_ecrit ON these.nnt = a_ecrit.nnt
        INNER JOIN personne ON a_ecrit.idPersonne = personne.idPersonne
        WHERE prenomPersonne = :recherche
        OR personne.nomPersonne = :recherche
        OR CONCAT_WS(' ', personne.nomPersonne, personne.prenomPersonne) = :recherche
        OR CONCAT_WS(' ', personne.prenomPersonne, personne.nomPersonne) = :recherche
    ";

    // On exécute les requêtes
    $recherche_these = $conn->prepare($sql);
    $recherche_these->execute(array(
        "recherche" => $recherche,
    ));
    $recherche_these->execute();
    $recherche_these = $recherche_these->fetchAll(PDO::FETCH_ASSOC);


    $recherche_auteur = $conn->prepare($sql2);
    $recherche_auteur->execute(array(
        "recherche" => $recherche
    ));
    $recherche_auteur->execute();
    $recherche_auteur = $recherche_auteur->fetchAll(PDO::FETCH_ASSOC);

    // On fusionne les résultats
    $resultats = array_merge($recherche_these, $recherche_auteur);


    // Si elle n'a rien donné on renvoie vers la page d'accueil avec un message d'erreur
    if (empty($resultats)) {
        header("Location: ./?msg=aucun_resultat");
        exit();
    }
    return $resultats;
}


/**
 * Renvoie les statistiques liées à la recherche
 * 
 * @param array $results
 * @return array
 */
function getStatsFromResults(array $results): array
{
    global $conn;
    $stats = array();
    // Nombre de thèses
    $stats["nombre_theses"] = count($results);


    // Nombre d'auteurs
    $sql = "SELECT COUNT(DISTINCT idPersonne) as nombre_auteurs FROM a_ecrit WHERE nnt IN (" . implodeToSQL(array_column($results, "nnt")) . ")";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_auteurs"] = $sth->fetch()["nombre_auteurs"];

    //Nombre de directeurs
    $sql = "SELECT COUNT(DISTINCT idPersonne) as nombre_directeurs FROM a_dirige WHERE nnt IN (" . implodeToSQL(array_column($results, "nnt")) . ")";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_directeurs"] = $sth->fetch()["nombre_directeurs"];

    // Nombre d'établissements
    $sql = "SELECT COUNT(DISTINCT id_etablissement) as nombre_etablissements FROM these_etablissement WHERE nnt IN (" . implodeToSQL(array_column($results, "nnt")) . ")";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_etablissements"] = $sth->fetch()["nombre_etablissements"];


    return $stats;
}

/**
 * Permet de faire une recherche avancée sur les 
 * directeurs, les auteurs, les établissements et/ou les mots-clés
 * 
 * Pas encore implémenté
 * 
 */
function getResultAvances(): array
{
    return array();
    global $conn;
    $dir = "";
    $etab = "";
    $aut = "";
    $mc = "";

    if (isset($_GET["dir"])) {
        $dir = $_GET["dir"];
    }
    if (isset($_GET["etab"])) {
        $etab = $_GET["etab"];
    }
    if (isset($_GET["aut"])) {
        $aut = $_GET["aut"];
    }
    if (isset($_GET["mc"])) {
        $mc = $_GET["mc"];
    }
    $sql = "SELECT DISTINCT these.idThese, these.nnt FROM these
    WHERE these.nnt IN (SELECT nnt FROM a_dirige WHERE idPersonne = :dir)
    OR these.nnt IN (SELECT nnt FROM these_etablissement WHERE id_etablissement = :etab)
    OR these.nnt IN (SELECT nnt FROM a_ecrit WHERE idPersonne = :aut)
    OR these.nnt IN (SELECT nnt FROM mots_cle WHERE idMot = :mc)
    ORDER BY dateSoutenance DESC
    ";
    $recherche_these = $conn->prepare($sql);
    $recherche_these->execute(array(
        "dir" => $dir,
        "etab" => $etab,
        "aut" => $aut,
        "mc" => $mc,
    ));
    $recherche_these->execute();
    $recherche_these = $recherche_these->fetchAll(PDO::FETCH_ASSOC);
    return $recherche_these;
}

/**
 * Prends une liste de idThese et de nnt et renvoie la liste d'objets Thèse
 * 
 * @param array $results
 * @return array
 */
function createTheseFromResults(array $results): array
{
    global $conn;
    $liste = array();
    foreach ($results as $result) {
        $these = new These();
        $these->setTheseId($result["idThese"])
            ->setNnt($result["nnt"])
            ->setInfosThese($conn);
        $liste[] = $these;
    }
    return $liste;
}

/**
 * Retourne les thèses aux hasard
 * @return array
 */
function getResultRandom($default = 10): array
{
    global $conn;
    $nombre = $default;
    if (isset($_GET["random"])) {
        $nombre = $_GET["random"];
    }
    if (!is_numeric($nombre)) {
        $nombre = 10;
        $_SESSION["erreur_recherche"] = "Nombre de résultats aléatoires invalide, recherche cappée à 10 résultats";
    } elseif ($nombre < 1) {
        $nombre = 1;
        $_SESSION["erreur_recherche"] = "Minimum 1 résultat de recherche";
    } elseif ($nombre > 100) {
        $nombre = 100;
        $_SESSION["erreur_recherche"] = "Recherche cappée à 100 résultats";
    }
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT idThese, nnt FROM these ORDER BY RAND() AND dateSoutenance LIMIT :nombre ";
    $sth = $conn->prepare($sql);
    $sth->bindParam(":nombre", $nombre, PDO::PARAM_INT);
    $sth->execute();
    $sth = $sth->fetchAll(PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    return $sth;
}

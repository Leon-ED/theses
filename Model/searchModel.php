<?php
require_once("../config/config.php");

/**
 * Renvoie la liste des idTheses et nnt correspondant aux résultats de la recherche
 * Recherche selon les titres, la discipline,les mots-clés et l'auteur
 * 
 * @param none
 * @return array 
 */
function getSearchResults(): array
{
    global $conn;


    //On vérifie que le formulaire a bien été envoyé
    if (!isset($_GET) || empty($_GET)) {
        header("Location: ../view/index.ph");
        exit();
    }

    // if (isset($_GET["dir"]) || isset($_GET["etab"]) || isset($_GET["aut"]) || isset($_GET["mc"])) {
    //     return getResultAvances();
    // }


    $recherche = $_GET["search"];
    // On fait la recherche sur les titres, la discipline,les mots-clés et l'auteur
    $sql = "
    SELECT DISTINCT these.idThese, these.nnt FROM these
    WHERE titre_fr LIKE :recherche
    OR titre_en LIKE :recherche
    OR discipline LIKE :recherche
    OR these.nnt LIKE :recherche
    
";
    // OR (a_ecrit.nnt = these.nnt AND personne.idPersonne = a_ecrit.idPersonne AND (personne.nomPersonne LIKE :recherche OR personne.prenomPersonne LIKE :recherche OR CONCAT(personne.prenomPersonne, ' ', personne.nomPersonne) LIKE :recherche))
    // ^^ provoque un ralentissement de la recherche 



    // On exécute la requête
    $recherche_these = $conn->prepare($sql);
    $recherche_these->execute(array(
        "recherche" => $recherche,
    ));
    $recherche_these->execute();
    $recherche_these = $recherche_these->fetchAll(PDO::FETCH_ASSOC);

    // Si elle n'a rien donné on renvoie vers la page d'accueil avec un message d'erreur
    if (empty($recherche_these)) {
        header("Location: ../view/index.php");
        exit();
    }
    return $recherche_these;
}

function getStatsFromResults(array $results): array
{
    global $conn;
    $stats = array();
    // Nombre de thèses
    $stats["nombre_theses"] = count($results);


    // Nombre d'auteurs
    $sql = "SELECT COUNT(idPersonne) as nombre_auteurs FROM a_ecrit WHERE nnt IN (" . implodeToSQL(array_column($results, "nnt")) . ")";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_auteurs"] = $sth->fetch()["nombre_auteurs"];

    //Nombre de directeurs
    $sql = "SELECT COUNT(idPersonne) as nombre_directeurs FROM a_dirige WHERE nnt IN (" . implodeToSQL(array_column($results, "nnt")) . ")";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_directeurs"] = $sth->fetch()["nombre_directeurs"];

    // Nombre d'établissements
    $sql = "SELECT COUNT(id_etablissement) as nombre_etablissements FROM these_etablissement WHERE nnt IN (" . implodeToSQL(array_column($results, "nnt")) . ")";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $stats["nombre_etablissements"] = $sth->fetch()["nombre_etablissements"];


    return $stats;
}

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

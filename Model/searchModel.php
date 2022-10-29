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
    if (!isset($_GET["search"]) || empty($_GET["search"])) {
        header("Location: ../view/index.php?msg=" . urlencode("Veuillez ne pas faire une recherche vide."));
        exit();
    }

    $recherche = $_GET["search"];
    // On fait la recherche sur les titres, la discipline,les mots-clés et l'auteur
    $sql = "
SELECT these.idThese,nnt FROM these
WHERE titre_fr LIKE :recherche
OR titre_en LIKE :recherche
OR discipline LIKE :recherche
OR nnt LIKE :recherche
OR these.nnt IN (SELECT nnt FROM a_ecrit  WHERE a_ecrit.id IN (SELECT idPersonne FROM personne WHERE nomPersonne LIKE :recherche OR prenomPersonne LIKE :recherche))
OR these.idThese IN (SELECT idThese FROM mots_cle WHERE mots_cle.idMot IN (SELECT idMot FROM liste_mots_cles WHERE mot LIKE :recherche))
";
    // On exécute la requête
    $recherche_these = $conn->prepare($sql);
    $recherche_these->execute(array(
        "recherche" => $recherche,
    ));
    $recherche_these->execute();
    $recherche_these = $recherche_these->fetchAll(PDO::FETCH_ASSOC);

    // Si elle n'a rien donné on renvoie vers la page d'accueil avec un message d'erreur
    if (empty($recherche_these)) {
        header("Location: ../view/index.php?msg=" . urlencode("Aucun résultat pour votre recherche."));
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

    $stats["nombre_etablissements"] = 0;
    // Nombre d'auteurs


    return $stats;
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

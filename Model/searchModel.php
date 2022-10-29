<?php
require_once("../config/config.php");
function getSearchResults()
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
SELECT these.idThese FROM these
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

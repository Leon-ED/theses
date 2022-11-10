<?php

function getAllNnt($conn)
{
    $sql = "SELECT nnt FROM these;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $AllNnt = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $AllNnt;
}


function getAllPersonnesIdRef($conn)
{
    $sql = "SELECT idRef FROM personne;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $AllNnt = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $AllNnt;
}

function searchEtablissement(Etablissement $obj, PDO $conn)
{
    $idRef = $obj->getIdRef();
    $nom = $obj->getName();

    // Si l'idRef est null on compare alors juste le nom de l'établissement
    if ($idRef == null) {
        $sql = "SELECT id FROM etablissement WHERE nom = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($nom));
    } else { // Sinon on compare l'idRef et le nom
        $sql = "SELECT id FROM etablissement WHERE idRef = :idRef AND nom = :nom;";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":idRef" => $idRef, ":nom" => $nom));
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result["id"];
}

/**
 * Ajoute dans la base le lien entre une thèse et son établissement de soutenance
 * @param These $these Thèse
 * @param Etablissement $etablissement Etablissement de soutenance
 * 
 * @return void
 */
function addLiaisonEtablissement(These $these, PDO $conn)
{
    $nnt = $these->getNnt();
    $liste_etablissements = $these->getEtablissements();
    $sql = "INSERT INTO these_etablissement (nnt, id_etablissement) VALUES (:nnt, :id_etablissement);";
    $stmt = $conn->prepare($sql);
    foreach ($liste_etablissements as $etablissemntObj) {
        $id = $etablissemntObj->getBddID();
        $stmt->execute(array(":nnt" => $nnt, ":id_etablissement" => $id));
    }
}

/**
 * Prends une liste en paramètre et retourne un string de string séparés par des virgules
 * pour l'utiliser dans une requête SQL
 * 
 * @param array $array
 * @return string
 */
function implodeToSQL($array)
{
    $sql = "";
    foreach ($array as $value) {
        $sql .= "'" . $value . "',";
    }
    $sql = substr($sql, 0, -1); // Enlève la dernière virgulee
    return $sql;
}

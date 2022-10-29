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

function searchEtablissement($liste, Etablissement $obj)
{
    $idRef = $obj->getIdRef();
    $nom = $obj->getName();
    foreach ($liste as $etablissement) {
        if ($etablissement->getIdRef() == $idRef && strcmp($etablissement->getName(), $nom) == 0) {
            return $etablissement->getBddID();
        }
    }
    return -1;
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

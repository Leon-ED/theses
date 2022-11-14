<?php
/**
 * Fonctions générales pour l'application PHP
 *
 **/


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
    foreach ($liste_etablissements as $etablissementOBJ) {
        $id = $etablissementOBJ->getIdBase();
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

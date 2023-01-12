<?php



function getRatioAccessible(PDO $conn)
{
    $sql = "SELECT COUNT(*) as disponible FROM these WHERE estAccessible = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $disponible = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["disponible"];

    $sql = "SELECT COUNT(*) as non_disponible FROM these WHERE estAccessible = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $non_disponible = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["non_disponible"];

    return array(
        "disponible" => $disponible,
        "non_disponible" => $non_disponible
    );
}
function getRatioAccessibleAnnees(PDO $conn, $listeAnnees)
{
    $listeDisponible = array();
    $listeNonDisponible = array();
    foreach ($listeAnnees as $annee) {
        $sql = "SELECT COUNT(*) as disponible FROM these WHERE estAccessible = 1 AND YEAR(datesoutenance) = :annee";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":annee" => $annee));
        $disponible = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["disponible"];

        $sql = "SELECT COUNT(*) as non_disponible FROM these WHERE estAccessible = 0 AND YEAR(datesoutenance) = :annee";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":annee" => $annee));
        $non_disponible = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["non_disponible"];

        $listeDisponible[] = $disponible;
        $listeNonDisponible[] = $non_disponible;

        // echo $annee . " : " . $disponible . " / " . $non_disponible . "
        // <br>";
    }
    return array(
        "disponible" => $listeDisponible,
        "non_disponible" => $listeNonDisponible
    );
}

function getCumulThesesAnnees(PDO $conn, $listeAnnees){
    $total = 0;
    $listeTotal = array();
    foreach ($listeAnnees as $annee) {
        $sql = "SELECT COUNT(*) as total FROM these WHERE YEAR(datesoutenance) = :annee";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":annee" => $annee));
        $total_annee = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["total"];
        $total += $total_annee;
        $listeTotal[] = $total;


        // echo $annee . " : " . $disponible . " / " . $non_disponible . "
        // <br>";
    }
    return $listeTotal;
}

function getListeAnnees(PDO $conn)
{
    $sql = "SELECT DISTINCT YEAR(datesoutenance) annee FROM these ORDER BY datesoutenance ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $result;
}

function getCompteMotsCles(PDO $conn){
    try {
    $sql = "SELECT mot, COUNT(ts.idMot) as nb FROM these_sujet as ts,sujets WHERE ts.idMot = sujets.idMot GROUP BY ts.idMot ORDER BY nb DESC LIMIT 100";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

}
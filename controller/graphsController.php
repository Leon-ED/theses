<?php
/* Classe gérant l'obtention des données afin de faire fonctionner les graphiques */
class GraphsController
{


    private $fromSearch = false;
    private $searchResults = null;

    public function __construct($fromSearch = false, $searchResults = null)
    {
        $this->fromSearch = $fromSearch;
        $this->searchResults = $searchResults;
    }

    function getRatioAccessible(PDO $conn)
    {
        $disponible = 0;
        $non_disponible = 0;
        if (!$this->fromSearch) {
            $sql = "SELECT COUNT(*) as disponible FROM these WHERE estAccessible = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $disponible = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["disponible"];

            $sql = "SELECT COUNT(*) as non_disponible FROM these WHERE estAccessible = 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $non_disponible = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["non_disponible"];
        } else {
            foreach ($this->searchResults as $these) {
                if ($these->estAccessible()) {
                    $disponible++;
                } else {
                    $non_disponible++;
                }
            }
        }
        return array(
            "disponible" => $disponible,
            "non_disponible" => $non_disponible
        );
    }
    function getRatioAccessibleAnnees(PDO $conn, $listeAnnees)
    {
        $listeDisponible = array();
        $listeNonDisponible = array();
        if (!$this->fromSearch) {
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
        }else{
            foreach ($listeAnnees as $annee) {
                $disponible = 0;
                $non_disponible = 0;
                foreach ($this->searchResults as $these) {
                    if ($these->estAccessible() && $these->getDateSoutenance("Y") == $annee) {
                        $disponible++;
                    } else if (!$these->estAccessible() && $these->getDateSoutenance("Y") == $annee) {
                        $non_disponible++;
                    }
                }
                $listeDisponible[] = $disponible;
                $listeNonDisponible[] = $non_disponible;
            }
        }

        return array(
            "disponible" => $listeDisponible,
            "non_disponible" => $listeNonDisponible
        );
    }

    function getCumulThesesAnnees(PDO $conn, $listeAnnees, $searchResults = null)
    {
        $total = 0;
        $listeTotal = array();
        if(!$this->fromSearch){
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
    }else{
        foreach ($listeAnnees as $annee) {
            $total_annee = 0;
            foreach ($this->searchResults as $these) {
                if ($these->getDateSoutenance("Y") == $annee) {
                    $total_annee++;
                }
            }
            $total += $total_annee;
            $listeTotal[] = $total;
        }
    }
        return $listeTotal;
    }

    function getListeAnnees(PDO $conn)
    {
        if (!$this->fromSearch) {
            $sql = "SELECT DISTINCT YEAR(datesoutenance) annee FROM these ORDER BY annee ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $result = array();
            foreach ($this->searchResults as $these) {
                $result[] = $these->getDateSoutenance("Y");
            }
            $result = array_unique($result);
            sort($result);
        }
        return $result;
    }

    function getCompteMotsCles(PDO $conn, $searhResults)
    {
        if(!$this->fromSearch){
            $sql = "SELECT mot, COUNT(ts.idMot) as nb FROM these_sujet as ts,sujets WHERE ts.idMot = sujets.idMot GROUP BY ts.idMot ORDER BY nb DESC LIMIT 100";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $listeNNT  = array();
            foreach ($this->searchResults as $these) {
                $listeNNT[] = $these->getNNT();
            }
            $listeNNT = implode("','", $listeNNT);
            $sql = "SELECT mot, COUNT(ts.idMot) as nb FROM these_sujet as ts,sujets WHERE ts.idMot = sujets.idMot AND ts.nnt IN ('$listeNNT') GROUP BY ts.idMot ORDER BY nb DESC LIMIT 100";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            

        }

        return $result;

            
    }
}

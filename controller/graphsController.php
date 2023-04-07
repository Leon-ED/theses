<?php
/* Classe gérant l'obtention des données afin de faire fonctionner les graphiques */
class GraphsController
{


    private $fromSearch = false;
    private $searchResults = null;
    private $nombreTheses = 0;

    public function __construct($fromSearch = false, $searchResults = null)
    {
        $this->fromSearch = $fromSearch;
        $this->searchResults = $searchResults;
        if ($fromSearch) {
            $this->nombreTheses = count($searchResults);
        }
    }

    function getNombreTheses(PDO $conn)
    {
        if (!$this->fromSearch) {
            $sql = "SELECT COUNT(*) as total FROM these";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["total"];
        } else {
            return count($this->searchResults);
        }
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

            $non_disponible = $this->getNombreTheses($conn) - $disponible;
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
            $sql = "SELECT 
            YEAR(dateSoutenance) AS annee,
            SUM(CASE WHEN estAccessible = 1 THEN 1 ELSE 0 END) AS nb_theses_disponibles,
            SUM(CASE WHEN estAccessible = 0 THEN 1 ELSE 0 END) AS nb_theses_non_disponibles
          FROM 
            these
          GROUP BY 
            YEAR(dateSoutenance)
          ORDER BY annee ASC";


            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultats as $resultat) {
                $listeDisponible[] = $resultat["nb_theses_disponibles"];
                $listeNonDisponible[] = $resultat["nb_theses_non_disponibles"];
            }
        } else {
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

    function getCumulThesesAnnees(PDO $conn, $listeAnnees)
    {
        $total = 0;
        $listeTotal = array();
        if (!$this->fromSearch) {
            $sql = "SELECT COUNT(*) AS nombre_theses
            FROM these
            GROUP BY YEAR(dateSoutenance)
            ORDER BY YEAR(dateSoutenance) ASC;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            array_map(function ($resultat) use (&$total, &$listeTotal) {
                $total += $resultat["nombre_theses"];
                $listeTotal[] = $total;
            }, $resultats);
        } else {
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

    function getCompteMotsCles(PDO $conn)
    {
        if (!$this->fromSearch) {
            $sql = "SELECT mot, COUNT(ts.idMot) as nb FROM these_sujet as ts,sujets WHERE ts.idMot = sujets.idMot GROUP BY ts.idMot ORDER BY nb DESC LIMIT 100";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
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

    function getRegions(PDO $conn)
    {
        $sql = "
        SELECT
    CASE
        WHEN e.region = 'Auvergne-Rhône-Alpes' THEN 'fr-ara'
        WHEN e.region = 'Bourgogne-Franche-Comté' THEN 'fr-bfc'
        WHEN e.region = 'Bretagne' THEN 'fr-bre'
        WHEN e.region = 'Centre-Val de Loire' THEN 'fr-cvl'
        WHEN e.region = 'Corse' THEN 'fr-cor'
        WHEN e.region = 'Grand Est' THEN 'fr-ges'
        WHEN e.region = 'Hauts-de-France' THEN 'fr-hdf'
        WHEN e.region = 'Île-de-France' THEN 'fr-idf'
        WHEN e.region = 'Normandie' THEN 'fr-nor'
        WHEN e.region = 'Nouvelle-Aquitaine' THEN 'fr-naq'
        WHEN e.region = 'Occitanie' THEN 'fr-occ'
        WHEN e.region = 'Pays de la Loire' THEN 'fr-pdl'
        WHEN e.region = 'Provence-Alpes-Côte d\'Azur' THEN 'fr-pac'
    END AS id,
    COUNT(*) AS compte
FROM
    these_etablissement te
    JOIN etablissement e ON te.id_etablissement = e.id
GROUP BY
    e.region;
";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getRatioLangues(PDO $conn)
    {
        if (!$this->fromSearch) {
            $sql = "
            SELECT 
                COUNT(*) AS compte,
                CASE
                    WHEN langue = 'fr' THEN 'Francais'
                    WHEN langue = 'en' THEN 'Anglais'
                    ELSE langue
                END AS id
            FROM
                these
            GROUP BY
                id;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listeNNT  = array();
            foreach ($this->searchResults as $these) {
                $listeNNT[] = $these->getNNT();
            }
            $listeNNT = implode("','", $listeNNT);
            $sql = "
            SELECT 
                COUNT(*) AS compte,
                CASE
                    WHEN langue = 'fr' THEN 'fr'
                    WHEN langue = 'en' THEN 'en'
                    ELSE langue
                END AS id
            FROM
                these
            WHERE
                nnt IN ('$listeNNT')
            GROUP BY
                id;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    function soutenancesParMois($conn)
    {
        if (!$this->fromSearch) {
            $sql = "
                SELECT 
        CASE MONTH(dateSoutenance)
            WHEN 1 THEN 'Janvier'
            WHEN 2 THEN 'Février'
            WHEN 3 THEN 'Mars'
            WHEN 4 THEN 'Avril'
            WHEN 5 THEN 'Mai'
            WHEN 6 THEN 'Juin'
            WHEN 7 THEN 'Juillet'
            WHEN 8 THEN 'Août'
            WHEN 9 THEN 'Septembre'
            WHEN 10 THEN 'Octobre'
            WHEN 11 THEN 'Novembre'
            WHEN 12 THEN 'Décembre'
        END AS mois,
        COUNT(*) AS compte
    FROM these
    GROUP BY mois
    ORDER BY MONTH(dateSoutenance) ASC
    ;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listeNNT  = array();
            foreach ($this->searchResults as $these) {
                $listeNNT[] = $these->getNNT();
            }
            $listeNNT = implode("','", $listeNNT);
            $sql = "
            SELECT 
    CASE MONTH(dateSoutenance)
        WHEN 1 THEN 'Janvier'
        WHEN 2 THEN 'Février'
        WHEN 3 THEN 'Mars'
        WHEN 4 THEN 'Avril'
        WHEN 5 THEN 'Mai'
        WHEN 6 THEN 'Juin'
        WHEN 7 THEN 'Juillet'
        WHEN 8 THEN 'Août'
        WHEN 9 THEN 'Septembre'
        WHEN 10 THEN 'Octobre'
        WHEN 11 THEN 'Novembre'
        WHEN 12 THEN 'Décembre'
    END AS mois,
    COUNT(*) AS compte
FROM these
WHERE nnt IN ('$listeNNT')
GROUP BY mois
ORDER BY MONTH(dateSoutenance) ASC

;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }
    function getDisciplines(PDO $conn)
    {
        if (!$this->fromSearch) {
            $sql = "
            SELECT
                discipline,
                COUNT(*) AS compte
            FROM
                these
            GROUP BY
                discipline
            ORDER BY compte DESC
            LIMIT 20;
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listeNNT  = array();
            foreach ($this->searchResults as $these) {
                $listeNNT[] = $these->getNNT();
            }
            $listeNNT = implode("','", $listeNNT);
            $sql = "
            SELECT
                discipline,
                COUNT(*) AS compte
            FROM
                these
            WHERE
                nnt IN ('$listeNNT')
            GROUP BY
                discipline
            ORDER BY compte DESC
            LIMIT 100;
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }
}

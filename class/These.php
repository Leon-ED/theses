<?php

/**
 * Objet thèse
 * 
 */
class These
{
    /**TODO : Implétenter la classe abstraite **/

    //Champs
    private  $idThese;
    private  $titreThese_fr;
    private  $titreThese_en;
    private  $resume_fr;
    private  $resume_en;
    private  $dateSoutance;
    private  $discipline;
    private  $estSoutenue;
    private  $estAccessible;
    private  $langueThese;
    private $nnt;
    private $iddoc;
    private $liste_etablissements;




    public function __construct()
    {
        $this->liste_etablissements = array();
    }


    static function getAllNNT($conn)
    {
        $sql = "SELECT nnt FROM these;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $AllNnt = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt->closeCursor();
        return $AllNnt;
    }



    /**
     * Mets les titres de la thèse (français et anglais)
     * @param string $titre_fr Titre français
     * @param string $titre_en Titre anglais
     * @return these
     */
    public function setTitre($titre_fr, $titre_en)
    {
        $this->titreThese_fr = $titre_fr;
        $this->titreThese_en = $titre_en;
        return $this;
    }

    /**
     * Mets les résumés de la thèse (français et anglais)
     * @param string $resume_fr Résumé français
     * @param string $resume_en Résumé anglais
     * @return these
     */
    public function setResume($resume_fr, $resume_en)
    {
        $this->resume_fr = $resume_fr;
        $this->resume_en = $resume_en;
        return $this;
    }

    // /**
    //  * Mets les auteurs de la thèse (nom et prénom)
    //  * @param string $nomAuteur Nom de l'auteur
    //  * @param string $prenomAuteur Prénom de l'auteur
    //  * @return these
    //  */
    // public function setAuteur($nom, $prenom)
    // {
    //     $this->nomA = $nom;
    //     $this->prenomAuteur = $prenom;
    //     return $this;
    // }

    /**
     * Mets la date de soutenance de la thèse
     * @param string $dateSoutenance Date de soutenance
     * @return these
     */
    public function setDateSoutenance($date)
    {
        $date = date('Y-m-d', strtotime($date));
        $this->dateSoutance = $date;
        return $this;
    }

    /**
     * Mets l'Iddoc de la thèse
     * @param string $iddoc Iddoc de la thèse
     * @return these
     */
    public function setIddoc($iddoc)
    {
        $this->iddoc = $iddoc;
        return $this;
    }

    /**
     * Mets le NNT de la thèse
     * @param string $nnt NNT de la thèse
     * @return these
     */
    public function setNNT($nnt)
    {
        $this->nnt = $nnt;
        return $this;
    }

    /**
     * Ajoute un établissement à la liste des établissements de la thèse
     * @param Etablissement $etablissement Etablissement à ajouter
     * @return these
     * 
     */
    public function addEtablissement(Etablissement $etablissement)
    {
        $this->liste_etablissements[] = $etablissement;
        return $this;
    }

    public function getEtablissements()
    {
        return $this->liste_etablissements;
    }

    /**
     * Renvoie le NNT de la thèse
     * @param string $nnt NNT de la thèse
     * @return string
     */
    public function getNNT(): string
    {
        return $this->nnt;
    }



    /**
     * Mets la langue de la thèse
     * @param string $langue Langue de la thèse
     * @return these
     */
    public function setLangueThese($langue)
    {
        $this->langueThese = $langue;
        return $this;
    }

    /**
     * Mets le statut de la thèse
     * @param string $statut Statut de la thèse
     * @return these
     */
    public function setAccessible($accessible)
    {
        if($accessible == "oui" || $accessible == "non")
            $this->estAccessible = strcmp($accessible, "oui") == 0 ? 1 : 0;
        else
            $this->estAccessible = (int) $accessible;
        return $this;
    }

    /**
     * Renvoie si la thèse est accessible ou non
     * @return bool
     */
    public function estAccessible()
    {
        return $this->estAccessible;
    }


    public function setSoutenue($soutenue)
    {
        $this->estSoutenue = strcmp($soutenue, "oui") == 0 ? 1 : 0;
        return $this;
    }

    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;
        return $this;
    }


    public function setInfosThese(PDO $conn): These
    {
        $sql = "SELECT * FROM these WHERE idThese = :idThese";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":idThese", $this->idThese);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->setTitre($result["titre_fr"], $result["titre_en"]);
        $this->setResume($result["resume_fr"], $result["resume_en"]);
        $this->setDateSoutenance($result["dateSoutenance"]);
        $this->setDiscipline($result["discipline"]);
        $this->setSoutenue($result["estSoutenue"]);
        $this->setAccessible($result["estAccessible"]);
        $this->setLangueThese($result["langue"]);
        $this->setIddoc($result["iddoc"]);


        return $this;
    }



    public function setTheseId($theseId)
    {
        $this->idThese = $theseId;
        return $this;
    }

    /**
     * Retourne le titre de la thèse
     * @return string
     */
    public function getTitre(): string
    {
        $titre = "Erreur lors de la récupération du titre";
        if ($this->titreThese_en != null) {
            $titre = $this->titreThese_en;
        }
        if ($this->titreThese_fr != null) {
            $titre =  $this->titreThese_fr;
        }
        return $titre;
    }

    /**
     * Retourne le(s) nom(s) et prénom(s) de(s) directeur(s) de thèse
     * @param PDO $conn Connexion à la base de données
     * @return array Nom et prénom du/des directeur(s) de thèse
     */
    public function getDirecteur($conn): array
    {
        $sql = "SELECT nomPersonne,prenomPersonne FROM personne,a_dirige WHERE personne.idPersonne = a_dirige.idPersonne AND :nnt = a_dirige.nnt";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nnt", $this->nnt);
        $stmt->execute();
        $result = $stmt->fetch();
        $liste = array();
        while ($result != null) {
            $liste[] = $result["nomPersonne"] . " " . $result["prenomPersonne"];
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $liste;
    }

    /**
     * Retourne le nom et prénom de l'auteur de la thèse
     * @param PDO $conn Connexion à la base de données
     * @return array Liste des auteurs de la thèse
     */
    public function getAuteur($conn): array
    {
        $sql = "SELECT nomPersonne,prenomPersonne FROM personne,a_ecrit WHERE personne.idPersonne = a_ecrit.idPersonne AND :nnt = a_ecrit.nnt";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nnt", $this->nnt);
        $stmt->execute();
        $result = $stmt->fetch();
        while ($result != null) {
            $liste[] = $result["nomPersonne"] . " " . $result["prenomPersonne"];
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $liste;
    }

    public function getDiscine(): string
    {
        return $this->discipline;
    }


    /**
     * Insert la thèse dans la base de donnée
     * @param Statement $stmt Requête préparée pour insérer une thèse
     * @return none
     */
    public function insertThese($conn)
    {
        try {
            $sql = "INSERT INTO these(titre_fr,titre_en,dateSoutenance,langue,estSoutenue,estAccessible,discipline,nnt,iddoc,resume_fr,resume_en) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
            $insertSTMT = $conn->prepare($sql);
            $insertSTMT->execute([$this->titreThese_fr, $this->titreThese_en, $this->dateSoutance, $this->langueThese, $this->estSoutenue, $this->estAccessible, $this->discipline, $this->nnt, $this->iddoc, $this->resume_fr, $this->resume_en]);
            return 0;
        } catch (PDOException $e) {
            echo $this->nnt;
            // print_r($conn->errorInfo());
            echo $e->getMessage();
            // echo "<br>";
        }
    }

    function getTheseId()
    {
        return $this->idThese;
    }


    /**
     * Retourne la date de la soutenance formatée (jj/mm/aaaa)
     * @return string Date de la soutenance
     */
    function getDateSoutenance($format = "d/m/Y"): string
    {
        $date = new DateTime($this->dateSoutance);
        return $date->format($format);
    }

    /**
     * Retourne le lien vers la thèse sur theses.fr
     * @return string Lien vers la thèse sur theses.fr
     */
    function getLink(): string
    {
        return "http://www.theses.fr/" . htmlspecialchars($this->nnt);
    }

    /**
     * Retourne la liste des mots clés de la thèse
     * @param PDO $conn Connexion à la base de données
     * @return a  Liste des mots clés de la thèse ou un message d'erreur
     */
    function getMotsCles($conn)
    {
        $sql = "SELECT DISTINCT lst.idMot id, lst.mot mot FROM sujets lst,these_sujet mc WHERE lst.idMot = mc.idMot AND mc.nnt = :nnt";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nnt", $this->nnt);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result == null) {
            return "Aucun mot clé";
        }
        return $result;
    }

    /**
     * Retourne l'établissement d'origine de la thèse
     * @param PDO $conn Connexion à la base de données
     * @return array Liste des Etablissements
     */
    function getEtablissement($conn): array
    {
        $sql = "SELECT nom FROM etablissement,these_etablissement WHERE etablissement.id = these_etablissement.id_etablissement AND these_etablissement.nnt = :nnt";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nnt", $this->nnt);
        $stmt->execute();
        $result = $stmt->fetch();
        while ($result != null) {
            $liste[] = $result["nom"];
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $liste;
    }
}

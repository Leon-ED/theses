<?php

/**
 * Classe représentant un établissement d'une thèse (Etablissement de soutenance et Ecole doctorale)
 *
 */
class Etablissement extends AbstractObjet
{


    public static function checkInArray($etablissementOBJ, $listeEtablissement)
    {
        if (!($etablissementOBJ instanceof Etablissement)) {
            throw new InvalidArgumentException("Le premier paramètre doit être un objet de type Etablissement");
        }

        foreach ($listeEtablissement as $etablissement) {
            if ($etablissementOBJ->equals($etablissement)) {
                return $etablissement;
            }
        }

        return null;
    }


    public static function getListFromBase($conn)
    {
        $sql = "SELECT * FROM etablissement";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $etablissementList = array();
        foreach ($result as $etablissement) {
            $etabliseementOBJ = new Etablissement();
            $etabliseementOBJ
                ->setNom($etablissement['nom'])
                ->setIdBase($etablissement['id'])
                ->setIdRef($etablissement['idRef']);

            $etablissementList[] = $etabliseementOBJ;
        }
        return $etablissementList;
    }


    private $name;

    public function __construct()
    {
    }


    function insertToBase($conn)
    {
        try {
            $sql = "INSERT INTO etablissement (nom,idRef) VALUES (:name,:idRef);";
            $insertEtablissement = $conn->prepare($sql);
            $insertEtablissement->execute(array(
                ":name" => $this->name,
                ":idRef" => $this->getIdRef()
            ));
            $this->setIdBase($conn->lastInsertId());
        } catch (PDOException $e) {
            echo "Erreur insertion etablissement $this->name : " . $this->getIdRef();
            echo $e->getMessage();
        }
    }


    public function updateToBase($conn)
    {
        if (parent::getIdRef() == null) {
            $UpdateEtablissementStmt = $conn->prepare("UPDATE etablissement SET nom= :nom WHERE nom= :nom AND idRef IS NULL");
            $UpdateEtablissementStmt->execute(array(
                ":nom" => $this->nom,
            ));
            return;
        }
        $UpdateEtablissementStmt = $conn->prepare("UPDATE etablissement SET nom= :nom WHERE nom= :nom AND idRef = ?");
        $UpdateEtablissementStmt->execute(array(
            ":nom" => $this->nom,
            ":idRef" => $this->getIdRef()
        ));
    }

    /**
     * Mets le nom de l'établissement
     * @param string $name Nom de l'établissement
     * @return etablissement
     */
    public function setNom($name)
    {
        $this->name = $name;
        return $this;
    }



    /**
     * Récupère le nom de l'établissement
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }




    /**
     * Vérifie si deux objets sont identiques
     * @param Object $obj
     * @return bool
     */
    public function equals($obj)
    {
        if (!($obj instanceof Etablissement)) {
            return false;
        }
        if ($this->getIdBase == $obj->getIdBase()) {
            return true;
        }
        if ($this->getIdRef() != null && $this->getIdRef() == $obj->getIdRef()) {
            return true;
        }
        if ($this->getName() == $obj->getName()) {
            return true;
        }
    }
}

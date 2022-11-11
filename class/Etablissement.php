<?php

class Etablissement extends AbstractObjet
{

    /**
     * Retourne true si l'établissement founit est déjà dans la liste donnée
     * @param Etablissement $etabliseementOBJ L'établissement à chercher
     * @param array $listeEtablissement La liste d'établissement
     * @return Etablissement|null Retourne l'établissement trouvé ou null si il n'est pas trouvé
     * @throws InvalidArgumentException Si l'un des paramètres n'est pas du bon type
     */
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

    /**
     * Renvoie la liste des établissements présents dans la base de données
     * @param PDO $conn La connexion à la base de données
     * @return array La liste des établissements
     */
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

    /**
     * Mets le nom de l'établissement
     * @param string $name Nom de l'établissement
     * @return void
     */
    function insertToBase($conn)
    {
        try {
            $sql = "INSERT INTO etablissement (nom,idRef) VALUES (:name,:idRef);";
            $insertEtablissement = $conn->prepare($sql);
            $insertEtablissement->execute(array(
                ":name" => $this->name,
                ":idRef" => $this->idRef
            ));
            $this->setIdBase($conn->lastInsertId());
        } catch (PDOException $e) {
            echo "Erreur insertion etablissement $this->name : $this->idRef";
            echo $e->getMessage();
        }
    }

    /**
     * Mets à jour  un établissement dans la base de données
     * @param PDO $conn La connexion à la base de données
     * @return void
     */
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
            ":idRef" => $this->idRef
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
        return parent::getIdRef() == $obj->getIdRef() ||
            $this->name == $obj->getName();
    }
}

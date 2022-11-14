<?php

class Personne extends AbstractObjet
{
    private $nom;
    private $prenom;
    private $theseNNT;

    public static function getListFromBase($conn)
    {
        $sql = "SELECT * FROM personne";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $personnesList = array();
        foreach ($result as $personne) {
            $personneOBJ = new Personne();
            $personneOBJ
                ->setNom($personne['nomPersonne'])
                ->setPrenom($personne['prenomPersonne'])
                ->setIdBase($personne['idPersonne'])
                ->setIdRef($personne['idRef']);

            $personnesList[] = $personneOBJ;
        }
        return $personnesList;
    }


    public static function checkInArray($personneOBJ, $liste)
    {
        if (!($personneOBJ instanceof Personne)) {
            throw new InvalidArgumentException("Le premier paramètre doit être un objet de type Personne");
        }

        foreach ($liste as $personne) {
            if ($personneOBJ->equals($personne)) {
                return $personne;
            }
        }
        return null;
    }



    public function insertToBase($conn)
    {
        $insertPersonneStmt = $conn->prepare("INSERT INTO personne(nomPersonne,prenomPersonne,idRef) VALUES(?,?,?)");
        $insertPersonneStmt->execute(array($this->nom, $this->prenom, $this->getIdRef()));
        parent::setIdBase($conn->lastInsertId());
    }

    public function updateToBase($conn)
    {
        if (parent::getIdRef() == null) {
            $insertPersonneStmt = $conn->prepare("UPDATE personne SET nomPersonne= :nom ,prenomPersonne= :prenom WHERE nomPersonne= :nom  AND prenomPersonne= :prenom AND idRef IS NULL");
            $insertPersonneStmt->execute(array(
                ":nom" => $this->nom,
                ":prenom" => $this->prenom
            ));
            return;
        }
        $insertPersonneStmt = $conn->prepare("UPDATE personne SET nomPersonne=?,prenomPersonne=? WHERE idRef = ?");
        $insertPersonneStmt->execute(array($this->nom, $this->prenom, parent::getIdRef()));
    }

    public function equals($obj)
    {
        if (!($obj instanceof Personne)) {
            return false;
        }
        if (parent::getIdBase() == $obj->getIdBase())
            return true;
        return $this->nom == $obj->getNom() && $this->prenom == $obj->getPrenom() && parent::getIdRef() == $obj->getIdRef();
    }

    /**
     * Renvoie le nom de la personne
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Renvoie le prénom de la personne
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }


    /**
     * Mets le prénom de la personne
     * @param string $prenom Prénom de la personne
     * @return personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * Mets le nom de la personne
     * @param string $nom Nom de la personne
     * @return personne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }


    /**
     * Insère l'objet en tant qu'auteur d'une thèse dans la base
     * @param PDO $conn
     * @param $theseNNT
     * @return void
     */
    public function insertAuteur($conn, $theseNNT)
    {
        $insertAuteurStmt = $conn->prepare("INSERT INTO a_ecrit(idPersonne,nnt) VALUES(?,?)");
        $insertAuteurStmt->execute(array(parent::getIdBase(), $theseNNT));
    }

    /**
     * Insère l'objet en tant que directeur dans la base
     * @param $conn
     * @param $theseNNT
     * @return void
     */
    public function insertDirecteur($conn, $theseNNT)
    {
        $insertAuteurStmt = $conn->prepare("INSERT INTO a_dirige(idPersonne,nnt) VALUES(?,?)");
        $insertAuteurStmt->execute(array(parent::getIdBase(), $theseNNT));
    }

    /**
     * Mets le NNT de la thèse à la quelle la personne est liée
     * @param string $nnt NNT de la thèse
     * @return personne
     */
    public function setTheseNNT($theseNnt)
    {
        $this->theseNnt = $theseNnt;
        return $this;
    }
}

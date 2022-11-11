<?php

class Sujet extends AbstractObjet
{
    private $sujet;


    static function  getListFromBase($conn)
    {
        $sql = "SELECT * FROM sujets";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $AllSujets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $AllSujets;
    }

    /**
     * Retourne l'Objet sujet s'il le même est dans la liste
     * @param Sujet $sujetOBJ
     * @param array $liste La liste
     * @return Sujet|null Retourne
     * @throws InvalidArgumentException Si l'un des paramètres n'est pas du bon type
     */
    static function checkInArray($sujetOBJ, $liste)
    {
        if (!($sujetOBJ instanceof Sujet)) {
            throw new InvalidArgumentException("Le premier paramètre doit être un objet de type Sujet");
        }
        foreach ($liste as $sujet) {
            if ($sujetOBJ->equals($sujet)) {
                return $sujet;
            }
        }
        return null;
    }


    function insertToBase($conn)
    {
        $sql = "INSERT INTO sujets (mot) VALUES (:mot)";
        $insertSujet = $conn->prepare($sql);
        $insertSujet->execute(array(
            'mot' => $this->getSujet()
        ));
        $this->setIdBase($conn->lastInsertId());
        $insertSujet->closeCursor();
    }

    function updateToBase($conn)
    {
        $sql = "UPDATE sujets SET mot = :mot WHERE id = :id";
        $updateSujet = $conn->prepare($sql);
        $updateSujet->execute(array(
            'mot' => $this->getSujet(),
            'id' => $this->getIdBase()
        ));
        $updateSujet->closeCursor();
    }



    /**
     * Retourne le sujet
     * @return Sujet
     */
    function setSujet(string $sujet)
    {
        $this->sujet = $sujet;
        return $this;
    }

    /**
     * Retourne le sujet
     * @return string
     */
    function getSujet(): string
    {
        return $this->sujet;
    }

    /**
     * Vérifie si deux objets sont identiques
     * @param Object $obj
     * @return bool
     */
    public function equals($obj)
    {
        if (!($obj instanceof Sujet)) {
            return false;
        }
        if (parent::getIdBase() == $obj->getIdBase())
            return true;
        return $this->sujet == $obj->sujet;
    }

    /**
     * Fait le lien entre un sujet et une thèse
     */
    function insertSujet(PDO $conn, $theseNNT)
    {
        $sql = "INSERT into these_sujet(nnt,idMot) VALUES (:nnt,:idMot)";
        $insertSujet = $conn->prepare($sql);
        $insertSujet->execute(array(
            'nnt' => $theseNNT,
            'idMot' => $this->getIdBase()
        ));
        $insertSujet->closeCursor();
    }
}

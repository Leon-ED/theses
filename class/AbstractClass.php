<?php

abstract class AbstractObjet
{

    private $idRef; // Idref de l'objet
    private $idBase; // Id de l'objet dans la base de données


    abstract public function insertToBase($conn);
    abstract public function equals($obj);
    abstract public function updateToBase($conn);
    abstract public static function getListFromBase($conn);
    abstract public static function checkInArray($personneOBJ, $liste);



    /**
     * Mets l'id de l'objet dans la base de données
     * @param int $idBase Id de l'objet dans la base de données
     * @return AbstractObjet
     */
    public function setIdBase($idBase)
    {
        $this->idBase = $idBase;
        return $this;
    }

    /**
     * Mets l'idref de l'objet
     * @param int $idRef Idref de l'objet
     * @return AbstractObjet
     * @throws InvalidArgumentException Si l'idref est trop long 
     */
    public function setIdRef($idRef)
    {
        if ($idRef != null && strlen($idRef) > 12) {
            throw new InvalidArgumentException("L'idref ne peut pas dépasser 12 caractères");
        }
        $this->idRef = $idRef;
        return $this;
    }


    /**
     * Retourne l'id de l'objet dans la base de données
     * @return int Id de l'objet dans la base de données
     */
    public function getIdBase()
    {
        return $this->idBase;
    }

    /**
     * Retourne l'idref de l'objet
     * @return string Idref de l'objet
     */
    public function getIdRef()
    {
        return $this->idRef;
    }
}

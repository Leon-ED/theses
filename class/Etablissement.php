<?php

class Etablissement{



    private $name;
    private $idRef;
    private $bddID;

    public function __construct(){
        
    }

    /**
     * Mets le nom de l'établissement
     * @param string $name Nom de l'établissement
     * @return int id base de l'établissement
     */
     function insertEtablissement($conn){
        try{
        $sql = "INSERT INTO etablissement (nom,idRef) VALUES (:name,:idRef);";
        $insertEtablissement = $conn->prepare($sql);
        $insertEtablissement->execute(array(
            ":name" => $this->name,
            ":idRef" => $this->idRef
        ));

        return $conn->lastInsertId();
    }catch(PDOException $e){
        return -1;
    }


    }

    /**
     * Mets le nom de l'établissement
     * @param string $name Nom de l'établissement
     * @return etablissement
     */
    public function setNom($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Mets l'idRef de l'établissement
     * @param string $idRef IdRef de l'établissement
     * @return etablissement
     */
    public function setIdRef($idRef){
        $this->idRef = $idRef;
        return $this;
    }

    /**
     * Récupère le nom de l'établissement
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Récupère l'idRef de l'établissement
     * @return string
     */
    public function getIdRef(){
        return $this->idRef;
    }


    /**
     * Récupère l'id de l'établissement dans la base de données
     * @return int
     */
    public function getBddID(){
        return $this->bddID;

    }

    /**
     * Mets l'id de l'établissement de la base de données
     * @param int $bddID Id de l'établissement dans la base de données
     * @return etablissement
     */
    public function setBddID($bddID){
        $this->bddID = $bddID;
        return $this;
    }

}
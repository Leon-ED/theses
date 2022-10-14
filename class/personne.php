<?php

    class Personne{
        private $idPersonne;
        private $nom;
        private $prenom;
        private $idRef;
        private $theseNnt;




        public function __construct(){

        }

        public function setNom($nom){
            $this->nom = $nom;
            return $this;
        }

        public function setPrenom($prenom){
            $this->prenom = $prenom;
            return $this;
        }

        public function setIdRef($idRef){
            $this->idRef = $idRef;
            return $this;
        }

        public function setTheseNnt($theseNnt){
            $this->theseNnt = $theseNnt;
            return $this;
        }


        public function insertPersonne($conn){

            $sql = "INSERT INTO personne (nomPersonne, prenomPersonne, idRef) VALUES (?,?,?);";
            try{
            $stmt = $conn->prepare($sql);
            $stmt->execute([$this->nom, $this->prenom, $this->idRef]);
            }catch(PDOException $e){
                echo $e->getMessage();
            }

        }

        public function insertDirecteur($conn,$idThese,$nnt){
            $sql = "INSERT INTO a_dirige (idThese, idPersonne, nnt) VALUES (?, ?, ?) RETURNING id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idThese, $this->idPersonne, $nnt]);
            $stmt->close();


        }












    }
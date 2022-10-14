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




        












    }
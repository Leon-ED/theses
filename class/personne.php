<?php

    class Personne{
        private $idPersonne;
        private $nom;
        private $prenom;
        private $idRef;
        private $theseNnt;




        public function __construct(){

        }

        /**
         * Mets le nom de la personne
         * @param string $nom Nom de la personne
         * @return personne
         */
        public function setNom($nom){
            $this->nom = $nom;
            return $this;
        }

        /**
         * Mets le prénom de la personne
         * @param string $prenom Prénom de la personne
         * @return personne
         */
        public function setPrenom($prenom){
            $this->prenom = $prenom;
            return $this;
        }

        /**
         * Mets l'idRef de la personne
         * @param string $idRef IdRef de la personne
         * @return personne
         */
        public function setIdRef($idRef){
            $this->idRef = $idRef;
            return $this;
        }

        /**
         * Mets le NNT de la thèse à la quelle la personne est liée
         * @param string $nnt NNT de la thèse
         * @return personne
         */
        public function setTheseNnt($theseNnt){
            $this->theseNnt = $theseNnt;
            return $this;
        }




        












    }
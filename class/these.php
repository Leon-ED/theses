<?php

/**
 * Objet thèse
 * 
 */
class these {

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
    private  $langue;
    private  $langueThese;
    private $nnt;
    private $iddoc;




    public function __construct(){

    }

    /**
     * Mets les titres de la thèse (français et anglais)
     * @param string $titre_fr Titre français
     * @param string $titre_en Titre anglais
     * @return these
     */
    public function setTitre($titre_fr, $titre_en){
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
    public function setResume($resume_fr, $resume_en){
        $this->resume_fr = $resume_fr;
        $this->resume_en = $resume_en;
        return $this;
    }

    /**
     * Mets les auteurs de la thèse (nom et prénom)
     * @param string $nomAuteur Nom de l'auteur
     * @param string $prenomAuteur Prénom de l'auteur
     * @return these
     */
    public function setAuteur($nom, $prenom){
        $this->nomAuteur = $nom;
        $this->prenomAuteur = $prenom;
        return $this;
    }

    /**
     * Mets la date de soutenance de la thèse
     * @param string $dateSoutenance Date de soutenance
     * @return these
     */
    public function setDateSoutenance($date){
        $this->dateSoutance = $date;
        return $this;
    }

    /**
     * Mets l'Iddoc de la thèse
     * @param string $iddoc Iddoc de la thèse
     * @return these
     */
    public function setIddoc($iddoc){
        $this->iddoc = $iddoc;
        return $this;
    }

    /**
     * Mets le NNT de la thèse
     * @param string $nnt NNT de la thèse
     * @return these
     */
    public function setNnt($nnt){
        $this->nnt = $nnt;
        return $this;
    }

    /**
     * Mets la langue de la thèse
     * @param string $langue Langue de la thèse
     * @return these
     */
    public function setLangueThese($langue){
        $this->langueThese = $langue;
        return $this;
    }

    /**
     * Mets le statut de la thèse
     * @param string $statut Statut de la thèse
     * @return these
     */
    public function setAccessible($accessible){
        $this->estAccessible = strcmp($accessible, "oui") == 0 ? 1 : 0;
        return $this;
    }


    public function setSoutenue($soutenue){
        $this->estSoutenue = strcmp($soutenue, "oui") == 0 ? 1 : 0;
        return $this;
    }

    public function setDiscipline($discipline){
        $this->discipline = $discipline;
        return $this;
    }




    public function setTheseId($theseId){
        $this->idThese = $theseId;
        return $this;
    }


    /**
     * Insert la thèse dans la base de donnée
     * @param Statement $stmt Requête préparée pour insérer une thèse
     * @return none
     */
    public function insertThese($conn){
        try{
        $sql = "INSERT INTO these(titre_fr,titre_en,dateSoutenance,langue,estSoutenue,estAccessible,discipline,nnt,iddoc,resume_fr,resume_en) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
        $insertSTMT = $conn->prepare($sql);
        $insertSTMT->execute([$this->titreThese_fr, $this->titreThese_en, $this->dateSoutance, $this->langueThese, $this->estSoutenue, $this->estAccessible, $this->discipline, $this->nnt, $this->iddoc, $this->resume_fr, $this->resume_en]);
        return 0;
       
        }catch(PDOException $e){
            echo $this->nnt;
            // echo $e->getMessage();
            // echo "<br>";
        }
        
        



    }

    function getTheseId() {
        return $this->idThese;
    }





}
<?php

class these {
    private  $idThese;
    private  $titreThese_fr;
    private  $titreThese_en;
    private  $resume_fr;
    private  $resume_en;

    private  $nomAuteur;
    private  $prenomAuteur;
    

    private  $dateSoutance;
    
    private  $etablissements_soutenance;
    private  $discipline;
    private  $estSoutenue;
    private  $estAccessible;
    private  $langue;

    private  $directeurThese;
    private  $motsCles;
    private  $statut;
    private  $langueThese;

    private $nnt;
    private $iddoc;




    public function __construct(){

    }

    public function setTitre($titre_fr, $titre_en){
        $this->titreThese_fr = $titre_fr;
        $this->titreThese_en = $titre_en;
        return $this;
    }
    
    public function setResume($resume_fr, $resume_en){
        $this->resume_fr = $resume_fr;
        $this->resume_en = $resume_en;
        return $this;
    }

    public function setAuteur($nom, $prenom){
        $this->nomAuteur = $nom;
        $this->prenomAuteur = $prenom;
        return $this;
    }

    public function setDateSoutenance($date){
        $this->dateSoutance = $date;
        return $this;
    }

    public function setIddoc($iddoc){
        $this->iddoc = $iddoc;
        return $this;
    }

    public function setNnt($nnt){
        $this->nnt = $nnt;
        return $this;
    }

    public function setLangueThese($langue){
        $this->langueThese = $langue;
        return $this;
    }

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


    public function insertThese($stmt){
        try{

        $stmt->execute([$this->titreThese_fr, $this->titreThese_en, $this->dateSoutance, $this->langueThese, $this->estSoutenue, $this->estAccessible, $this->discipline, $this->nnt, $this->iddoc, $this->resume_fr, $this->resume_en]);
        
        return 0;
       
        }catch(PDOException $e){
            echo $e->getMessage();
            echo "<br>";
        }
        
        


    }





}
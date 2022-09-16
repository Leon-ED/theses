<?php

class these {
    private string $idThese;
    private string $titreThese;

    private string $nomAuteur;
    private string $prenomAuteur;

    private string $dateSoutance;

    private array $directeurThese;
    private array $motsCles;
    private string $statut;
    private string $langueThese;




    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getLangueThese(): string
    {
        return $this->langueThese;
    }

    /**
     * @param string $langueThese
     */
    public function setLangueThese(string $langueThese): void
    {
        $this->langueThese = $langueThese;
    }

    /**
     * @return string
     */
    public function getIdThese(): string
    {
        return $this->idThese;
    }

    /**
     * @param string $idThese
     */
    public function setIdThese(string $idThese): void
    {
        $this->idThese = $idThese;
    }

    /**
     * @return string
     */
    public function getTitreThese(): string
    {
        return $this->titreThese;
    }

    /**
     * @param string $titreThese
     */
    public function setTitreThese(string $titreThese): void
    {
        $this->titreThese = $titreThese;
    }

    /**
     * @return string
     */
    public function getNomAuteur(): string
    {
        return $this->nomAuteur;
    }

    /**
     * @param string $nomAuteur
     */
    public function setNomAuteur(string $nomAuteur): void
    {
        $this->nomAuteur = $nomAuteur;
    }

    /**
     * @return string
     */
    public function getPrenomAuteur(): string
    {
        return $this->prenomAuteur;
    }

    /**
     * @param string $prenomAuteur
     */
    public function setPrenomAuteur(string $prenomAuteur): void
    {
        $this->prenomAuteur = $prenomAuteur;
    }

    /**
     * @return string
     */
    public function getDateSoutance(): string
    {
        return $this->dateSoutance;
    }

    /**
     * @param string $dateSoutance
     */
    public function setDateSoutance(string $dateSoutance): void
    {
        $this->dateSoutance = $dateSoutance;
    }

    /**
     * @return array
     */
    public function getDirecteurThese(): array
    {
        return $this->directeurThese;
    }

    /**
     * @param array $directeurThese
     */
    public function setDirecteurThese(array $directeurThese): void
    {
        $this->directeurThese = $directeurThese;
    }

    /**
     * @return array
     */
    public function getMotsCles(): array
    {
        return $this->motsCles;
    }

    /**
     * @param array $motsCles
     */
    public function setMotsCles(array $motsCles): void
    {
        $this->motsCles = $motsCles;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }













}
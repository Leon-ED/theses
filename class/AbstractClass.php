<?php


/**
 * Classe abstraite pour les objets de la base de données
 * permet d'avoir des méthodes communes à tous les objets
 */
abstract class AbstractObjet
{

    /**
     * Idref de l'objet
     * @var
     */
    private $idRef;
    /**
     * Id de l'objet dans la base de données
     * @var
     */
    private $idBase;


    /**
     * Insère dans la base de données l'objet
     * @param PDO $conn - La base de données
     * @return none
     */
    abstract public function insertToBase($conn);

    /**
     * Renvoie true si l'objet possède les mêmes champs, false sinon
     * @param $obj
     * @return boolean
     */
    abstract public function equals($obj);

    /**
     * Trouve l'objet dans la base de données et le met à jour
     * @param AbstractObjet $obj - L'objet à mettre à jour
     * @return none
     */
    abstract public function updateToBase($conn);

    /**
     * Renvoie la liste des objets présents dans la base de données correspond à la classe appelée
     * @param PDO $conn La connexion à la base de données
     * @return array La liste des objets PHP
     */
    abstract public static function getListFromBase($conn);

    /**
     * Retourne true si l'établissement founit est déjà dans la liste donnée
     * @param AbstractObjet $objet L'objet à chercher (de la même classe que la classe appelée)
     * @param array $liste La liste d'objet à chercher
     * @return AbstractObjet|null Retourne l'objet trouvé ou null si il n'est pas trouvé
     * @throws InvalidArgumentException Si l'un des paramètres n'est pas du bon type
     */
    abstract public static function checkInArray($objet, $liste);


    /**
     * @param $idBase
     * @return $this
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

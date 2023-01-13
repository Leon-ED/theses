<?php
use \JsonMachine\Items;
try {
require_once("../config/config.php");
spl_autoload_register(require '../libs/json-machine/src/autoloader.php');
/**
 * Module s'occupant de lire le fichier JSON et d'importer toutes les données
 * dans la base
 */


    // $file = file_get_contents("../fichiers/extract_theses.json");
    // $data = json_decode($file, true);
    $data = Items::fromFile('../fichiers/theses-soutenues.json'

);

} catch (Exception $e) {
    echo $e->getMessage();
    die("Une erreur a eu lieu lors de la lecture du fichier JSON");
}

$i = 0;
$etablissements_soutenance = Etablissement::getListFromBase($conn); // Liste de tous les établissements de soutenance
$liste_personnes = Personne::getListFromBase($conn); // Liste de toutes les personnes
$liste_sujets = Sujet::getListFromBase($conn); //Liste de tous les sujets
$liste_NNT = These::getAllNNT($conn); // Liste de tous les NNT
//Tout importer dans la boucle principale est plus pratique mais plus lent ...
try {
foreach ($data as $these) {
    // En local pour tester sans importer toute la data.
    if (DEBUG === true) {
        $i++;
        if ($i >= 20) {
            break;
        }
    }

    // On vérifie que la thèse n'est pas déjà présente dans la base de données
    if (in_array($these->nnt, $liste_NNT)) {
        continue;
    }

    // On récupère les informations de la thèse
    $titre = array("fr" => $these->titres->fr, "en" => $these->titres->en);
    if ($titre == null) {
        $titre = $these["titres"][0];
    }
    $resume = array("fr" => $these->resumes->fr, "en" => $these->resumes->en);
    $auteur = array("nom" => $these->auteur->nom, "prenom" => $these->auteur->prenom);

    // On crée un objet thèse et on lui mets ses champs
    $theseOBJ = new These();
    $theseOBJ
        ->setTitre($titre["fr"], $titre["en"])
        ->setResume($resume["fr"], $resume["en"])
        ->setAuteur($auteur["nom"], $auteur["prenom"])
        ->setDateSoutenance($these->date_soutenance)
        ->setLangueThese($these->langue)
        ->setSoutenue("oui")
        ->setAccessible($these->accessible)
        ->setDiscipline($these->discipline->fr)
        ->setIddoc($these->iddoc)
        ->setNNT($these->nnt);


    // On insère la thèse dans la BDD et on récupère son internal ID.
    $theseOBJ->setTheseId($theseOBJ->insertThese($conn));


    // On boucle sur chaque Auteur de la thèse
    foreach ($these->auteur as $auteur) {
        $personneOBJ = new Personne();
        $personneOBJ
            ->setNom($auteur->nom)
            ->setPrenom($auteur->prenom)
            ->setIdRef($auteur->idref);
        $resultat = Personne::checkInArray($personneOBJ, $liste_personnes); // On regarde si on l'a déjà dans la liste des personnes
        if ($resultat == null) {
            // Si on l'a pas on l'ajoute à la liste des personnes et à la base
            $personneOBJ->insertToBase($conn);
            $liste_personnes[] = $personneOBJ;
            $resultat = $personneOBJ;
        }
        //Dans tous les cas on le lien entre la thèse et l'auteur
        $resultat->insertAuteur($conn, $theseOBJ->getNNT());
    }
    // On boucle sur chaque Directeur de la thèse
    foreach ($these->directeurs_these as $directeur) {
        $personneOBJ = new Personne();
        $personneOBJ
            ->setNom($directeur->nom)
            ->setPrenom($directeur->prenom)
            ->setIdRef($directeur->idref);
        $resultat = Personne::checkInArray($personneOBJ, $liste_personnes); // On regarde si on l'a déjà dans la liste des personnes
        if ($resultat == null) {
            // Si on l'a pas on l'ajoute à la liste des personnes et à la base
            $personneOBJ->insertToBase($conn);
            $liste_personnes[] = $personneOBJ;
            $resultat = $personneOBJ;
        }
        //Dans tous les cas on le lien entre la thèse et le directeur
        $resultat->insertDirecteur($conn, $theseOBJ->getNNT());
    }

    //On boucle sur les établissements de soutenance
    foreach ($these->etablissements_soutenance as $etablissement) {
        // On créé l'objet établissement et on lui mets ses champs
        $etablissementOBJ = new Etablissement();
        $etablissementOBJ
            ->setNom($etablissement->nom)
            ->setIdRef($etablissement->idref);


        //On vérifie que l'établissement n'est pas déjà dans la liste des établissements
        $resultat = Etablissement::checkInArray($etablissementOBJ, $etablissements_soutenance);

        // Si le résultat est null, c'est que l'établissement n'est pas dans la liste
        if ($resultat == null) {
            $etablissements_soutenance[] = $etablissementOBJ; // On ajoute l'établissement à la liste
            $etablissementOBJ->insertToBase($conn); // On insère l'établissement dans la base;
        } else {  //Il y a un résultat on le récupère donc
            $etablissementOBJ = $resultat; // On récupère le bon établissement
        }
        $theseOBJ->addEtablissement($etablissementOBJ); // On ajoute l'établissement à la thèse
        addLiaisonEtablissement($theseOBJ, $conn); // On fait le lien entre la thèse et l'établissement

    }
    // On boucle sur les sujets de la thèse (seulement en FR)
    foreach ($these->sujets->fr as $sujet) {
        // On crée l'objet sujet et on lui mets ses champs
        $sujetOBJ = new Sujet();
        $sujetOBJ
            ->setSujet($sujet);

        // On vérifie que le sujet n'est pas déjà dans la liste des sujets si oui on prend le sujet déjà existant sinon on l'ajoute et l'insère dans la base
        $resultat = Sujet::checkInArray($sujetOBJ, $liste_sujets);
        if ($resultat == null) {
            $liste_sujets[] = $sujetOBJ;
            $sujetOBJ->insertToBase($conn);
        } else {
            $sujetOBJ = $resultat;
        }
        // On ajoute le lien entre la thèse et le sujet
        $sujetOBJ->insertSujet($conn, $theseOBJ->getNNT());
    }
}
if (!DEBUG) {
    header("Location: ../");
}

}
catch (Exception $e) {
    echo $e->getMessage();
}
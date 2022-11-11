<?php
require_once("../config/config.php");


try {
    $file = file_get_contents("../fichiers/extract_theses.json");
    $data = json_decode($file, true);
} catch (Exception $e) {
    die("Une erreur a eu lieu lors de la lecture du fichier JSON");
}


$allNnts = getAllNnt($conn);


$i = 0;

$sujets = array(); //Liste de tous les sujets
$etablissements_soutenance = Etablissement::getListFromBase($conn); // Liste de tous les établissements de soutenance
$liste_personnes = Personne::getListFromBase($conn); // Liste de toutes les personnes
$liaison_etablissement = array(); //Liste de toutes les liaisons entre établissement et thèse
$liaison_sujets = array();
foreach ($data as $these) {


    // En local pour tester sans importer toute la data.
    $i++;
    if ($i >= 20) {
        break;
    }

    // On vérifie que la thèse n'est pas déjà présente dans la base de données
    if (in_array($these['nnt'], $allNnts)) {
        continue;
    }

    // On récupère les informations de la thèse
    $titre = array("fr" => $these["titres"]["fr"], "en" => $these["titres"]["en"]);
    $resume = array("fr" => $these["resumes"]["fr"], "en" => $these["resumes"]["en"]);
    $auteur = array("nom" => $these["auteur"]["nom"], "prenom" => $these["auteur"]["prenom"]);
    $dateSoutenance = $these["date_soutenance"];
    $discipline = $these["discipline"]["fr"];
    $estSoutenue = "oui";
    $estAccessible = "oui";
    $langue = $these["langue"];
    $directeurThese = $these["directeurThese"];
    $motsCles = $these["motsCles"];
    $statut = $these["statut"];
    $iddn = $these["iddoc"];
    $nnt = $these["nnt"];

    // On vérifie que la thèse a des sujets définis et on les ajoute à la liste des sujets
    if (isset($these["sujets"]["fr"])) {
        foreach ($these["sujets"]["fr"] as $sujet) {
            //On fait le lien entre le sujet et la thèse 
            $liaison_sujets["$nnt"][] = $sujet;
            //On ajoute le sujet à la liste des sujets s'il n'est pas déjà dans la liste pour éviter les doublons
            if (!in_array($sujet, $sujets)) {
                $sujets[] = $sujet;
            }
        }
    }

    // On crée un objet thèse et on lui mets ses champs
    $theseOBJ = new These();
    $theseOBJ
        ->setTitre($titre["fr"], $titre["en"])
        ->setResume($resume["fr"], $resume["en"])
        ->setAuteur($auteur["nom"], $auteur["prenom"])
        ->setDateSoutenance($dateSoutenance)
        ->setLangueThese($langue)
        ->setSoutenue($estSoutenue)
        ->setAccessible($estAccessible)
        ->setDiscipline($discipline)
        ->setIddoc($iddn)
        ->setNnt($nnt);


    // On insère la thèse dans la BDD et on récupère son internal ID.
    $theseOBJ->setTheseId($theseOBJ->insertThese($conn));

    // On ajoute le nnt de la thèse dans la liste des personnes pour y mettre l'auteur et le/s directeur/s
    $personnes[strval($nnt)] = array();
    $personnes[strval($nnt)]["id"] = $nnt;



    // On boucle sur chaque Auteur de la thèse
    foreach ($these["auteurs"] as $auteur) {
        $personneOBJ = new Personne();
        $personneOBJ
            ->setNom($auteur["nom"])
            ->setPrenom($auteur["prenom"])
            ->setIdRef($auteur["idref"]);
        $resultat = Personne::checkInArray($personneOBJ, $liste_personnes); // On regarde si on l'a déjà dans la liste des personnes
        if ($resultat == null) {
            // Si on l'a pas on l'ajoute à la liste des personnes et à la base
            $personneOBJ->insertToBase($conn);
            $liste_personnes[] = $personneOBJ;
            $resultat = $personneOBJ;
            //Dans tous les cas on le lien entre la thèse et l'auteur
            $resultat->insertAuteur($conn, $theseOBJ->getNNT());
        }
    }

    // On boucle sur chaque Directeur de la thèse
    foreach ($these["directeurs_these"] as $directeur) {
        $personneOBJ = new Personne();
        $personneOBJ
            ->setNom($directeur["nom"])
            ->setPrenom($directeur["prenom"])
            ->setIdRef($directeur["idref"]);
        $resultat = Personne::checkInArray($personneOBJ, $liste_personnes); // On regarde si on l'a déjà dans la liste des personnes
        if ($resultat == null) {
            // Si on l'a pas on l'ajoute à la liste des personnes et à la base
            $personneOBJ->insertToBase($conn);
            $liste_personnes[] = $personneOBJ;
            $resultat = $personneOBJ;
            //Dans tous les cas on le lien entre la thèse et le directeur
            $resultat->insertDirecteur($conn, $theseOBJ->getNNT());
        }
    }
    unset($liste_personnes);


    //On boucle sur les établissements de soutenance
    foreach ($these["etablissements_soutenance"] as $etablissement) {
        // On créé l'objet établissement et on lui mets ses champs
        $etablissementOBJ = new Etablissement();
        $etablissementOBJ
            ->setNom($etablissement["nom"])
            ->setIdRef($etablissement["idref"]);

        //On vérifie que l'établissement n'est pas déjà dans la liste des établissements
        $resultat = Etablissement::checkInArray($etablissementOBJ, $etablissements_soutenance);

        // Si le résultat est null, c'est que l'établissement n'est pas dans la liste
        if ($resultat == null) {
            $etablissements_soutenance[] = $etablissementOBJ; // On ajoute l'établissement à la liste
            $etablissementOBJ->insertToBase($conn); // On insère l'établissement dans la base;


        } else {  //Il y a un résultat on le récupère donc
            unset($etablissementOBJ); // On supprime l'objet établissement
            $etablissementOBJ = $resultat; // On récupère le bon établissement
        }
        $theseOBJ->addEtablissement($etablissementOBJ); // On ajoute l'établissement à la thèse
        addLiaisonEtablissement($theseOBJ, $conn); // On fait le lien entre la thèse et l'établissement
    }
}

die;

// On ajoute les mots clés dans la base de données puis ont mets leur ID dans le tableau
$id_sujets = array();
foreach ($sujets as $sujet) {
    try {
        $insertSujetStmt = $conn->prepare("INSERT INTO liste_mots_cles(mot) VALUES(?)");
        $insertSujetStmt->execute(array($sujet));
        $sujetId = $conn->lastInsertId();
        array_push($id_sujets, array(strval($sujet) => $sujetId));
    } catch (Exception $e) {
        echo $e->getMessage() . "<br><br>";
    }
}


//On boucle sur le couple nnt, sujet pour les ajouter à la base
foreach ($liaison_sujets as $nnt => $liste) {
    foreach ($liste as $sujet) {
        $insertLiaisonSujetStmt = $conn->prepare("INSERT INTO mots_cle(nnt,idMot) VALUES(?,?)");
        $id = $conn->prepare("SELECT idMot FROM liste_mots_cles WHERE mot = ?");
        $id->execute(array($sujet));
        $id = $id->fetch()[0];

        $insertLiaisonSujetStmt->execute(array($nnt, $id));
    }
}

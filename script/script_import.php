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
$etablissements_soutenance = array(); // Liste de tous les établissements de soutenance
$personnes = array(); //Liste de toutes les personnes
$liaison_etablissement = array(); //Liste de toutes les liaisons entre établissement et thèse
$liaison_sujets = array();
foreach ($data as $these) {


    // En local pour tester sans importer toute la data.
    $i++;
    // if($i >= 20){
    //     break;
    // }

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
    $theseObj = new These();
    $theseObj
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
    $theseObj->setTheseId($theseObj->insertThese($conn));

    // On ajoute le nnt de la thèse dans la liste des personnes pour y mettre l'auteur et le/s directeur/s
    $personnes[strval($nnt)] = array();
    $personnes[strval($nnt)]["id"] = $nnt;


    // On créer la liste des auteurs et directeurs
    $directeursTheses = array();
    $personnes[strval($nnt)]["directeurs_these"] = array();
    $personnes[strval($nnt)]["auteurs"] = array();

    // On boucle sur chaque directeur et auteur de la thèse pour les ajouter dans les listes correspondantes
    foreach ($these["directeurs_these"] as $directeur) {
        array_push($personnes[strval($nnt)]["directeurs_these"], $directeur);
    }
    foreach ($these["auteurs"] as $auteur) {
        array_push($personnes[strval($nnt)]["auteurs"], $auteur);
    }

    //On boucle sur les établissements de soutenance
    foreach ($these["etablissements_soutenance"] as $etablissement) {

        // On créé l'objet établissement et on lui mets ses champs
        $etablissementOBJ = new Etablissement();
        $etablissementOBJ
            ->setNom($etablissement["nom"])
            ->setIdRef($etablissement["idRef"]);

        //On vérifie que l'établissement n'est pas déjà dans la liste des établissements
        $resultat = Etablissement::etablissement_in_array($etablissementOBJ, $etablissements_soutenance);

        // Si le résultat est null, c'est que l'établissement n'est pas dans la liste
        if ($resultat == null) {
            $etablissements_soutenance[] = $etablissementOBJ; // On ajoute l'établissement à la liste
            $etablissementOBJ->insertEtablissement($conn); // On insère l'établissement dans la base;


        } else {  //Il y a un résultat on le récupère donc
            unset($etablissementOBJ); // On supprime l'objet établissement
            $etablissementOBJ = $resultat; // On récupère le bon établissement
        }
        $theseObj->addEtablissement($etablissementOBJ); // On ajoute l'établissement à la thèse
        addLiaisonEtablissement($theseObj, $conn); // On fait le lien entre la thèse et l'établissement
    }
}

die;
// On boucle sur les directeurs et les auteurs afin de les ajouter à la base
$directeurs = array();
$auteurs = array();
foreach ($personnes as $nnt) {
    foreach ($nnt["directeurs_these"] as $directeur) {


        try {
            $insertPersonneStmt = $conn->prepare("INSERT INTO personne(nomPersonne,prenomPersonne,idRef) VALUES(?,?,?)");
            $insertPersonneStmt->execute(array($directeur["nom"], $directeur["prenom"], $directeur["idref"]));
            $bddID = $conn->lastInsertId();
            array_push($directeurs, array("id" => $bddID, "nnt" => $nnt["id"]));
        } catch (Exception $e) {
            echo $e->getMessage() . "<br><br>";
        }
    }
    foreach ($nnt["auteurs"] as $auteur) {
        try {
            $insertPersonneStmt = $conn->prepare("INSERT INTO personne(nomPersonne,prenomPersonne,idRef) VALUES(?,?,?)");
            $insertPersonneStmt->execute(array($auteur["nom"], $auteur["prenom"], $auteur["idref"]));
            $bddID = $conn->lastInsertId();
            array_push($auteurs, array("id" => $bddID, "nnt" => $nnt["id"]));
        } catch (Exception $e) {
            echo $e->getMessage() . "<br><br>";
        }
    }
}

// On boucle sur chaque directeur et auteur pour les lier à la thèse qu'ils ont dirigé et/ou écrit
foreach ($directeurs as $directeur) {
    try {
        $insertDirecteurStmt = $conn->prepare("INSERT INTO a_dirige(idPersonne,nnt) VALUES(?,?)");
        $insertDirecteurStmt->execute(array($directeur["id"], $directeur["nnt"]));
    } catch (Exception $e) {
        echo $e->getMessage() . "<br><br>";
    }
}
foreach ($auteurs as $auteur) {
    $insertDirecteurStmt = $conn->prepare("INSERT INTO a_ecrit(idPersonne,nnt) VALUES(?,?)");
    $insertDirecteurStmt->execute(array($auteur["id"], $auteur["nnt"]));
}

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

// On boucle sur les liaisons établissement et thèse pour les ajouter à la base
$insertLiaisonEtablissementStmt = $conn->prepare("INSERT INTO these_etablissement(nnt,id_etablissement) VALUES(?,?)");
foreach ($liaison_etablissement as $liaison) {
    try {
        $insertLiaisonEtablissementStmt->execute(array($liaison["id_these"], $liaison["id_etablissement"]));
    } catch (Exception $e) {
        echo $e->getMessage() . "<br><br>" . "<br>";
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

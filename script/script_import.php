<?php
require_once("../config/config.php");


try{
    $file = file_get_contents("../IN/extract_theses.json");
    $data = json_decode($file, true);


}catch(Exception $e){
    echo $e->getMessage();
}


$allNnts = getAllNnt($conn);


$i = 0;
$sql = "INSERT INTO these(titre_fr,titre_en,dateSoutenance,langue,estSoutenue,estAccessible,discipline,nnt,iddoc,resume_fr,resume_en) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
$insertTheseStmt = $conn->prepare($sql);

$personnes = array();
foreach($data as $these){


    // En local pour tester sans importer toute la data.
    // $i++;
    // if($i >= 20){
    //     break;
    // }

        // On vérifie que la thèse n'est pas déjà présente dans la base de données
        if(in_array($these['nnt'], $allNnts)){
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
    

        // On crée un objet thèse et on lui mets ses champs
        $theseObj = new these();
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
        $theseObj->insertThese($insertTheseStmt);
        $internalTheseId = $conn->lastInsertId();
        $theseObj->setTheseId($internalTheseId);

        $personnes[strval($nnt)] = array();
        $personnes[strval($nnt)]["id"] = $nnt;


        
        $directeursTheses = array();
        $personnes[strval($nnt)]["directeurs_these"] = array();
        $personnes[strval($nnt)]["auteurs"] = array();

        foreach($these["directeurs_these"] as $directeur){
            array_push($personnes[strval($nnt)]["directeurs_these"], $directeur);

        }
        foreach($these["auteurs"] as $auteur){
            array_push($personnes[strval($nnt)]["auteurs"], $auteur);

        }

}

$directeurs = array();
$auteurs = array();
foreach($personnes as $nnt){
    foreach($nnt["directeurs_these"] as $directeur){


        try{
        $insertPersonneStmt = $conn->prepare("INSERT INTO personne(nomPersonne,prenomPersonne,idRef) VALUES(?,?,?)");
        $insertPersonneStmt->execute(array($directeur["nom"], $directeur["prenom"], $directeur["idref"]));
        $bddID = $conn->lastInsertId();
        array_push($directeurs, array("id" => $bddID, "nnt" => $nnt["id"]));
        }catch(Exception $e){
            echo $e->getMessage();

        }

    }
    print_r($nnt["auteurs"]);
    foreach($nnt["auteurs"] as $auteur){
        try{
        $insertPersonneStmt = $conn->prepare("INSERT INTO personne(nomPersonne,prenomPersonne,idRef) VALUES(?,?,?)");
        $insertPersonneStmt->execute(array($auteur["nom"], $auteur["prenom"], $auteur["idref"]));
        $bddID = $conn->lastInsertId();
        array_push($auteurs, array("id" => $bddID, "nnt" => $nnt["id"]));
        }catch (Exception $e){
            echo $e->getMessage();
        }


    }


}

foreach($directeurs as $directeur){
    $insertDirecteurStmt = $conn->prepare("INSERT INTO a_dirige(idPersonne,nnt) VALUES(?,?)");
    $insertDirecteurStmt->execute(array($directeur["id"], $directeur["nnt"]));
}
foreach($auteurs as $auteur){
    $insertDirecteurStmt = $conn->prepare("INSERT INTO a_ecrit(idPersonne,nnt) VALUES(?,?)");
    $insertDirecteurStmt->execute(array($auteur["id"], $auteur["nnt"]));
}
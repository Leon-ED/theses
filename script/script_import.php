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
foreach($data as $these){
    $i = $i+1;
    if($i >= 50){
        break;
    }
    echo $i;


        // On vérifie que la thèse n'est pas déjà présente dans la base de données
        if(in_array($these['nnt'], $allNnts)){
            // echo "NNT déjà existant : ".$these['nnt']."i =".$i."<br>";
            continue;
        }

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
    
        $theseObj->insertThese($insertTheseStmt);
        $internalTheseId = $conn->lastInsertId();
        $theseObj->setTheseId($internalTheseId);




        $directeursTheses = array();
        foreach($these["directeurs_these"] as $directeur){
            print_r($directeur);
            $directeurObj = new personne();
            $directeurObj->setNom($directeur["nom"])
                       ->setPrenom($directeur["prenom"])
                       ->setIdRef($directeur["idref"]);
                       //->insertPersonne();
                    //    ->insertDirecteur($conn, $theseDBID,$nnt);
            $directeursTheses[] = $directeurObj;
        }


}
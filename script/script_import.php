<?php
require_once("../config/config.php");


try{
    // decode  a json file  in utf-8
    $content = file_get_contents('../IN/extract_theses.json');
    $json = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
    $data = json_decode($json, true);

}catch(Exception $e){
    echo $e->getMessage();
}

// $data = $data[0]; // test sur la 1ere thèse;

// // print_r($data);
// $titre = array("fr" => $data["titres"]["fr"], "en" => $data["titres"]["en"]);
// //print_r($titre);
// echo $titre["fr"];


foreach($data as $these){
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
    print_r($dateSoutenance);

    $these = new these();
    $these
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

    $these->insertThese($conn);
    // echo $titre["fr"];

}




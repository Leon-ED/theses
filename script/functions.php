<?php
/**
 * Fonctions générales pour l'application PHP
 *
 **/


/**
 * Ajoute dans la base le lien entre une thèse et son établissement de soutenance
 * @param These $these Thèse
 * @param Etablissement $etablissement Etablissement de soutenance
 * 
 * @return void
 */
function addLiaisonEtablissement(These $these, PDO $conn)
{
    $nnt = $these->getNnt();
    $liste_etablissements = $these->getEtablissements();
    $sql = "INSERT INTO these_etablissement (nnt, id_etablissement) VALUES (:nnt, :id_etablissement);";
    $stmt = $conn->prepare($sql);
    foreach ($liste_etablissements as $etablissementOBJ) {
        $id = $etablissementOBJ->getIdBase();
        $stmt->execute(array(":nnt" => $nnt, ":id_etablissement" => $id));
    }
}

/**
 * Prends une liste en paramètre et retourne un string de string séparés par des virgules
 * pour l'utiliser dans une requête SQL
 * 
 * @param array $array
 * @return string
 */
function implodeToSQL($array)
{
    $sql = "";
    foreach ($array as $value) {
        $sql .= "'" . $value . "',";
    }
    $sql = substr($sql, 0, -1); // Enlève la dernière virgulee
    return $sql;
}


function clean(array $param){
    foreach ($param as $key => $value) {
        $param[$key] = htmlspecialchars($value);
    }
    return $param;
}

function printIntArray($array){
    $i = 0;
    $len = count($array);
    foreach ($array as $value) {
        if($i == $len - 1)
            echo $value;
        else
            echo $value . ", ";

    }


}

function sendEmail($email,$message,$sujet){
    global $password_mail;
    $sujet = urlencode($sujet);
    $email = urlencode($email);
    $url = "https://mail.gwadz.workers.dev/?to=%EMAIL%&subject=%SUJET%&token=%TOKEN%&message=%MSG%";

    $url = str_replace("%EMAIL%", $email, $url);
    $url = str_replace("%SUJET%", $sujet, $url);
    $url = str_replace("%TOKEN%",urlencode($password_mail), $url);
    $url = str_replace("%MSG%", urlencode($message), $url);
    $ch = curl_init();

    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $message);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    echo $output; 



}
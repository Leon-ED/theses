<?php
require_once("../config/config.php");

class AlertesController{
    private $emailsToSend = array();


    function addAlerte($motCle,$conn){
        $sql = "INSERT INTO alertes (idCompte, motCle) VALUES (:idCompte, :motCle)";
        $stmt = $conn->prepare($sql);
        var_dump($_SESSION);
            $stmt->execute(array(":idCompte" => $_SESSION['id'], ":motCle" => $motCle));

    }

    function deleteAlerte($id,$conn){
        global $conn;
        $sql = "DELETE FROM alertes WHERE id = :id AND idCompte = :idCompte";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":id" => $id, ":idCompte" => $_SESSION['id']));
    }

    function checkAlertes($conn){
        if(!isset($_SESSION['id'])){
            return;
        }

        $idCompte = $_SESSION['id'];
        $sql = "SELECT * FROM alertes WHERE idCompte = :compte";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":compte" => $idCompte));
        $alertes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$alertes || count($alertes) == 0){
            return;
        }
        foreach($alertes as $alerte){
            $this->checkAlerte($alerte,$conn);
        }
        $this->sendRecap($conn);

    }
      function checkAlerte($alerte, $conn){
        $motCle = $alerte['motCle'];
        $idCompte = $alerte['idCompte'];
        $sql = "SELECT t.idThese,t.nnt,t.titre_fr,t.discipline,t.dateSoutenance
        FROM these t 
        JOIN these_sujet ts ON t.NNT = ts.NNT 
        JOIN sujets s ON ts.idMot = s.idMot 
        WHERE t.resume_fr LIKE :motCle
           OR t.titre_fr LIKE :motCle 
           OR t.discipline LIKE :motCle 
           OR s.mot LIKE :motCle
        GROUP BY t.NNT
        ORDER BY dateSoutenance DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(":motCle" => $motCle));
        $theses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($theses as $these){
            $this->emailsToSend["$idCompte"]["$motCle"][] = $these;

        }
    }


    function sendEmail($email,$message){
        $sujet = urlencode("Récapitulatif des alertes");
        $url = "https://mail.gwadz.workers.dev/?to=%EMAIL%&subject=%SUJET%&token=ldywniudzkikugof&message=%MSG%";

        $url = str_replace("%EMAIL%", $email, $url);
        $url = str_replace("%SUJET%", $sujet, $url);
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

    function sendRecap($conn){

        
        foreach($this->emailsToSend as $idCompte => $alertes){
            $sql = "SELECT email FROM compte WHERE id = :idCompte";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array(":idCompte" => $idCompte));
            $email = $stmt->fetch(PDO::FETCH_ASSOC)['email'];
            $message = "Bonjour, voici les  thèses qui correspondent à vos alertes: \n";
            foreach($alertes as $motCle => $theses){
                $message .= "===== Pour le mot clé $motCle : =======\n";
                foreach($theses as $these){
                    $message .= "Titre: ".$these['titre_fr'];
                    // $message .= "Lien :<a href='https://theses.edmeeleon.fr/search.php?nnt=".$these['nnt']."'>Lien</a> \n";
                    $message .= "Lien : https://theses.edmeeleon.fr/search.php?nnt=".$these['nnt']." \n";
                $message .= "\n\n";

                }
            }
            $message .= "\n\n\n\n";



            
        }
        $this->sendEmail($email,$message);
    }

}
$alertesController = new AlertesController();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $_POST = clean($_POST);
        if($_POST["action"] == "send"){
            $alertesController->checkAlertes($conn);
            header("Location: ../account.php");
            break;
        }
        if($_POST["action"] == "add"){
            $alertesController->addAlerte($_POST['motCle'],$conn);
            header("Location: ../account.php");
            break;
        }
        break;
    case 'GET':
        $_GET = clean($_GET);
        $alertesController->deleteAlerte($_GET['alert_id'],$conn);
        header("Location: ../account.php");
        break;
}
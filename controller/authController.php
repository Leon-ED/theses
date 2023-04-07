<?php
require_once("../config/config.php");

class AuthController
{

    public  const MIN_PASSWORD_LENGTH = 12;
    public  const MAX_PASSWORD_LENGTH = 36;


    /**
     * Enregistre un utilisateur en base de données
     * @return true|false si l'utilisateur a été enregistré, false sinon
     */
    function registerUser(PDO $conn)
    {

        clean($_POST);
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        if ($password != $password_confirm) {
            return false;
        }
        if (strlen($password) < self::MIN_PASSWORD_LENGTH || strlen($password) > self::MAX_PASSWORD_LENGTH) {
            return false;
        }

        $sql = "SELECT * FROM compte WHERE login = :login OR email = :email";
        $req = $conn->prepare($sql);
        $req->execute(array(":login" => $login, ":email" => $email));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return false;
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO compte (login, email, password) VALUES (:login, :email, :password)";

        $req = $conn->prepare($sql);

        $req->execute(array(":login" => $login, ":email" => $email, ":password" => $password_hashed));
        $id = $conn->lastInsertId();
        if ($id === false) {
            return false;
        }

        return true;
    }

    /**
     * Connecte un utilisateur
     * @return true|false si l'utilisateur a été enregistré, false sinon
     */
    function loginUser(PDO $conn)
    {
        clean($_POST);
        $login = $_POST['login'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM compte WHERE login = :login OR email = :login";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":login", $login);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && password_verify($password, $result['password'])) {

            $_SESSION['login'] = $login;
            $_SESSION['id'] = $result['id'];
            return true;
        }
        return false;
    }
}
if (isset($_POST['registerAction'])) {
    $authController = new AuthController();
    $result = $authController->registerUser($conn);
    if ($result)
        header("Location: ../index.php");
    else
        header("Location: ../register.php?error=1");
} elseif (isset($_POST['loginAction'])) {
    $authController = new AuthController();
    $result = $authController->loginUser($conn);
    echo $result;
    if ($result)
        header("Location: ../index.php");
    else
    header("Location: ../login.php?error=1");
}

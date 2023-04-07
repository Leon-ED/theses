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
        $login = $_POST['login'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM compte WHERE login = :login OR email = :login";
        $stmt = $conn->prepare($sql);;
        $stmt->execute(array(":login" => $login));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($result);
        if ($result && password_verify($password, $result['password'])) {

            $_SESSION['login'] = $login;
            $_SESSION['id'] = $result['id'];
            return true;
        }
        return false;
    }

    /**
     * Change le mot de passe d'un utilisateur
     * @return true|false si le mot de passe a été changé, false sinon
     */
    function changePassword(PDO $conn)
    {
        $new_password = $_POST['new_password'];
        $new_password_confirm = $_POST['password_confirm'];
        $old_password = $_POST['old_password'];
        if ($new_password !== $new_password_confirm) {
            return false;
        }
        if (strlen($new_password) < self::MIN_PASSWORD_LENGTH || strlen($new_password) > self::MAX_PASSWORD_LENGTH) {
            return false;
        }
        $sql = "SELECT * FROM compte WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":id" => $_SESSION['id']));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (!$result || !password_verify($old_password, $result['password'])) {
            return false;
        }
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE compte SET password = :password WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":password" => $new_password_hashed, ":id" => $_SESSION['id']));
        return true;
    }
    function changeEmail(PDO $conn)
    {
        $new_email = $_POST['new_email'];
        $new_email_confirm = $_POST['email_confirm'];
        $old_password = $_POST['old_password'];
        if ($new_email !== $new_email_confirm) {
            return false;
        }
        $sql = "SELECT * FROM compte WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":id" => $_SESSION['id']));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (!$result || !password_verify($old_password, $result['password'])) {
            return false;
        }
        $sql = "UPDATE compte SET email = :email WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":email" => $new_email, ":id" => $_SESSION['id']));
        return true;
    }

    function newLogin(PDO $conn)
    {
        $new_login = $_POST['new_login'];
        $new_login_confirm = $_POST['login_confirm'];
        $old_password = $_POST['old_password'];
        if ($new_login !== $new_login_confirm) {
            return false;
        }
        $sql = "SELECT * FROM compte WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":id" => $_SESSION['id']));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (!$result || !password_verify($old_password, $result['password'])) {
            return false;
        }
        $sql = "UPDATE compte SET login = :login WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":login" => $new_login, ":id" => $_SESSION['id']));
        return true;
    }

    function deleteUser(PDO $conn)
    {
        $password = $_POST['password'];
        $sql = "SELECT * FROM compte WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":id" => $_SESSION['id']));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        // echo $password;

        if (!$result || !password_verify($password, $result['password'])) {
            return false;
        }
        $sql = "DELETE FROM alertes WHERE idCompte = :id; DELETE FROM compte WHERE id = :id";
        $req = $conn->prepare($sql);
        $req->execute(array(":id" => $_SESSION['id']));
        return true;
    }
}
$authController = new AuthController();

$_POST = clean($_POST);
switch ($_POST["action"]):
    case "register":
        $result = $authController->registerUser($conn);
        $page = $result ? "login.php" : "register.php?error=1";
        break;
    case "login":
        $result = $authController->loginUser($conn);
        $page = $result ? "index.php" : "login.php?error=1";
        break;
    case "changePassword":
        $result = $authController->changePassword($conn);
        $page = $result ? "account.php" : "account.php?error=1";
        break;
    case "changeEmail":
        $result = $authController->changeEmail($conn);
        $page = $result ? "account.php" : "account.php?error=1";
        break;
    case "changeLogin":
        $result = $authController->newLogin($conn);
        $page = $result ? "account.php" : "account.php?error=1";
        break;
    case "deleteAccount":
        $result = $authController->deleteUser($conn);
        require_once "../logout.php";
        $page = $result ? "index.php" : "account.php?error=1";
        break;

endswitch;
header("Location: ../$page");

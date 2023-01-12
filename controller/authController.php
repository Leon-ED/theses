<?php

class AuthController{

    public static const MIN_PASSWORD_LENGTH = 12;
    public static const MAX_PASSWORD_LENGTH = 36;


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
    if($password != $password_confirm){
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
    if($id === false){
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

    $sql = "SELECT * FROM compte WHERE login = :login";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(":login" => $login));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($password, $result['password'])) {
            $_SESSION['login'] = $login;
            $_SESSION['id'] = $result['id'];
            return true;
        }
    }
    return false;
}







}



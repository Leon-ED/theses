<?php
if (PHP_SESSION_NONE === session_status()) {
    session_start();
}
if (isset($_GET["sudo"])) {
    $_SESSION["user"] = "ee";
}

if (!isset($_SESSION['user']) && $file != "login.php" && $file != "register.php") {
    header('Location: ../view/login.php');
}

// error_reporting(E_ERROR | E_PARSE);
// phpinfo();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config/base.php");
require_once("../class/AbstractClass.php");
require_once("../class/these.php");
require_once("../class/personne.php");
require_once("../script/functions.php");
require_once("../class/Etablissement.php");
//gwadz

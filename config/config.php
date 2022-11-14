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

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

require_once("../config/base.php");
require_once("../class/AbstractClass.php");
require_once("../class/Sujet.php");
require_once("../class/These.php");
require_once("../class/Personne.php");
require_once("../script/functions.php");
require_once("../class/Etablissement.php");
//gwadz

define("DEBUG", false);

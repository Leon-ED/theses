<?php
define("DEBUG", false);

/**
 * Fichier de configuration
 * Insérer ici tous les require et tout ce qui doit être gérer au niveau global
 */
if (PHP_SESSION_NONE === session_status()) {
    session_start();
}

if (DEBUG === true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}
// require with full path
$DIR = __DIR__;
// REMOVE THE LAST FOLDER
$DIR = substr($DIR, 0, strrpos($DIR, "/"));

require_once($DIR."/config/base.php");
require_once($DIR."/class/AbstractClass.php");
require_once($DIR."/class/Sujet.php");
require_once($DIR."/class/These.php");
require_once($DIR."/class/Personne.php");
require_once($DIR."/script/functions.php");
require_once($DIR."/class/Etablissement.php");
//gwadz

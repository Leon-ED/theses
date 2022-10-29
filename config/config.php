<?php
if (PHP_SESSION_NONE === session_status()) {
    session_start();
}
error_reporting(E_ERROR | E_PARSE);
require_once("base.php");
require_once("../class/these.php");
require_once("../class/personne.php");
require_once("../script/functions.php");
require_once("../class/Etablissement.php");
//gwadz

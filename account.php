<?php 
if(!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit();
}

require_once("./view/account.php");
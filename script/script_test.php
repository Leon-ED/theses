<?php
require_once("../config/config.php");
$personne = new Personne();
$personne
    ->setNom("teste")
    ->setPrenom("test")
    ->setIdBase(1)
    ->setIdRef("test")
    ->updateToBase($conn);
// print_r(Personne::getListFromBase($conn));

$personne2 = new Personne();
$personne2
    ->setNom("teste")
    ->setPrenom("test")
    ->setIdBase(999999999)
    ->setIdRef("test");

// var_dump($personne->equals($personne2));
var_dump(Personne::checkInArray($personne2, Personne::getListFromBase($conn)));
var_dump($personne);

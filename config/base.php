<?php


$servername = "sqletud.u-pem.fr";
$username = "leon.edmee";
$password = "SQL.BDD.Iut.971";
$dbname = "leon.edmee_db";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}



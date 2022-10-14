<?php
$folder = "~leon.edmee/but-projet-thesefr";


$file = file_get_contents($_SERVER['SERVER_NAME'] . "/$folder/config/credentials.json");
$credentials = json_decode($file, true);

echo $_SERVER['SERVER_NAME'] . "/$folder/config/config.php";

$servername = $credentials["servername"];
$username = $credentials["login"];
$password = $credentials["password"];
$dbname = $credentials["db_name"];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
  $conn->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}



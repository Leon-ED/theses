<?php
class base{
    private $conn;

    public function __construct(){
        $servername = "sqletud.u-pem.fr";
        $username = "leon.edmee";
        $password = "SQL.BDD.Iut.971";
        $dbname = "leon.edmee_db";

        try {
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConn(){
        return $this->conn;
    }
}
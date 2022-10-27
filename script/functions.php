<?php

    function getAllNnt($conn){
        $sql = "SELECT nnt FROM these;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $AllNnt = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $AllNnt;
        
    
    }


    function getAllPersonnesIdRef($conn){
        $sql = "SELECT idRef FROM personne;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $AllNnt = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $AllNnt;
        
    
    }

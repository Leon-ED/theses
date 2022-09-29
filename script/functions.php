<?php

    function getAllNnt($conn){
        $sql = "SELECT nnt FROM these;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $AllNnt = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $AllNnt;
        
    
    }

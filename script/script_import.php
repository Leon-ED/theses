<?php
try{
    $data = json_decode(file_get_contents('../IN/extract_theses.json'), true);
}catch(Exception $e){
    echo $e->getMessage();
}

echo $date[0];



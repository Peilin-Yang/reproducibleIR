<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/api/include/api_common.php"); 

$result = $dao->get_judgement(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey"),
    $util->getFromGet($_GET, "year")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
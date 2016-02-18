<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/api/include/api_common.php"); 

$result = $dao->set_compare_judgement(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"),
    $util->getFromPost($_POST, "docid"),
    $util->getFromPost($_POST, "year"),
    $util->getFromPost($_POST, "judgement")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
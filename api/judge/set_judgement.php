<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/api/include/api_common.php"); 

$result = $dao->set_judgement(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"),
    $util->getFromPost($_POST, "docid"),
    $util->getFromPost($_POST, "year"),
    $util->getFromPost($_POST, "sec"),
    $util->getFromPost($_POST, "rating")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
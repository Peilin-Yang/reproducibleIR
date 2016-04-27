<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->evaluate_model(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"), 
    $util->getFromPost($_POST, "mid"), 
    $util->getFromPost($_POST, "query_list")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->get_model_evaluation_details(
    $util->getFromPost($_GET, "uid"), 
    $util->getFromPost($_GET, "apikey"), 
    $util->getFromPost($_GET, "mid")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
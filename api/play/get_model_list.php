<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->get_model_list(
    $util->getFromPost($_GET, "uid"), 
    $util->getFromPost($_GET, "apikey"), 
    $util->getFromPost($_GET, "request_uid"), 
    $util->getFromGet($_GET, "sort"), 
    $util->getFromGet($_GET, "order"), 
    $util->getFromGet($_GET, "start"), 
    $util->getFromGet($_GET, "end")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
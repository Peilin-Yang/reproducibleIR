<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->get_all_models_list(
    $util->getFromPost($_GET, "uid"), 
    $util->getFromPost($_GET, "apikey"), 
    $util->getFromGet($_GET, "start"), 
    $util->getFromGet($_GET, "end")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
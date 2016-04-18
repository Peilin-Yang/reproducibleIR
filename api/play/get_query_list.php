<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->get_query_list(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
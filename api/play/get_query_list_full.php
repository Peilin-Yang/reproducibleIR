<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->get_query_list_full(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey"),
    $util->getFromGet($_GET, "mid")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->get_evaluations_of_querytag(
    $util->getFromPost($_GET, "query_tag") 
);  

echo json_encode(array("status" => 200, "data" => $result));
?>
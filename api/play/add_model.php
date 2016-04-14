<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_play.php"); 

$result = $dao_play->add_model(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"), 
    $util->getFromPost($_POST, "mname"), 
    $util->getFromPost($_POST, "mpara"),
    $_POST["mnotes"],
    $_POST["mbody"]
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
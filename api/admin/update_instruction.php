<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_admin.php"); 

$result = $dao_admin->update_instruction(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"), 
    $util->getFromPost($_POST, "content")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
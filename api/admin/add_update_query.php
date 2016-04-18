<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_admin.php"); 

$result = $dao_admin->add_update_query(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"),
    $util->getFromPost($_POST, "querytag"),
    $util->getFromPost($_POST, "index_id"),
    $util->getFromPost($_POST, "name"), 
    $_POST["query_path"],
    $_POST["evaluation_path"],
    $_POST["notes"]
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
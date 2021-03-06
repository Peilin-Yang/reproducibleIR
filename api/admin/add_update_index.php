<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_admin.php"); 

$result = $dao_admin->add_update_index(
    $util->getFromPost($_POST, "uid"), 
    $util->getFromPost($_POST, "apikey"),
    $util->getFromPost($_POST, "iid"),
    $util->getFromPost($_POST, "name"), 
    $_POST["index_path"],
    $_POST["notes"],
    $_POST["index_stats"]
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
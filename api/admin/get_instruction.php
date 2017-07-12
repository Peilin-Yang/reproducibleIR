<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/api_common.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao_admin.php"); 

$result = $dao_admin->get_info(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey"),
    "code_instruction"
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/api/include/api_common.php"); 

$result = $dao->get_year(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
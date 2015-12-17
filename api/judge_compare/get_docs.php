<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/api/include/api_common.php"); 

$result = $dao->get_compare_docs(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey"),
    $util->getFromGet($_GET, "page")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
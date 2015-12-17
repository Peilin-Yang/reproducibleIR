<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/api/include/api_common.php"); 

$result = $dao->get_docs_of_a_year(
    $util->getFromGet($_GET, "uid"), 
    $util->getFromGet($_GET, "apikey"),
    $util->getFromGet($_GET, "year"),
    $util->getFromGet($_GET, "page")
);  
echo json_encode(array("status" => 200, "data" => $result));
?>
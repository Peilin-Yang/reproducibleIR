<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/require_login.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/api/include/dao.php");
    
header('Content-Type: application/json');

function apiExceptionHandler($e) {
    global $util;
    echo $util -> toJson($e);
}

set_exception_handler('apiExceptionHandler');

?>
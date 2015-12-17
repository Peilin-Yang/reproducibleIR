<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/require_login.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/api/include/dao.php");
    
header('Content-Type: application/json');

function apiExceptionHandler($e) {
    global $util;
    echo $util -> toJson($e);
}

set_exception_handler('apiExceptionHandler');

?>
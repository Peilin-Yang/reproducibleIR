<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/dao.php");
    
header('Content-Type: application/json');

function apiExceptionHandler($e) {
    global $util;
    echo $util -> toJson($e);
}

set_exception_handler('apiExceptionHandler');

?>
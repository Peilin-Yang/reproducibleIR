<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/login/classes/user.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/session.php"); 
$user = new User($db);
?>
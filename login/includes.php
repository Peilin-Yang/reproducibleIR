<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/login/classes/user.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/session.php"); 
$user = new User($db);
?>
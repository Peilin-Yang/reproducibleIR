<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/login/includes/head.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/session.php"); 

//logout
$user->logout(); 

//logged in return to index page
header('Location: index.php');
exit;
?>
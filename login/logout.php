<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/login/includes.php");

//logout
$user->logout(); 

//logged in return to index page
header('Location: index.php');
exit;
?>
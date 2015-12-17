<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/login/includes/head.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/session.php"); 

//logout
$user->logout(); 

//logged in return to index page
header('Location: index.php');
exit;
?>
<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/config.php"); 
include_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/login/classes/user.php");
$user = new User($db); 
?>
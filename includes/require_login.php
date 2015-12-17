<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/login/includes/head.php");

if( !$user->is_logged_in()) {
  header('Location: /treccs_summary_judge/login/login.php?next='.$_SERVER['REQUEST_URI']);
  exit;
}
?>
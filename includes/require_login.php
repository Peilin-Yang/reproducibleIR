<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/login/includes/head.php");

if( !$user->is_logged_in()) {
  header('Location: /reproducibleIR/login/login.php?next='.$_SERVER['REQUEST_URI']);
  exit;
}
?>
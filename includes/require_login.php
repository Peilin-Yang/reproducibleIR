<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/login/includes.php");

if( !$user->is_logged_in()) {
  header('Location: /login/login.php?next='.$_SERVER['REQUEST_URI']);
  exit;
}
?>
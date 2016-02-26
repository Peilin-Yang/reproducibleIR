<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/require_login.php");

function show_sidenav($cur_idx) {
  $navs = array(array('<li role="presentation"><a href="index.php">', '<i class="fa fa-list fa-fw"></i>My Models</a></li>'),
          array('<li role="presentation"><a href="add_function.php">','<i class="fa fa-edit fa-fw"></i>Add New Model</a></li>'));
  $navs[$cur_idx][0] = '<li role="presentation" class="active"><a href="#">';
  $html = '';
  foreach ($navs as $value) {
    $html .= implode('', $value);
  }
  return $html;
}



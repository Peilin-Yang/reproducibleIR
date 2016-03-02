<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/require_login.php");

function show_sidenav($cur_idx) {
  $navs = array(array('<li role="presentation"><a href="index.php">', 'Update Instruction</a></li>'));
  $navs[$cur_idx][0] = '<li role="presentation" class="active"><a href="#">';
  $html = '';
  foreach ($navs as $value) {
    $html .= implode('', $value);
  }
  return $html;
}



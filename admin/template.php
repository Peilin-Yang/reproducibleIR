<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/require_login.php");

function show_sidenav($cur_idx) {
  $navs = array(array('<li role="presentation"><a href="index.php">', 'Update Instruction</a></li>'),
    array('<li role="presentation"><a href="add_index_path.php">', 'Add Index Path</a></li>'),
    array('<li role="presentation"><a href="add_index_path.php">', 'Add Query Path</a></li>'),
    array('<li role="presentation"><a href="add_index_path.php">', 'Add Evaluation Path</a></li>'));
  $navs[$cur_idx][0] = '<li role="presentation" class="active"><a href="#">';
  $html = '';
  foreach ($navs as $value) {
    $html .= implode('', $value);
  }
  return $html;
}



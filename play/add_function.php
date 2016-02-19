<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/require_login.php");
?>

<!doctype html>
<html>
<head>
    <title><?php echo htmlentities(SITENAME." Add Function"); ?></title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <style type="text/css" media="screen">
      #editor { 
        position: relative !important;
        border: 1px solid lightgray;
        margin: auto;
        height: 80%;
        width: 80%;
      }
    </style>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
	</div>
<div class="container">
  <h2><i class="fa fa-list-ul fa-6x fa-fw"></i><strong>Edit Ranking Function</strong></h2>

  <hr/>

  <p class="text-center" id="waiting-span">
    <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
  </p>

  <div id="editor">function foo(items) {
      var x = "All this is syntax highlighted";
      return x;
  }</div>
  
</div>

<div class="scroll-top-wrapper ">
  <span class="scroll-top-inner">
    <i class="fa fa-2x fa-arrow-circle-up"></i>
  </span>
</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/ace/1.2.3/min/ace.js"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/iplastic");
    editor.getSession().setMode("ace/mode/c_cpp");
</script>

</body>
</html>
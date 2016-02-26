<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>Admin</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <style type="text/css" media="screen">
      #editor { 
        position: relative !important;
        border: 1px solid lightgray;
        margin: auto;
        width: 100%;
        font-size: 16px;
      }
    </style>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
	</div>
<div class="container">
  <h2><strong>My Account</strong></h2>
  <div class="row">
    <div class="col-sm-3 col-md-3 col-lg-3">
      <ul class="nav nav-pills nav-stacked">
        <?php echo(show_sidenav(0)); ?>
      </ul>
    </div>
    <div class="col-sm-9 col-md-9 col-lg-9">
      <form role="form" id="fform" method="POST" action="/api/admin/update_instruction.php" autocomplete="off">
        <input type="hidden" name="uid" id="uid" class="form-control">
        <div class="form-group">
          <label for="content">Model Implementation Instruction</label>
          <input type="hidden" name="content" class="form-control" id="content" placeholder="content">
          <div id="editor"></div> 
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
    <div class="scroll-top-wrapper ">
      <span class="scroll-top-inner">
        <i class="fa fa-2x fa-arrow-circle-up"></i>
      </span>
    </div>
  </div>

</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/ace/1.2.3/min/ace.js"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/iplastic");
  editor.getSession().setMode("ace/mode/c_cpp");
  var $editor = $('#editor');
  $editor.closest('form').submit(function() {
    var code = editor.getSession().getValue();
    $editor.prev('input[type=hidden]').val(code);
    $('input#uid').val($('#cur_uid').html());  
  });
</script>
<script type="text/javascript" src="/static/js/admin_update_ins.js"></script>
</body>
</html>
<?php
require_once "template.php";
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
        width: 100%;
        font-size: 16px;
      }
    </style>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
	</div>

<div class="container">
  <h2><strong>Admin</strong></h2>

  <div class="row">
    <div class="col-sm-3 col-md-3 col-lg-3">
      <ul class="nav nav-pills nav-stacked">
        <?php echo(show_sidenav(1)); ?>
      </ul>
    </div>
    <div class="col-sm-9 col-md-9 col-lg-9">
      <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#form" aria-controls="form" role="tab" data-toggle="tab">Implementation</a></li>
          <li role="presentation"><a href="#instruction" aria-controls="instruction" role="tab" data-toggle="tab">Instruction</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade in active" id="form">
            <form role="form" id="fform" method="POST" action="" autocomplete="off">
              <div class="form-group">
                <label for="fname">Model Name</label>
                <input type="text" name="fname" class="form-control" id="fname" placeholder="Model Name">
              </div>
              <div class="form-group">
                <label for="fpara">Model Parameters</label>
                <p class="help-block">This is just a string indicating the model belongs to model name above.</p>
                <input type="text" name="fpara" class="form-control" id="fpara" placeholder="Model Parameters">
              </div>
              <div class="form-group">
                <label for="fbody">Model Implementation</label>
                <input type="hidden" name="fbody" class="form-control" id="fbody" placeholder="Model Body">
                <div id="editor"></div> 
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="instruction">to be fetched from server</div>
        </div>

      </div>
      
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
  });
</script>
<script type="text/javascript" src="/static/js/add_function.js"></script>
</body>
</html>
<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title><?php echo htmlentities(SITENAME." Add Function"); ?></title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/default.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css"/>
    <link rel="stylesheet" href="/static/css/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/add_model.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_copyfrom"><?php echo $_GET['copyfrom']; ?></p>
	</div>

<div class="container">
  <h2><strong>My Account</strong></h2>
  
  <div class="row">
    <div id="myNavmenu" class="col-sm-3 col-md-3 col-lg-3 offcanvas">
      <ul class="nav nav-pills nav-stacked">
        <?php echo(show_sidenav(1)); ?>
      </ul>
    </div>
    <div class="main-content">
      <button id="btn-menu-toggle" type="button" data-toggle="offcanvas" data-target="#myNavmenu">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </button>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#form" aria-controls="form" role="tab" data-toggle="tab">Implementation</a></li>
          <li role="presentation"><a href="#instruction" aria-controls="instruction" role="tab" data-toggle="tab">Instruction</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content main-content">
          <div role="tabpanel" class="tab-pane fade in active" id="form">
            <form role="form" id="fform" method="POST" action="/api/play/add_update_model.php" autocomplete="off">
              <div class="form-group">
                <label for="mname">Model Name</label>
                <input type="text" name="mname" class="form-control" id="mname" placeholder="Model Name">
              </div>
              <div class="form-group">
                <label for="mpara">Model Parameters</label>
                <p class="help-block">This is just a string indicating the model belongs to model name above.</p>
                <input type="text" name="mpara" class="form-control" id="mpara" placeholder="Model Parameters">
              </div>
              <div class="form-group">
                <label for="mnotes">Model Notes</label>
                <p class="help-block">You can leave some notes about the model. You can use text between two dollar signs to add math equations.</p>
                <textarea name="mnotes" class="form-control" id="mnotes" rows="5" placeholder="Model Notes: original authors, publish year, etc."></textarea>
              </div>
              <div class="form-group">
                <label for="mbody">Model Implementation</label>
                <input type="hidden" name="mbody" class="form-control" id="mbody" placeholder="Model Body">
                <div id="editor"></div> 
              </div>
              <input type="hidden" name="uid" id="uid">
              <input type="hidden" name="apikey" id="apikey">
              <button type="submit" id="submit_model" class="btn btn-primary">Submit</button>
            </form>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="instruction">Cannot get model implementation instruction from server...</div>
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

<script type="text/javascript" src="//cdn.jsdelivr.net/ace/1.2.3/min/ace.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/highlight.min.js"></script>
<script type="text/javascript" src="/static/js/marked.js"></script>
<script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<script type="text/javascript" src="/static/js/jasny-bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/play.js"></script>
<script type="text/javascript" src="/static/js/add_model.js"></script>
</body>
</html>

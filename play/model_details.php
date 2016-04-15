<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>My Account</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/default.min.css">
    <link rel="stylesheet" href="/static/css/model_details.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_mid"><?php echo $_GET['mid']; ?></p>
	</div>
<div class="container">
  <h2><strong>My Account</strong></h2>
  <div class="row">
    <div class="col-sm-3 col-md-2 col-lg-2 col-sm-offset-9 col-md-offset-10 col-lg-offset-10">
      <form action="index.php">
        <button class="btn btn-default"><i class="fa fa-arrow-circle-left fa-6x fa-fw"></i>Back to Model List</button>
      </form>
    </div>
    <div class="main-content">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#form" aria-controls="form" role="tab" data-toggle="tab">Implementation</a></li>
        <li role="presentation"><a href="#instruction" aria-controls="instruction" role="tab" data-toggle="tab">Instruction</a></li>
      </ul>
      <!-- Tab panes -->
      <div class="tab-content main-content">
        <div id="status-content" class="row">
          <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="status-span">Created at:</div> <div id="submitted_dt"></div>
            <div class="status-span">Last Modified at:</div> <div id="last_modified_dt"></div>
            <div class="status-span">Last Compiled at:</div> <div id="last_compile_dt"></div>
          </div>
          
          <div class="col-sm-6 col-md-6 col-lg-6"> 
            <div id="compile_status"></div>
            <div id="compile_msg"></div>
          </div>     
        </div>

        <div role="tabpanel" class="tab-pane fade in active" id="form">
          <form role="form" id="fform" method="POST" action="/api/play/add_model.php" autocomplete="off">
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
              <input type="text" name="mnotes" class="form-control" id="mnotes" placeholder="Model Notes">
            </div>
            <div class="form-group">
              <label for="mbody">Model Implementation</label>
              <input type="hidden" name="mbody" class="form-control" id="mbody" placeholder="Model Body">
              <div id="editor"></div> 
            </div>
            <input type="hidden" name="uid" id="uid">
            <input type="hidden" name="apikey" id="apikey">
            <button type="submit" id="submit_model" class="btn btn-primary">Submit Modification</button>
          </form>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="instruction">Cannot get model implementation instruction from server...</div>
      </div>

    <p class="text-center" id="waiting-span">
      <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
    </p>
    
    <div class="scroll-top-wrapper ">
      <span class="scroll-top-inner">
        <i class="fa fa-2x fa-arrow-circle-up"></i>
      </span>
    </div>  
    </div>
  </div>

</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/ace/1.2.3/min/ace.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/highlight.min.js"></script>
<script type="text/javascript" src="/static/js/marked.js"></script>
<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js"></script>
<script type="text/javascript" src="/static/js/model_details.js"></script>
</body>
</html>
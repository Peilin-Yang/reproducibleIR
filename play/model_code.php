<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>Model Code</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/default.min.css">
    <link rel="stylesheet" href="/static/css/model_code.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_mid"><?php echo $_GET['mid']; ?></p>
	</div>
<div class="container">
  <div class="row">
    <h2><strong>Model Code</strong></h2>
    <button id="copy_create_btn" class="btn btn-primary btn-sm">Copy and Create</button>
  </div>
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="entity_heading">Name</div>
      <div class="entity_body" id="mname"></div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="entity_heading">Parameters</div>
      <div class="entity_body" id="mpara"></div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="entity_heading">Notes</div>
      <div class="entity_body" id="mnotes"></div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="entity_heading">Code</div>
      <div id="editor"></div>
    </div>
  </div>

  <p class="text-center" id="waiting-span">
    <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
  </p>
</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/ace/1.2.3/min/ace.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js"></script>
<script type="text/javascript" src="/static/js/model_code.js"></script>
</body>
</html>
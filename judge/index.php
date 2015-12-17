<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/require_login.php");
?>


<!doctype html>
<html>
<head>
    <title><?php echo htmlentities(SITENAME." Judge Main"); ?></title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/common/common_header.php"); ?>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
	  <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
	</div>
<div class="container">
  <h3><i class="fa fa-hand-o-down fa-6x fa-fw"></i><strong>Please Select a Year to Start Your Judgement</strong></h3>

  <p class="text-center" id="waiting-span">
    <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
  </p>

  <div id="year">
  </div>
</div>

<div class="scroll-top-wrapper ">
  <span class="scroll-top-inner">
    <i class="fa fa-2x fa-arrow-circle-up"></i>
  </span>
</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/common/common_footer.php"); ?>
<script type="text/javascript" src="../static/js/judge_main.js"></script>
</body>
</html>
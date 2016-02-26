<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>My Account</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
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
      <p class="text-center" id="waiting-span">
        <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
      </p>
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
</body>
</html>
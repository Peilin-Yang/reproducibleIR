<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/require_login.php");
?>


<!doctype html>
<html>
<head>
    <title><?php echo htmlentities(SITENAME." Judge Comparison"); ?></title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/common/common_header.php"); ?>
    <link href="../static/css/bootstrap-switch.min.css" media="all" rel="stylesheet" type="text/css" />
    <link href="../static/css/judge_list.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/common/navbar_compare_judge.php"); ?>
  <div style="display:none;">
    <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_page"><?php if (isset($_GET['page'])) echo $_GET['page']; else echo "1";?></p>
  </div>
<div class="container">
  <h3><i class="fa fa-list-ul fa-6x fa-fw"></i><strong>Choose the Better One!</strong></h3>

  <hr/>

  <p class="text-center" id="waiting-span">
    <i class="fa fa-refresh fa-spin fa-6x" style="font-size: 300%;"></i>
  </p>

  <div id="head" class="row">
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" id="count"></div>
  </div>

  <hr/>

  <div id="judge-list" class="panel-group" role="tablist" aria-multiselectable="true"></div>

  <nav>
    <ul class="pagination">
      <li class="disabled" id="left_page"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
      <li class="active page" data-page="1"><a href="#">1</a></li>
      <li class="disabled" id="right_page"><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
    </ul>
  </nav>
</div>

<div class="scroll-top-wrapper ">
  <span class="scroll-top-inner">
    <i class="fa fa-2x fa-arrow-circle-up"></i>
  </span>
</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/common/common_footer.php"); ?>

<script type="text/javascript" src="../static/js/star-rating.min.js"></script>
<script type="text/javascript" src="../static/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="../static/js/blockUI.js"></script>
<script type="text/javascript" src="../static/js/judge_comp_list.js"></script>

</body>
</html>
<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/includes/config.php";
?>

<!doctype html>
<html>
<head>
    <title>Comparison</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="/static/css/comparison.css">
</head>
<body>
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo SITE; ?>"><?php echo SITENAME; ?></a>
      </div>
    </div>
  </nav>
  <div class="container">
    <h2><strong>Comparisons</strong></h2>
    <div>
      <div>
      In this page, models that were successfully evaluated for the query sets 
      are directly compared on query set basis.
      </div>
      <div>
      Select a Query Set to start with:
      </div>
      <select id="query_select" class="form-control">
        
      </select>
      <div id="query_info"> 
        <ul>
          <li id="query_name"> </li>
          <li id="query_des"> </li>
          <li id="index_name"> </li>
          <li id="index_des"> </li>
          <li id="index_stats"> </li>
        </ul>
      </div>
      <div id="draw"> </div>
    </div>
  </div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>
<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="/static/js/comparison.js"></script>
</body>
</html>
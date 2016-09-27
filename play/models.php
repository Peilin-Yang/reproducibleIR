<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>Uploaded Models</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="/static/css/bootstrap-sortable.css">
    <link rel="stylesheet" href="/static/css/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/get_model.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
	</div>
<div class="container">
  <h2><strong>Uploaded Models</strong></h2>
  <div class="row">

    <table id="model-list-table" class="table table-striped table-hover">
        <thead>
          <tr>  
            <th>
              <i class="fa fa-user fa-fw"></i>User
            </th>
            <th>
              <i class="fa fa-tag fa-fw"></i>Model
            </th>
            <th>
              <i class="fa fa-cog fa-fw"></i>Paras.
            </th>
            <th>
              <i class="fa fa-clock-o fa-fw"></i>Last Modified Time
            </th>
          </tr>
        </thead>

        <tbody data-link="row" class="rowlink">

        </tbody>
      </table>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js"></script>
<script type="text/javascript" src="/static/js/jasny-bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/play.js"></script>
<script type="text/javascript" src="/static/js/models.js"></script>
</body>
</html>

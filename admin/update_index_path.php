<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>Admin</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="/static/css/add_index.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_iid"><?php echo $_GET['iid']; ?></p>
	</div>
<div class="container">
  <h2><strong>Update Index Path</strong></h2>
  <div class="row">
    <div class="col-sm-3 col-md-2 col-lg-2 col-sm-offset-9 col-md-offset-10 col-lg-offset-10">
      <form action="add_index_path.php">
        <button class="btn btn-default"><i class="fa fa-arrow-circle-left fa-6x fa-fw"></i>Back to Add Index</button>
      </form>
    </div>
    <div class="">
      <form role="form" id="fform" method="POST" action="/api/admin/add_update_index.php" autocomplete="off">
        <div class="form-group">
          <label for="name">Index Name</label>
          <input type="text" name="name" class="form-control" id="name" placeholder="Index Name">
        </div>
        <div class="form-group">
          <label for="notes">Notes</label>
          <p class="help-block">You can leave some notes about the index.</p>
          <input type="text" name="notes" class="form-control" id="notes" placeholder="Index Notes">
        </div>
        <div class="form-group">
          <label for="index_path">Index Path</label>
          <textarea name="index_path" id="index_path" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="index_stats">Add Index Statistics</label>
          <textarea name="index_stats" id="index_stats" class="form-control" rows="6"></textarea>
        </div>
        <input type="hidden" name="uid" id="uid">
        <input type="hidden" name="apikey" id="apikey">
        <input type="hidden" name="iid" id="iid">
        <button type="submit" id="submit_index" class="btn btn-primary">Submit Modification</button>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js"></script>
<script type="text/javascript" src="/static/js/update_index.js"></script>
</body>
</html>
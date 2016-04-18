<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>Admin</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
    <p id="cur_querytag"><?php echo $_GET['querytag']; ?></p>
	</div>
<div class="container">
  <h2><strong>Update Query Path</strong></h2>
  <div class="row">
    <div class="col-sm-3 col-md-2 col-lg-2 col-sm-offset-9 col-md-offset-10 col-lg-offset-10">
      <form action="add_query_path.php">
        <button class="btn btn-default"><i class="fa fa-arrow-circle-left fa-6x fa-fw"></i>Back to Add Query</button>
      </form>
    </div>
    <div class="">
      <form role="form" id="fform" method="POST" action="/api/admin/add_update_query.php" autocomplete="off">
        <div class="form-group">
          <label for="index_id">Index</label>
          <select name="index_id" class="form-control" id="index_id">
          </select>
        </div>
        <div class="form-group">
          <label for="name">Query Name</label>
          <input type="text" name="name" class="form-control" id="name" placeholder="Query Name">
        </div>
        <div class="form-group">
          <label for="notes">Notes</label>
          <p class="help-block">You can leave some notes about the index.</p>
          <input type="text" name="notes" class="form-control" id="notes" placeholder="Query Notes">
        </div>
        <div class="form-group">
          <label for="query_path">Add Query Path</label>
          <textarea name="query_path" id="query_path" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
          <label for="evaluation_path">Add Evaluation Path(query should be companied with evaluation)</label>
          <textarea name="evaluation_path" id="evaluation_path" class="form-control" rows="5"></textarea>
        </div>
        <input type="hidden" name="uid" id="uid">
        <input type="hidden" name="apikey" id="apikey">
        <input type="hidden" name="querytag" id="querytag">
        <button type="submit" id="submit_query" class="btn btn-primary">Submit Modification</button>
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
<script type="text/javascript" src="/static/js/update_query.js"></script>
</body>
</html>
<?php
require_once "template.php";
?>

<!doctype html>
<html>
<head>
    <title>Admin</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="/static/css/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/add_index.css">
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
	<div style="display:none;">
	  <p id="cur_uid"><?php echo $_SESSION['uid']; ?></p>
    <p id="cur_apikey"><?php echo $_SESSION['apikey']; ?></p>
	</div>
<div class="container">
  <h2><strong>Admin Panel</strong></h2>
  <div class="row">
    <div class="col-sm-3 col-md-3 col-lg-3">
      <ul class="nav nav-pills nav-stacked">
        <?php echo(show_sidenav(1)); ?>
      </ul>
    </div>
    <div class="col-sm-9 col-md-9 col-lg-9">
      <table id="index-list-table" class="table table-striped table-hover sortable">
        <caption>Existing Index Paths</caption>
        <thead>
          <tr>  
            <th>
              <i class="fa fa-tag fa-fw"></i>Name
            </th> 
            <th>
              <i class="fa fa-file-text-o fa-fw"></i>Path
            </th>
            <th>
              <i class="fa fa-clock-o fa-fw"></i>Added or Updated Time
            </th>
          </tr>
        </thead>

        <tbody data-link="row" class="rowlink">

        </tbody>
      </table>

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
          <label for="index_path">Add Index Path</label>
          <textarea name="index_path" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="index_stats">Add Index Statistics</label>
          <textarea name="index_stats" class="form-control" rows="6"></textarea>
        </div>
        <input type="hidden" name="uid" id="uid">
        <input type="hidden" name="apikey" id="apikey">
        <button type="submit" id="submit_index" class="btn btn-primary">Submit</button>
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
<script type="text/javascript" src="/static/js/jasny-bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/add_index.js"></script>
</body>
</html>
<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/login/includes.php"); ?>

<!doctype html>
<html>
<head>
    <title><?php echo htmlentities(SITENAME." Judge Comparison"); ?></title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
</head>
<body>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/navbar.php"); ?>
  <div style="display:none;">
    <p id="cur_uid"><?php if( $user->is_logged_in() ) {echo $_SESSION['uid'];} ?></p>
  </div>
  <div class="jumbotron">
    <div class="container">
      <h1>Welcome Aboard!</h1>
      <p>
      Reproducible Information Retrieval Evaluation System (<strong>RIRES</strong>) 
      aims to help researchers and students to quickly and easily implement ranking 
      models with small pieces of codes.

      </p>
    </div>
  </div>
<div class="container">

</div>

<footer style="margin-bottom:30px;">
</footer>

<?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_footer.php"); ?>

</body>
</html>
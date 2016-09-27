<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/login/includes.php");
?>

<!doctype html>
<html>
<head>
    <title>Comparison</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]."/common/common_header.php"); ?>
    <link rel="stylesheet" href="/static/css/comparison.css">
</head>
<body>
  <!-- nav bar of comparison does not need login -->
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
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="/play/comparison.php">Comparisons</a></li>
          <li><a href="/play/models.php">Uploaded Models</a></li>
          <?php 
            if (is_null($user)) {
              echo '<li><a href="/login/login.php?next='.$_SERVER['REQUEST_URI'].'" id="login">Log In</a></li>';
            } else {
              if( $user->is_logged_in() ) { 
                $username = $_SESSION['username'];
                $stmt = $db->prepare('SELECT isAdmin FROM users WHERE username = :username');
                $stmt->execute(array(':username' => $username));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $isAdmin = false;
                if($row['isAdmin'] == "1"){
                  $isAdmin = true;
                }
                echo '<li class="dropdown">
                  <a href="#" class="dropdown-toggle user" data-toggle="dropdown" role="button" aria-expanded="false">'.$username.'<span class="caret"></span></a>
                  <ul class="dropdown-menu nav-dropdown-menu" role="menu">';
                if ($isAdmin) {
                  echo '<li><a href="/admin/">Admin</a></li>';
                }
                echo '<li><a href="/play/">My Account</a></li>
                    <li>
                      <form action="/login/logout.php">
                      <button type="submit" id="logout" class="btn btn-danger form-control" title="Log Out"><i class="fa fa-sign-out fa-fw"></i> Log Out</button>
                      </form>
                    </li>
                  </ul>
                </li>';
              } else {
                echo '<li><a href="/login/login.php?next='.$_SERVER['REQUEST_URI'].'" id="login">Log In</a></li>';
              }
            }
          ?> 
          
        </ul>
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
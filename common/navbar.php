<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/includes/config.php";
?>

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
        ?> 
        
      </ul>
    </div>
  </div>
</nav>

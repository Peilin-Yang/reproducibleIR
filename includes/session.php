<?php
ob_start();
session_start();

$session_lifetime = 604800;
//var_dump($_SESSION['LAST_ACTIVITY']);
//var_dump(time());
//var_dump($session_lifetime);
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
//var_dump($_SESSION['LAST_ACTIVITY']);
?>
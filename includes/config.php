<?php
ob_start();
session_start();

//set timezone
//date_default_timezone_set('UTC');

//database credentials
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','rires');

//application address
define('FILEDIR', '/');
define('SITE','rires.info/index.php');
define('DIR','rires.info/login/');
define('SITENAME','RISE');
define('FromEmail','info@ReproducibleIR.org');
define('ReplyEmail','noreply@ReproducibleIR.org');

try {
	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db -> exec("set names utf8");

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}
?>

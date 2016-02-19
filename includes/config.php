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
define('SITE','http://rires.info:8080/index.php');
define('DIR','http://rires.info:8080/login/');
define('SITENAME','Reproducible Information Retrieval');
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

require_once ($_SERVER["DOCUMENT_ROOT"] . FILEDIR . "vendor/mandrill/mandrill/src/Mandrill.php"); //Not required with Composer
$mandrill = new Mandrill('i3LLpVJctGwbHWuDMzUaJw');
//var_dump($_SERVER["DOCUMENT_ROOT"]);
?>

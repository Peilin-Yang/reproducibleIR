<?php
ob_start();
session_start();

//set timezone
//date_default_timezone_set('UTC');

//database credentials
define('DBHOST','localhost');
define('DBUSER','treccs');
define('DBPASS','2HMstyCayMuMRpem');
define('DBNAME','treccs_summary_judgement');

//application address
define('FILEDIR', '/treccs_summary_judge/');
define('SITE','http://everlyn.info:8080/treccs_summary_judge/judge/index.php');
define('DIR','http://everlyn.info:8080/treccs_summary_judge/login/');
define('SITENAME','TREC Contextual Suggestion Summary Judgement');
define('FromEmail','info@TRECCSSJ.org');
define('ReplyEmail','noreply@TRECCSSJ.org');

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

require_once ($_SERVER["DOCUMENT_ROOT"] . FILEDIR . "/vendor/mandrill/mandrill/src/Mandrill.php"); //Not required with Composer
$mandrill = new Mandrill('i3LLpVJctGwbHWuDMzUaJw');
//var_dump($_SERVER["DOCUMENT_ROOT"]);
?>

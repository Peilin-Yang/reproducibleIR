<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/login/includes/head.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/treccs_summary_judge/includes/session.php"); 

function generate_apikey( $length = 64 ) {
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $apikey = substr( str_shuffle( $chars ), 0, $length );
    return $apikey;
}

//collect values from the url
$uid = trim($_GET['x']);
$active = trim($_GET['y']);

//if id is number and the active token is not empty carry on
if(is_numeric($uid) && !empty($active)){
	$apikey = generate_apikey();
	//update users record set the active column to Yes where the memberID and active value match the ones provided in the array
	$stmt = $db->prepare("UPDATE users SET active = 'Yes', apikey=:apikey, activateAt=:activateAt WHERE uid = :uid AND active = :active");
	$stmt->execute(array(
		':activateAt' => gmdate('Y-m-d H:i:s'),
		':uid' => $uid,
		':apikey' => $apikey, 
		':active' => $active
	));

	//if the row was updated redirect the user
	if($stmt->rowCount() == 1){

		//redirect to login page
		header('Location: login.php?action=active');
		exit;

	} else {
		echo "Your account could not be activated."; 
	}
	
}
?>
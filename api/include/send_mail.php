<?php
require_once "Mail.php";
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/config.php");

function send_mail($to, $subject, $body) {
    $from = 'admin@rires.info';
    $headers = array(
        'From' => $from,
        'To' => $to,
        'Subject' => $subject
    );

    $smtp = Mail::factory('smtp', array(
            'host' => 'ssl://smtp.zoho.com',
            'port' => '465',
            'auth' => true,
            'username' => 'admin@rires.info',
            'password' => 'Franklyn841208!'
        ));

    $mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
        echo('<p>' . $mail->getMessage() . '</p>');
    } else {
        echo('<p>Message successfully sent!</p>');
    }
}
//send_mail('<yangpeilyn@gmail.com>', "Hi", "Hello World!");
?>
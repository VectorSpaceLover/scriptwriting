<?php

require 'class.phpmailer.php';
require 'PHPMailerAutoload.php';
define('GUSER', 'team@procedurerock.com'); // GMail username
define('GPWD', 'Itsarembrandt6'); // GMail password

$from="team@procedurerock.com";
$from_name="team@procedurerock.com";

$cc="vsc.india01@gmail.com";
$to="team@procedurerock.com";   

$mail = new PHPMailer();  // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true;  // authentication enabled
$mail->Host = 'ssl://smtp.gmail.com:465';
//$mail->Host = "smtp.gmail.com";
//$mail->Port = 465;
$mail->Username = GUSER;  
$mail->Password = GPWD;           
$mail->SetFrom($from, $from_name);



$mail->Body = "Thank you";

$mail->AddAddress($to);

$mail->AddCC($cc);
$mail->Send();



//   
// 	$mail->Username = "⁠⁠⁠team@procedurerock.com";
// 	$mail->Password = "Donkey_Kong66";

<?php

//require 'class.phpmailer.php';
//require 'PHPMailerAutoload.php';
//define('GUSER', 'team@procedurerock.com'); // GMail username
//define('GPWD','Itsarembrandt6'); // GMail password


function encrypt_decrypt($string, $action = 'encrypt')
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
    $secret_iv = '5fgf5HJ5g27'; // user define secret key
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}


function send_mail($email,$message,$subject)
{

    $from="team@screenwriting.com";
    $from_name="team@screenwriting.com";
    //$cc="";
    $to=$email;

    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->Host = 'ssl://smtp.gmail.com:465';
    $mail->Username = 'team@procedurerock.com';
    $mail->Password = 'Itsarembrandt6';
    $mail->SetFrom($from, $from_name);

    $mail->Subject = $subject;

    $mail->Body=$message;
    $mail->IsHTML();
    $mail->AddAddress($to);

    // $mail->AddCC($cc);
    $mail->Send();

    return true;
}


?>

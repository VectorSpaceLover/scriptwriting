<?php

// require 'class.phpmailer.php';
require '../vendor/autoload.php';
require 'PHPMailerAutoload.php';
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


// function send_mail($email, $from, $password, $message,$subject)
// {

//     // $from = "team@screenwriting.com";
//     // $from_name = "team@screenwriting.com";
//     //$cc="";
//     $to = $email;

//     $mail = new PHPMailer();  // create a new object
//     $mail->IsSMTP(); // enable SMTP
//     $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
//     $mail->SMTPAuth = true;  // authentication enabled
//     $mail->Host = 'ssl://smtp.gmail.com:465';
//     // $mail->Username = 'team@procedurerock.com';
//     // $mail->Password = 'Itsarembrandt6';
//     $mail->Username = $from;
//     $mail->Password = $password;
//     // $mail->SetFrom($from, $from_name);
//     $mail->SetFrom($from);

//     $mail->Subject = $subject;

//     $mail->Body=$message;

//     $mail->IsHTML();
//     $mail->AddAddress($to);

//     // $mail->AddCC($cc);
//     $mail->Send();

//     return true;
// }


function send_email_verify_code($email, $from, $password, $message){

    $mail = new PHPMailer(true);                            

    try {
        //Server settings
        $mail->isSMTP();                                     
        $mail->Host = 'smtp.gmail.com';                      
        $mail->SMTPAuth = true;                             
        // $mail->Username = 'sourcecod404@gmail.com';     
        // $mail->Password = 'programmer54321';             
        $mail->Usename = $from;
        $mail->Password = $password;
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );                         
        $mail->SMTPSecure = 'ssl';                           
        $mail->Port = 465;                                   

        //Send Email
        // $mail->setFrom('sourcecod404@gmail.com');
        $mail->setFrom($from);

        //Recipients
        $mail->addAddress($email);              
        // $mail->addReplyTo('sourcecod404@gmail.com');
        $mail->addReplyTo($from);

        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = "Account registration confirmation";
        $mail->Body    = $message;

        $mail->send();
        return array('success' => true, 'message' => '');
        // header("location:verification.php?firstname=".$firstname."&lastname=".$lastname."&email=".$email."");
        
    } catch (Exception $e) {
        // $_SESSION['result'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
        // $_SESSION['status'] = 'error';
        return array('success' => false, 'message' => 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo);
    }
}

?>

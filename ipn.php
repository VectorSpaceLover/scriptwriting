<?php
//Include DB configuration file
include 'php/databaseConfig.php';

$file=fopen("ipn1.txt","a");

fwrite($file,print_r("Paypal LOG",true)."\n");

// Include phpmailer class
require 'PHPMailer/PHPMailerAutoload.php';

define('GUSER', 'coupons@morevaluecoupons.com'); // GMail username
define('GPWD', ':eMZ&vFr[Ej5'); // GMail password


$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}

$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
    $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
}

$paypalURL = "https://www.paypal.com/cgi-bin/webscr";

fwrite($file,print_r($paypalURL,true)."\n");
$ch = curl_init($paypalURL);
if ($ch == FALSE) {
    return FALSE;
}
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSLVERSION, 6);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name'));
$res = curl_exec($ch);

$tokens = explode("\r\n\r\n", trim($res));

$res = trim(end($tokens));

fwrite($file,print_r($res,true)."\n");
fwrite($file,print_r("Verified Process Done",true)."\n");
fwrite($file,print_r($_POST,true)."\n");

fwrite($file,print_r('Dinesh',true)."\n");

if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {
    //Payment data
    $txn_id = $_POST['txn_id'];
    $payment_gross = $_POST['mc_gross'];
    $currency_code = $_POST['mc_currency'];
    $payment_status = $_POST['payment_status'];
    $payer_email = $_POST['payer_email'];
    $order_item_number = '';
    $order_item_quantity = 0;
    $coupon_item_name = strtolower($_POST['item_number']);


    fwrite($file,print_r($coupon_item_name,true)."\n");


    if(!empty($coupon_item_name) || $coupon_item_name != '') {
        $coupons = $conn->query("select * from coupons WHERE coupon_name = '".$coupon_item_name."'");
        fwrite($file,print_r("select * from coupons WHERE coupon_name = '".$coupon_item_name."'",true)."\n"); 

    } else {
        $coupons = $conn->query("select * from coupons WHERE FIND_IN_SET('".$payment_gross."',amount) limit 1");
        fwrite($file,print_r("select * from coupons WHERE FIND_IN_SET('".$payment_gross."',amount) limit 1",true)."\n");
    }


    if($coupons->num_rows > 0){

        while($coupon = $coupons->fetch_assoc()){

            $amount = explode(',',$coupon['amount']);
            $quantity = explode(',',$coupon['quantity']);

            if(in_array($payment_gross,$amount)) {        
                $order_item_number= $coupon['coupon_name'];
                $index = array_search($payment_gross,$amount);
                $order_item_quantity = $quantity[$index];
            }
        }

    }else{
        $order_item_number= 'Not Found';
        $order_item_quantity = 0;
    }


    fwrite($file,print_r($order_item_number,true)."\n");


    fwrite($file,print_r('order_item_number'.$order_item_number,true)."\n");


    //Check if payment data exists with the same TXN ID.
    $prevPayment = $conn->query("SELECT payment_id FROM payments WHERE txn_id = '".$txn_id."' and payer_email IS NOT NULL and payer_email !=''");
    if($prevPayment->num_rows > 0){
        fwrite($file,print_r("SELECT payment_id FROM payments WHERE txn_id = '".$txn_id."' and payer_email IS NOT NULL and payer_email !=''",true)."\n");
        fwrite($file,print_r('existing'.$txn_id,true)."\n");
        exit();
    }else{

        fwrite($file,print_r('else statement',true)."\n");
        $lastRecord = $conn->query("SELECT * FROM payments ORDER BY payment_id DESC LIMIT 1");
        $lastRecordResult= $lastRecord->fetch_assoc();
        fwrite($file,print_r($lastRecordResult,true)."\n");

        $payment_id = $lastRecordResult['payment_id'] + 1;
        
        if($payer_email !='' && $lastRecordResult['payment_status'] != 'initiated')
        {

            $insertPayment = $conn->query("INSERT INTO payments(txn_id,payment_gross,currency_code,payment_status,payer_email) VALUES('".$txn_id."','".$payment_gross."','".$currency_code."','".$payment_status."','".$payer_email."')");

            $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$payer_email."')");


            fwrite($file,print_r("INSERT INTO payments(txn_id,payment_gross,currency_code,payment_status,payer_email) VALUES('".$txn_id."','".$payment_gross."','".$currency_code."','".$payment_status."','".$payer_email."')",true)."\n");

            if($insertPayment){
               
                $insertOrderItem = $conn->query("INSERT INTO order_items(payment_id,item_number,quantity,gross_amount) VALUES('".$payment_id."','".$order_item_number."','".$order_item_quantity."','".$payment_gross."')");

                fwrite($file,print_r("INSERT INTO order_items(payment_id,item_number,quantity,gross_amount) VALUES('".$payment_id."','".$order_item_number."','".$order_item_quantity."','".$payment_gross."')",true)."\n");


            }

        }
        else
        {
            if($lastRecordResult['payment_status'] == 'initiated' && $lastRecordResult['payment_gross'] == $payment_gross)
            {
                fwrite($file,print_r('insert_update',true)."\n");

                if($payer_email == $lastRecordResult['payer_email']) {
                    $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$lastRecordResult['payer_email']."')");
                }

                //Insert tansaction data into the database
                $insertPayment = $conn->query("update payments set txn_id='".$txn_id."',payment_gross='".$payment_gross."',currency_code='".$currency_code."',payment_status='".$payment_status."',payer_email='".$lastRecordResult['payer_email']."' where payment_id='".$lastRecordResult['payment_id']."'");


                fwrite($file,print_r("update payments set txn_id='".$txn_id."',payment_gross='".$payment_gross."',currency_code='".$currency_code."',payment_status='".$payment_status."',payer_email='".$lastRecordResult['payer_email']."' where payment_id='".$lastRecordResult['payment_id']."'",true)."\n");

                if($insertPayment){

                    fwrite($file,print_r('update first done',true)."\n");

                    //Insert order items into the database
                    $payment_id = $lastRecordResult['payment_id'];
                    $payer_email = $lastRecordResult['payer_email'];
                    $insertOrderItem = $conn->query("update order_items set item_number='".$order_item_number."',quantity='".$order_item_quantity."',gross_amount='".$payment_gross."' where payment_id='".$lastRecordResult['payment_id']."' ");

                    fwrite($file,print_r("update order_items set item_number='".$order_item_number."',quantity='".$order_item_quantity."',gross_amount='".$payment_gross."' where payment_id='".$lastRecordResult['payment_id']."' ",true)."\n");


                    fwrite($file,print_r($payment_id,true)."\n");

                    fwrite($file,print_r("update order_items set item_number='".$order_item_number."',quantity='".$order_item_quantity."',gross_amount='".$payment_gross."' where payment_id='".$lastRecordResult['payment_id']."' ",true)."\n");

                    fwrite($file,print_r("update order_items set item_number='".$order_item_number."',quantity='".$order_item_quantity."',gross_amount='".$payment_gross."' where payment_id='".$lastRecordResult['payment_id']."' ",true)."\n");


                }

            } 
            else if($lastOrderRecordResult['payment_id'] != $lastRecordResult['payment_id']) {

                $insertOrderItem = $conn->query("INSERT INTO order_items(payment_id,item_number,quantity,gross_amount) VALUES('".$payment_id."','".$order_item_number."','".$order_item_quantity."','".$payment_gross."')");
                $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$payer_email."')");


            }  else {
                $insertOrderItem = $conn->query("INSERT INTO order_items(payment_id,item_number,quantity,gross_amount) VALUES('".$payment_id."','".$order_item_number."','".$order_item_quantity."','".$payment_gross."')");
                $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$payer_email."')");
            }

        }

        $to=urldecode($payer_email);

        fwrite($file,print_r($to,true)."\n");


        if($to != '')
        {

            fwrite($file,print_r('to',true)."\n");

            $cc="superctrsavings@gmail.com";
            $cc1="morevaluecoupons@gmail.com";
            $cc2="vsc.india01@gmail.com";

            global $error;

            $mail = new PHPMailer(); // create a new object
            $mail->isSMTP(); // enable SMTP
            //$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->IsHTML(true);
            $mail->Host = 'smtp.mail.us-west-2.awsapps.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->Username = 'coupons@morevaluecoupons.com';
            $mail->Password = ':eMZ&vFr[Ej5';
            $mail->SetFrom('coupons@morevaluecoupons.com', 'morevaluecoupons');



            $results = $conn->query("select * from order_items where payment_id = (select payment_id from payments order by payment_id desc limit 1) order by id desc limit 1");

            fwrite($file,print_r($results,true)."\n");


            while($row = $results->fetch_assoc()){

                $quantity=$row['quantity'];
                $payment_amount=$row['gross_amount'];

                fwrite($file,print_r($row,true)."\n");
                fwrite($file,print_r('results1',true)."\n");

                $coupon_name = $row['item_number'];  
                fwrite($file,print_r('results2',true)."\n");

                $coupon_type = $conn->query("select coupon_type from coupons where coupon_name='".$coupon_name."'");

                $coupon_type = mysqli_fetch_array($coupon_type);
                fwrite($file,print_r('results3',true)."\n");

                if($coupon_type['coupon_type'] == 'lowes' || $coupon_type['coupon_type'] == 'morevaluecoupons' ) {


                    $subject="Here is your MORE VALUE COUPONS order";

                    $mail->Subject = $subject;

                    // Email body content
                    $mailContent = "<!DOCTYPE html>
                                    <html>
                                    <body>
                                    <table width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                                    <tbody>
                                    <tr>
                                    <td>
                                    <table style='font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;width:600px;margin:0 auto;color:#444444' width='600' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                                    <tbody>

                                    <tr>
                                    <td style='padding:20px;background:red'> 
                                    <h1 style='text-align:center;color:white'>MORE VALUE COUPONS</h1>
                                    </td>
                                    </tr>
                                    <tr>

                                    <td>

                                    <h2 style='text-align:center'>Thank you for your coupon order </h2>

                                    </td>
                                    </tr>


                                    <tr>
                                    <td style='padding:40px 20px 10px;font-size:20px;font-weight:bold;color:#000000'>Hello,</td>
                                    </tr>
                                    <tr>
                                    <td style='padding:8px 40px;color:rgb(0,0,0);text-align:justify;line-height:26px'><font size='4'>

                                    Attached to this email is your digital purchase which has been sent within seconds after you placed your order.  <br><br> 

                                    Simply open and print using a PDF reader. Also the bar code can be scanned directly from your mobile phone. <br><br> 

                                    If you encounter any problems please contact me as indicated below - I will respond as soon as possible. <br><br> 

                                    Best Regards <br><br>Patrick 
                                    <br><br>Note: You may receive this same message with the attachments a second time time from another email address. This second email is sent later for redundancy purposes to ensure deliver of your order.<br><br>Contact Information: <br><br>Email: morevaluecoupons@gmail.com<br><br>
                                    Text: 910-269-8701<br><br>
                                    Order: http://morevaluecoupons.com<br><br>
                                    </font></td>
                                    </tr>
                                    <tr>

                                    </tr>
                                    </tbody>
                                    </table>
                                    </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    </body>
                                    </html>";

                    $mail->Body = $mailContent;


                } else if($coupon_type['coupon_type'] == 'competitor') {

                    $subject="This is confirmation of your order";

                    $mail->Subject = $subject;

                    // Email body content
                    $mailContent = "<!DOCTYPE html>
                    <html>
                    <body>
                    <table width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody>
                    <tr>
                    <td>
                    <table style='font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;width:600px;margin:0 auto;color:#444444' width='600' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody>

                    <tr>
                    <td style='padding:20px;background:red'> 
                    <h1 style='text-align:center;color:white'>MORE VALUE COUPONS</h1>
                    </td>
                    </tr>
                    <tr>

                    <td>

                    <h2 style='text-align:center'>Thank you for your coupon order </h2>

                    </td>
                    </tr>
                    <tr>
                    <td style='padding:40px 20px 10px;font-size:20px;font-weight:bold;color:#000000'>Hello,</td>
                    </tr>
                    <tr>
                    <td style='padding:8px 40px;color:rgb(0,0,0);text-align:justify;line-height:26px'><font size='4'>

                    Thank you for your purchase. It will be processed and mailed ASAP via the USPS and you should have them soon.  <br><br> 

                    Please direct any communication with me to: superctrsavings@gmail.com  or, the contact information provided on the website. <br><br> 

                    Please be sure to check out all the savings available on our website for Lowes and Home Depot, ...and of course - try our other restaurant coupons and codes by visiting
                    https://superctrsavings.com <br><br> 

                    Best Regards <br><br>Patrick 
                    <br><br>Note: You may receive this same message with the attachments a second time time from another email address. This second email is sent later for redundancy purposes to ensure deliver of your order.<br><br>Contact Information: <br><br>Email: superctrsavings@gmail.com<br><br>
                    Text: 910-269-8701<br><br>
                    Order: http://morevaluecoupons.com<br><br>
                    </font></td>
                    </tr>
                    <tr>

                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    </body>
                    </html>";
                    $mail->Body = $mailContent;


                } else if($coupon_type['coupon_type'] == 'food') {

                    $subject="Your order has been received";

                    $mail->Subject = $subject;

                    // Email body content
                    $mailContent = "<!DOCTYPE html>
                    <html>
                    <body>
                    <table width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody>
                    <tr>
                    <td>
                    <table style='font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;width:600px;margin:0 auto;color:#444444' width='600' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody>

                    <tr>
                    <td style='padding:20px;background:red'> 
                    <h1 style='text-align:center;color:white'>MORE VALUE COUPONS</h1>
                    </td>
                    </tr>
                    <tr>

                    <td>

                    <h2 style='text-align:center'>Thank you for your coupon order </h2>

                    </td>
                    </tr>
                    <tr>
                    <td style='padding:40px 20px 10px;font-size:20px;font-weight:bold;color:#000000'>Hello,</td>
                    </tr>
                    <tr>
                    <td style='padding:8px 40px;color:rgb(0,0,0);text-align:justify;line-height:26px'><font size='4'>

                    Thank you for the order. It will be processed and mailed ASAP via the USPS and you should have it soon.<br><br> 

                    If needed - please contact me from the information provided on the website. <br><br> 

                    Please be sure to check out all the savings available on our website for Lowes and Home Depot, ...and of course - try our other restaurant coupons and codes by visiting
                    https://superctrsavings.com <br><br> 

                    Best Regards <br><br>Patrick 
                    <br><br>Note: You may receive this same message with the attachments a second time time from another email address. This second email is sent later for redundancy purposes to ensure deliver of your order.<br><br>Contact Information: <br><br>Email: superctrsavings@gmail.com<br><br>
                    Text: 910-269-8701<br><br>
                    Order: http://morevaluecoupons.com<br><br>
                    </font></td>
                    </tr>
                    <tr>

                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    </body>
                    </html>";
                    $mail->Body = $mailContent;


                }

                fwrite($file,print_r("Email Sent123",true)."\n");


                if(!empty($row["item_number"]) && $row['item_number'] != '')
                {

                    $sql = "SELECT * FROM ".$coupon_name." ORDER BY file_name ASC limit $quantity";

                    $subject="Here is your MORE VALUE COUPONS order";

                    $mail->Subject = $subject;

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)) {
                            $mail->AddAttachment($coupon_name."/".$row["file_name"]);
                            $attachments[]=$row['file_name'];
                            fwrite($file,print_r($row['file_name'],true)."\n");
                        }
                    } 

                    $mail->AddAddress($to);

                    $mail->AddCC('morevaluecoupons@gmail.com', 'Patrick Hooks');
                    $mail->AddCC($cc1);
                    $mail->AddCC($cc2);
                    fwrite($file,print_r('results4',true)."\n");
                    if(!$mail->send() && empty($attachments)) {
                        fwrite($file,print_r($mail->ErrorInfo,true)."\n");
                    } else {

                        fwrite($file,print_r("Email Sent",true)."\n");

                        $delete = "SELECT * FROM ".$coupon_name." ORDER BY file_name ASC limit $quantity";

                        fwrite($file,print_r($delete,true)."\n");

                        $deleteResult = mysqli_query($conn, $delete);

                        if (mysqli_num_rows($deleteResult) > 0) {
                            // output data of each row
                            $i=0;
                            while($del = mysqli_fetch_assoc($deleteResult)) {
                                $deleteSql="Delete from ".$coupon_name." where id=".$del['id']."";
                                fwrite($file,print_r($deleteSql,true)."\n");
                                $deleteResultSet = mysqli_query($conn, $deleteSql);
                                $filename = $coupon_name.'/'.$del['file_name'];
                                chmod($filename, 0777);
                                $destination_path = '/var/www/html/backups/';
                                rename($filename,$destination_path.$del['file_name']);
                            }
                        }

                        $mail->clearAttachments();
                        $updateStatus = $conn->query("update payments set sent=1 where payer_email='".$to."' and payment_id='".$payment_id."'");

                    }



                }


                // sending email





            }
        }




    }
}

?>

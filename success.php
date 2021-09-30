<?php

//Include DB configuration file
include 'php/databaseConfig.php';

$file=fopen("t4.txt","a");

fwrite($file,print_r("Success page Logs",true)."\n");
fwrite($file,print_r($_GET,true)."\n");
die();
// Include phpmailer class
require 'PHPMailer/PHPMailerAutoload.php';

define('GUSER', 'coupons@morevaluecoupons.com'); // GMail username
define('GPWD', ':eMZ&vFr[Ej5'); // GMail password

//Payment data
$txn_id = $_GET['tx'];
$payment_gross = $_GET['amt'];
$currency_code = $_GET['cc'];
$payment_status = $_GET['st'];
$payer_email = $_GET['cm'];
$coupon_item_number = strtolower($_GET['item_number']);

$order_item_number = '';
$order_item_quantity = 0;


fwrite($file,print_r($coupon_item_number,true)."\n");


if(!empty($coupon_item_number) || $coupon_item_number != '') {
    $coupons = $conn->query("select * from coupons WHERE coupon_name = '".$coupon_item_number."'");
    fwrite($file,print_r("select * from coupons WHERE coupon_name = '".$coupon_item_number."'",true)."\n");

} else {
    $coupons = $conn->query("select * from coupons WHERE FIND_IN_SET('".$payment_gross."',amount) limit 1");
    fwrite($file,print_r("select * from coupons WHERE FIND_IN_SET('".$payment_gross."',amount) limit 1",true)."\n");
}




if($coupons->num_rows > 0){

    while($coupon = $coupons->fetch_assoc()){

        $amount = explode(',',$coupon['amount']);
        $quantity = explode(',',$coupon['quantity']);

        if(in_array($_GET['amt'],$amount)) {
            $order_item_number= $coupon['coupon_name'];
            $index = array_search($_GET['amt'],$amount);
            $order_item_quantity = $quantity[$index];
        }
    }

}else{
    $order_item_number= 'Not Found';
    $order_item_quantity = 0;
}



fwrite($file,print_r($order_item_number,true)."\n");


//Check if payment data exists with the same TXN ID.
$prevPayment = $conn->query("SELECT payment_id FROM payments WHERE txn_id = '".$txn_id."' and payer_email IS NOT NULL and payer_email !=''");
if($prevPayment->num_rows > 0){
    exit();
}else{

    fwrite($file,print_r('else statement',true)."\n");
    $lastRecord = $conn->query("SELECT * FROM payments ORDER BY payment_id DESC LIMIT 1");
    $lastRecordResult= $lastRecord->fetch_assoc();
    //    fwrite($file,print_r($lastRecord,true)."\n");
    fwrite($file,print_r($lastRecordResult,true)."\n");

    $lastOrderRecord = $conn->query("SELECT * FROM order_items ORDER BY payment_id DESC LIMIT 1");
    $lastOrderRecordResult= $lastOrderRecord->fetch_assoc();

    fwrite($file,print_r($lastOrderRecordResult,true)."\n");

    fwrite($file,print_r($payer_email,true)."\n");

     $payment_id = $lastRecordResult['payment_id'] + 1;

    if($payer_email !='' && $lastRecordResult['payment_status'] != 'initiated')
    {

        $insertPayment = $conn->query("INSERT INTO payments(txn_id,payment_gross,currency_code,payment_status,payer_email) VALUES('".$txn_id."','".$payment_gross."','".$currency_code."','".$payment_status."','".$payer_email."')");

        $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$payer_email."')");


        fwrite($file,print_r("INSERT INTO payments(txn_id,payment_gross,currency_code,payment_status,payer_email) VALUES('".$txn_id."','".$payment_gross."','".$currency_code."','".$payment_status."','".$payer_email."')",true)."\n");

        if($insertPayment){
            //Insert order items into the database

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

            $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$payer_email."')");

            $insertOrderItem = $conn->query("INSERT INTO order_items(payment_id,item_number,quantity,gross_amount) VALUES('".$payment_id."','".$order_item_number."','".$order_item_quantity."','".$payment_gross."')");

        } else {
            $insertEmail = $conn->query("INSERT INTO payment_emails(paypal_email,receiving_email) VALUES('".$payer_email."','".$payer_email."')");

            $insertOrderItem = $conn->query("INSERT INTO order_items(payment_id,item_number,quantity,gross_amount) VALUES('".$payment_id."','".$order_item_number."','".$order_item_quantity."','".$payment_gross."')");
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

        fwrite($file,print_r('results',true)."\n");


        while($row = $results->fetch_assoc()){
            $quantity=$row['quantity'];
            $payment_amount=$row['gross_amount'];

            fwrite($file,print_r($row,true)."\n");

            $coupon_name = $row['item_number'];
            fwrite($file,print_r($coupon_name,true)."\n");


            $coupon_type = $conn->query("select coupon_type from coupons where coupon_name='".$coupon_name."'");

            $coupon_type = mysqli_fetch_array($coupon_type);

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




            if(!empty($row["item_number"]) && $row['item_number'] != '')
            {

                $sql = "SELECT * FROM ".$coupon_name." ORDER BY file_name ASC limit $quantity";
                fwrite($file,print_r($sql,true)."\n");
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

                if(!$mail->send() && empty($attachments)) {
                    fwrite($file,print_r($mail->ErrorInfo,true)."\n");
                }
                else {

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


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
            /* Set a background image by replacing the URL below */
            body {
                background: url('https://source.unsplash.com/twukN12EN7c/1920x1080') no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                background-size: cover;
                -o-background-size: cover;
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light static-top mb-5 shadow">
            <div class="container">
                <a class="navbar-brand" href="#">MORE VALUE COUPONS</a>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container">
            <div class="card border-0 shadow my-5">
                <div class="card-body p-5">
                    <h3 class="font-weight-light text-center">Your payment has been successful.</h3>
                    <h6 class="font-weight-light text-center">
                        Please check your inbox or spam for your coupon order.
                    </h6>
                </div>
            </div>
        </div>
        <script>

            setTimeout(function() {
                window.location.href = 'https://morevaluecoupons.com';
            }, 5000);

        </script>

    </body>
</html>

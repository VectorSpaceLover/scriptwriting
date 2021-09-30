<?php

include_once 'session.php';
// Include configuration file
include_once 'callbacks/config.php';

// Include PayPalPro PHP library
require_once 'PaypalPro.class.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Buyer information
	$name = $_POST['name_on_card'];
	$nameArr = explode(' ', $name);
    $firstName = !empty($nameArr[0])?$nameArr[0]:'';
    $lastName = !empty($nameArr[1])?$nameArr[1]:'';
    $email = $_SESSION['email'];
	// Card details
	$creditCardNumber = trim(str_replace(" ","",$_POST['card_number']));
	$creditCardType = $_POST['card_type'];
	$expYear = $_POST['expiry_year'];
	$expMonth = $_POST['expiry_month'];
	$cvv = $_POST['cvv'];
  $payableAmount = $_POST['amount'];
    // Create an instance of PaypalPro class
	$paypal = new PaypalPro();

	// Payment details
    $paypalParams = array(
        'paymentAction' => 'Sale',
        'amount' => $payableAmount,
        'currencyCode' => $currency,
        'creditCardType' => $creditCardType,
        'creditCardNumber' => $creditCardNumber,
        'expMonth' => $expMonth,
        'expYear' => $expYear,
        'cvv' => $cvv,
        'firstName' => $firstName,
        'lastName' => $lastName
    );

	// Call PayPal API
    $response = $paypal->paypalCall($paypalParams);
    $paymentStatus = strtoupper($response["ACK"]);

    if($paymentStatus == "SUCCESS"){
		// Transaction info
		$transactionID = $response['TRANSACTIONID'];
		$paidAmount = $response['AMT'];

		// Insert tansaction data into the database
    $insert = mysqli_query($con,"INSERT INTO paypal_payments(txn_id,payment_gross,currency_code,payment_status,name,payer_email) VALUES('".$transactionID."','".$paidAmount."','".$currency."','".$paymentStatus."','".$name."','".$email."')");
    $last_insert_id = $con->insert_id;

		$data['status'] = 1;
        $data['orderID'] = $last_insert_id;


    }else{
         $data['status'] = 0;
    }

	// Transaction status
    echo json_encode($data);
}
?>

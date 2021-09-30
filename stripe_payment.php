<?php

require_once 'session.php';
// Include configuration file
require_once 'callbacks/config.php';

// Include phpmailer class
require 'callbacks/PHPMailerAutoload.php';

$payment_id = $statusMsg = '';
$ordStatus = 'error';

// Check whether stripe token is not empty
if(!empty($_POST['stripeToken'])){

  // Retrieve stripe token, card and user info from the submitted form data
  $token  = $_POST['stripeToken'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $card_number = preg_replace('/\s+/', '', $_POST['card_number']);
  $card_exp_month = $_POST['card_exp_month'];
  $card_exp_year = $_POST['card_exp_year'];
  $card_cvc = $_POST['card_cvc'];
  $itemPrice=$_POST['coupon_price'];


  // Include Stripe PHP library
  require_once 'stripe-php/init.php';

  // Set API key
  \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

  // Add customer to stripe
  $customer = \Stripe\Customer::create(array(
    'email' => $email,
    'source'  => $token
  ));


  // Unique order ID
  $orderID = strtoupper(str_replace('.','',uniqid('', true)));

  // Convert price to cents
  $itemPriceCents = ($itemPrice*100);



  // Charge a credit or a debit card
  $charge = \Stripe\Charge::create(array(
    'customer' => $customer->id,
    'amount'   => $itemPriceCents,
    'currency' => 'USD',
    'metadata' => array(
      'order_id' => $orderID
    )
  ));


  // Retrieve charge details
  $chargeJson = $charge->jsonSerialize();



  // Check whether the charge is successful
  if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){
    // Order details
    $transactionID = $chargeJson['balance_transaction'];
    $paidAmount = $chargeJson['amount'];
    $paidAmount = ($paidAmount/100);
    $paidCurrency = $chargeJson['currency'];
    $payment_status = $chargeJson['status'];

    // Include database connection file
    include_once 'callbacks/config.php';



    // Insert tansaction data into the database

    $insertStripe = mysqli_query($con,"INSERT INTO stripe_payments(txn_id,payment_gross,currency_code,payment_status,name,payer_email) VALUES('".$transactionID."','".$paidAmount."','".$paidCurrency."','".$payment_status."','".$name."','".$email."')");

    // If the order is successful
    if($payment_status == 'succeeded'){
      $ordStatus = 'success';
      $statusMsg = 'Your Payment has been Successful!';
      $update_user = mysqli_query($con,"UPDATE users SET txn_id='".$transactionID."', payment_gross='".$paidAmount."',currency_code='".$paidCurrency."',payment_status='".$payment_status."',active=1 where email='".$email."'");
  }else{
      $statusMsg = "Your Payment has Failed!";
    }
  }else{
    //print '<pre>';print_r($chargeJson);
    $statusMsg = "Transaction has been failed!";
  }
}else{
  $statusMsg = "Error on form submission.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Stripe Payment</title>
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
      <a class="navbar-brand" href="#">STRIPE PAYMENT PAGE</a>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">
    <div class="card border-0 shadow my-5">
      <div class="card-body p-5">
        <?php if($payment_status == 'succeeded'){ ?>

          <h3 class="font-weight-light text-center">Your payment has been successful.</h3>
        </h6>
      <?php }else{ ?>

        <h3 class="font-weight-light text-center">Sorry, Payment Failed!</h3>
        <h6 class="font-weight-light text-center">
          Please try again later.
        </h6>
      <?php } ?>

    </div>
  </div>
</div>
<script>

setTimeout(function() {
  window.location.href = 'http://localhost/screenwriting/index.php';
}, 5000);

</script>
<h6 class="font-weight-light text-center">

</body>
</html>

<?php
require_once 'session.php';
require 'callbacks/config.php'
require 'connect-php-sdk-master/vendor/autoload.php';

$access_token = ;
# setup authorization
\SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
# create an instance of the Transaction API class
$transactions_api = new \SquareConnect\Api\TransactionsApi();
$location_id = 'L1BFP543Y2SV5';
$nonce = $_POST['nonce'];

$request_body = array (
    "card_nonce" => $nonce,
    # Monetary amounts are specified in the smallest unit of the applicable currency.
    # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
    "amount_money" => array (
        "amount" => (int) $_POST['amount'],
        "currency" => "USD"
    ),
    # Every payment you process with the SDK must have a unique idempotency key.
    # If you're unsure whether a particular payment succeeded, you can reattempt
    # it with the same idempotency key without worrying about double charging
    # the buyer.
    "idempotency_key" => uniqid()
);

try {
    $result = $transactions_api->charge($location_id,  $request_body);
    // print_r($result);

	// echo '';
	if($result['transaction']['id']){

    $ordStatus = 'success';
    $statusMsg = 'Your Payment has been Successful!';
    $transactionID = $result['transaction']['id'];
    $paidAmount = (int) $_POST['amount'];
    $email = $_SESSION['email'];
    $update_user = mysqli_query($con,"UPDATE users SET txn_id='".$transactionID."', payment_gross='".$paidAmount."',currency_code='USD',payment_status='success',active=1 where email='".$email."'");
		//echo 'Payment success!';
		//echo "Transation ID: ".$result['transaction']['id']."";
	}
} catch (\SquareConnect\ApiException $e) {

      $ordStatus = 'failue';
      $statusMsg = "Your Payment has Failed!";

    //echo "Exception when calling TransactionApi->charge:";
    //var_dump($e->getResponseBody());
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
        <?php if($ordStatus == 'success'){ ?>

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

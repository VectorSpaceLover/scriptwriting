<?php

header("Access-Control-Allow-Origin: *");

$servername = "localhost";
// $username = "capsicu1_freelan";
// $password = "Freel@ncer@2021";
//$password = "MysqlDBA12#";
// $db='capsicu1_webscript';

$username = 'root';
$password = '';
$db = 'screenwriting';

global $con;
// Create connection
$con = mysqli_connect($servername, $username, $password,$db);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


/*
 * PayPal and database Configuration
 */

// PayPal configuration
//define('PAYPAL_ID', 'this2that46@gmail.com'); //Business Email
//define('PAYPAL_SANDBOX', FALSE); //TRUE or FALSE

// define('PAYPAL_ID', 'uniqwebstudio-facilitator@gmail.com'); //Business Email
// define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE


// define('PAYPAL_RETURN_URL', 'http://localhost/success.php');
// define('PAYPAL_CANCEL_URL', 'http://localhost/cancel.php');
// define('PAYPAL_NOTIFY_URL', 'http://localhost/ipn.php');
// define('PAYPAL_CURRENCY', 'USD');

// // PayPal API configuration
// define('PAYPAL_API_USERNAME', 'sb-2wivw7344904_api1.business.example.com');
// define('PAYPAL_API_PASSWORD', 'MSK4D3RT89PXNM8S');
// define('PAYPAL_API_SIGNATURE', 'A4BIxyAxlhrbtKPzpFsixQfZfQNsA-3ooPD33sFu8H.yO-oqrup-vKC5');
// define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE


// // Change not required
// define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");

// Stripe API configuration
//define('STRIPE_API_KEY', 'sk_live_lavh7JkyXHPh5HG9SWr4OsLg00A5XZQjyP');
//define('STRIPE_PUBLISHABLE_KEY', 'pk_live_DIbs7DAuvX013VTzWOtcRf4C00GZamWFVT');

define('STRIPE_API_KEY', 'sk_test_zOWcn9gx5HuRhMkkg307w1Mc00iuOxMQZ8');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_rbrirq1tzDKkOKf3D0fWIpMI');

define('SQAURE_APPLICAION_ID', 'sandbox-sq0idb-unXxP-SRD_OflgF3T9A6rQ');
define('SQUARE_ACCESS_KEY', 'EAAAEBD1fLmBmTzYPTKLgnAMNSu6yI1ZdzvFVJmkRTuo3sF4Qs6bafCNPB78vLub');
define('SQAURE_LOCATION_ID','L1BFP543Y2SV5');
?>

<?php require_once 'callbacks/config.php'; ?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<?php include_once('header.php'); ?>

<body class="h-100">
  <div class="authincation h-100">
    <div class="container-fluid h-100">
      <div class="row justify-content-center h-100 align-items-center">
        <div class="col-md-12">
          <div class="authincation-content">
            <div class="row no-gutters">
              <div class="col-xl-12">
                <div class="auth-form">
                  <h4 class="text-center mb-4">Payment</h4>



                  <div class="row">
                    <div class="col-xl-8">
                      <div class="row">
                        <div class="col-xl-3">
                          <div class="nav flex-column nav-pills nav-justified">
                            <a href="#v-pills-stripe" data-toggle="pill" class="nav-link show active">STRIPE</a>
                            <a href="#v-pills-square" data-toggle="pill" class="nav-link">SQUARE</a>
                            <a href="#v-pills-paypal" data-toggle="pill" class="nav-link">PAYPAL</a>

                          </div>
                        </div>
                        <div class="col-xl-9">
                          <div class="tab-content">
                            <div id="v-pills-stripe" class="tab-pane fade active show">


                              <!-- Display errors returned by createToken -->
                              <div id="paymentResponse"></div>

                              <!-- Payment form -->
                              <form action="stripe_payment.php" method="POST" id="paymentFrm">
                                <div class="form-group">
                                  <label>NAME</label>
                                  <input type="text" name="name" id="name" class="form-control field form-control"  required="">
                                </div>
                                <div class="form-group">
                                  <label>EMAIL</label>
                                  <input type="email" name="email" id="email" class="form-control field"  value="<?php echo $_SESSION['email']?>" required="">
                                </div>
                                <div class="form-group">
                                  <label>CARD NUMBER</label>
                                  <div id="card_number" class="form-control field"></div>
                                </div>


                                <div class="form-group">
                                  <label>EXPIRY DATE</label>
                                  <div id="card_expiry" class="form-control field">

                                  </div>
                                </div>


                                <div class="form-group">
                                  <label>CVC CODE</label>
                                  <div id="card_cvc" class="form-control field"></div>

                                </div>
                              <input type="hidden" value="15" class="amount" name="amount"/>
                              <button type="submit" class="btn btn-primary" id="payBtn">Submit Payment</button>
                            </form>



                          </div>
                          <div id="v-pills-square" class="tab-pane fade">


                            <div id="form-container">
                              <div id="sq-ccbox">
                                <!--
                                Be sure to replace the action attribute of the form with the path of
                                the Transaction API charge endpoint URL you want to POST the nonce to
                                (for example, "/process-card")
                              -->
                              <form id="nonce-form" novalidate action="square_payment.php" method="post">

                                <div class="form-group">
                                  <label>Card Number</label>
                                  <input type="text" name="sq-card-number" id="sq-card-number" class="form-control field form-control"  required="">
                                </div>

                                <div class="form-group">
                                  <label>Expiration</label>
                                  <input type="text" name="sq-expiration-date" id="sq-expiration-date" class="form-control field form-control"  required="" >
                                </div>

                                <div class="form-group">
                                  <label>CVV</label>
                                  <input type="text" name="sq-cvv" id="sq-cvv" class="form-control field form-control"  required="" >
                                </div>

                                <div class="form-group">
                                  <label>Postal</label>
                                  <input type="text" name="sq-postal-code" id="sq-postal-code" class="form-control field form-control"  required="" >
                                </div>


                                <input type="hidden" class="amount" id="amount" name="amount" value="15">
                                <input type="hidden" id="card-nonce" name="nonce">

                                <button type="submit" class="button-credit-card btn btn-primary" onclick="requestCardNonce(event)" id="sq-creditcard">Submit Payment</button>

                                <div id="error"></div>


                            </form>
                          </div> <!-- end #sq-ccbox -->

                        </div> <!-- end #form-container -->



                      </div>


                      <div id="v-pills-paypal" class="tab-pane fade">

                      <form method="POST" id="paymentForm">

                          <div class="form-group">
                            <label>Name on the card</label>
                            <input type="text" id="pp_name_on_card" name="name_on_card"class="form-control field form-control"  required="">
                          </div>

                          <div class="form-group">
                            <label>CARD NUMBER</label>
                            <input type="text" type="text" placeholder="1234 5678 9012 3456" maxlength="20" id="pp_card_number" name="card_number" class="form-control field form-control"  required="">
                          </div>


                          <div class="form-group">
                            <label>EXPIRY MONTH</label>
                            <input type="text" placeholder="MM" maxlength="5" id="pp_expiry_month" name="expiry_month" class="form-control field form-control"  required="" >
                          </div>

                          <div class="form-group">
                            <label>EXPIRY YEAR</label>
                            <input type="text" placeholder="YYYY" maxlength="5" id="pp_expiry_year" name="expiry_year" class="form-control field" required="">
                          </div>


                          <div class="form-group">
                            <label>CVC CODE</label>
                            <input type="text" placeholder="123" maxlength="3" id="pp_cvv" name="cvv" class="form-control">
                          </div>

                        <input type="hidden" value="15" class="amount" name="amount"/>
                        <input type="hidden" name="card_type" id="card_type" value=""/>
                        <button type="submit" class="btn btn-primary payment-btn" name="card_submit" id="pp_cardSubmitBtn" value="Proceed" disabled="true">Submit Payment</button>
                      </form>















                      </div>

                    </div>
                  </div>
                </div>




              </div>





                <div class="col-xl-4">


                  <div class="form-group"> <label>Have coupon?</label>
                    <div class="input-group">
                      <input type="text" class="form-control coupon_code" name="" placeholder="Coupon code">
                      <span class="input-group-append">
                        <button class="btn btn-primary btn-apply coupon">Apply</button>
                      </span>
                    </div>
                  </div>


                  <div class="card">

                    <div class="card-body text-center">
                      <h4 class="card-title ">Payment Info</h4>
                      <p class="card-text">
                        <h1><b>$<span class="coupon_price">15</span> </b></h1>
                        <ul class="mt-3 mb-4">
                          <li>10 users included</li>
                          <li>2 GB of storage</li>
                          <li>Email support</li>
                          <li>Help center access</li>
                        </ul>

                      </p>
                    </div>
                  </div>

                </div>





              </div>




            </div>




          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<?php include_once('scripts.php'); ?>

<script src="https://js.stripe.com/v3/"></script>
<!-- link to the SqPaymentForm library -->
<script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>

<!-- link to the local SqPaymentForm initialization -->
<script type="text/javascript" src="js/sqpaymentform.js"></script>
<script type="text/javascript" src="js/creditCardValidator.js"></script>


<script>



/* Credit card validation code */
function cardFormValidate(){
    var cardValid = 0;

    // Card number validation
    $('#pp_card_number').validateCreditCard(function(result) {
        var cardType = (result.card_type == null)?'':result.card_type.name;
        if(cardType == 'Visa'){
            var backPosition = result.valid?'2px -163px, 260px -87px':'2px -163px, 260px -61px';
        }else if(cardType == 'MasterCard'){
            var backPosition = result.valid?'2px -247px, 260px -87px':'2px -247px, 260px -61px';
        }else if(cardType == 'Maestro'){
            var backPosition = result.valid?'2px -289px, 260px -87px':'2px -289px, 260px -61px';
        }else if(cardType == 'Discover'){
            var backPosition = result.valid?'2px -331px, 260px -87px':'2px -331px, 260px -61px';
        }else if(cardType == 'Amex'){
            var backPosition = result.valid?'2px -121px, 260px -87px':'2px -121px, 260px -61px';
        }else{
            var backPosition = result.valid?'2px -121px, 260px -87px':'2px -121px, 260px -61px';
        }
        $('#pp_card_number').css("background-position", backPosition);
        if(result.valid){
            $("#pp_card_type").val(cardType);
            $("#pp_card_number").removeClass('required');
            cardValid = 1;
        }else{
            $("#pp_card_type").val('');
            $("#pp_card_number").addClass('required');
            cardValid = 0;
        }
    });

    // Card details validation
    var cardName = $("#pp_name_on_card").val();
    var expMonth = $("#pp_expiry_month").val();
    var expYear = $("#pp_expiry_year").val();
    var cvv = $("#pp_cvv").val();
    var regName = /^[a-z ,.'-]+$/i;
    var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
    var regYear = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
    var regCVV = /^[0-9]{3,3}$/;
    if(cardValid == 0){
        $("#pp_card_number").addClass('required');
        $("#pp_card_number").focus();
        return false;
    }else if(!regMonth.test(expMonth)){
        $("#pp_card_number").removeClass('required');
        $("#pp_expiry_month").addClass('required');
        $("#pp_expiry_month").focus();
        return false;
    }else if(!regYear.test(expYear)){
        $("#pp_card_number").removeClass('required');
        $("#pp_expiry_month").removeClass('required');
        $("#pp_expiry_year").addClass('required');
        $("#pp_expiry_year").focus();
        return false;
    }else if(!regCVV.test(cvv)){
        $("#pp_card_number").removeClass('required');
        $("#pp_expiry_month").removeClass('required');
        $("#pp_expiry_year").removeClass('required');
        $("#pp_cvv").addClass('required');
        $("#pp_cvv").focus();
        return false;
    }else if(!regName.test(cardName)){
        $("#pp_card_number").removeClass('required');
        $("#pp_expiry_month").removeClass('required');
        $("#pp_expiry_year").removeClass('required');
        $("#pp_cvv").removeClass('required');
        $("#pp_name_on_card").addClass('required');
        $("#pp_name_on_card").focus();
        return false;
    }else{
        $("#pp_card_number").removeClass('required');
        $("#pp_expiry_month").removeClass('required');
        $("#pp_expiry_year").removeClass('required');
        $("#pp_cvv").removeClass('required');
        $("#pp_name_on_card").removeClass('required');
        $('#pp_cardSubmitBtn').prop('disabled', false);
        return true;
    }
}



// Create an instance of the Stripe object
// Set your publishable API key
var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

console.log(stripe);

// Create an instance of elements
var elements = stripe.elements();

var style = {
  base: {
    fontWeight: 400,
    fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
    fontSize: '16px',
    lineHeight: '1.4',
    color: '#555',
    backgroundColor: '#fff',
    '::placeholder': {
      color: '#888',
    },
  },
  invalid: {
    color: '#eb1c26',
  }
};

var cardElement = elements.create('cardNumber', {
  style: style
});
cardElement.mount('#card_number');

var exp = elements.create('cardExpiry', {
  'style': style
});
exp.mount('#card_expiry');

var cvc = elements.create('cardCvc', {
  'style': style
});
cvc.mount('#card_cvc');

// Validate input of the card elements
var resultContainer = document.getElementById('paymentResponse');
cardElement.addEventListener('change', function(event) {
  if (event.error) {
    resultContainer.innerHTML = '<p>'+event.error.message+'</p>';
  } else {
    resultContainer.innerHTML = '';
  }
});

// Get payment form element
var form = document.getElementById('paymentFrm');

// Create a token when the form is submitted.
form.addEventListener('submit', function(e) {
  e.preventDefault();
  createToken();
});

// Create single-use token to charge the user
function createToken() {
  stripe.createToken(cardElement).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error
      resultContainer.innerHTML = '<p>'+result.error.message+'</p>';
    } else {
      // Send the token to your server
      stripeTokenHandler(result.token);
    }
  });
}

// Callback to handle the response from stripe
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}



document.addEventListener("DOMContentLoaded", function(event) {
  if (SqPaymentForm.isSupportedBrowser()) {
    paymentForm.build();

  }
});


</script>

</body>

</html>

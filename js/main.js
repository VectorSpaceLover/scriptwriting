$(document).ready(function(){

  $('.privacy-pages>p>a').each(function(){
    console.log(this);
    $(this).click(function(){
      var pageId = $(this).attr('page-id');
      console.log(pageId);
      $.get('callbacks/pages.php?function=getPageContent&pageId=' + pageId,
      function(response, status){
        if(response.success) swal(response.data.page_content, '', 'info');
      })
    })
  })

  $('.btn-apply').on('click',function(){
    var coupon_code = $('.coupon_code').val();
    var coupon_price = $('.amount').val();

    if(coupon_code != '') {

      $.ajax({
        type: "POST",
        url: "callbacks/scripts.php",
        data: {'coupon_code':coupon_code, 'function':'coupon'},
        success: function (data) {
          var result = isJSON(data) ? JSON.parse(data) : data;
          console.log(result.msg);
          if( result.msg == 'success') {

            var new_price = result.coupon_price - coupon_price;
            console.log(new_price);
            $('.coupon_price').text(new_price);
            $('.amount').val(new_price);

            if(new_price <= 0) {
              swal({
                title: "Thank you for purchase!",
                text: "",
                type: "success"
              }).then(function() {
                window.location = "dashboard.php";
              });

            }


          } else {
            swal(result.msg, "", "error")
          }
        }
      });

    } else {

      swal("Coupon code empty", "", "error")

    }

  });



  $(document).ready(function(){
    // Initiate validation on input fields
    $('#paymentForm input[type=text]').on('keyup',function(){
      cardFormValidate();
    });

    // Submit card form
    $("#cardSubmitBtn").on('click',function(){
      $('.status-msg').remove();
      if(cardFormValidate()){
        var formData = $('#paymentForm').serialize();
        $.ajax({
          type:'POST',
          url:'paypal_payment.php',
          dataType: "json",
          data:formData,
          beforeSend: function(){
            $("#cardSubmitBtn").prop('disabled', true);
            $("#cardSubmitBtn").val('Processing....');
          },
          success:function(data){
            if(data.status == 1){
              $('#paymentSection').html('<p class="status-msg success">The transaction was successful. Order ID: <span>'+data.orderID+'</span></p>');
            }else{
              $("#cardSubmitBtn").prop('disabled', false);
              $("#cardSubmitBtn").val('Proceed');
              $('#paymentSection').prepend('<p class="status-msg error">Transaction has been failed, please try again.</p>');
            }
          }
        });
      }
    });
  });

});


function isJSON(str) {
  console.log('JSON CHECK');
  try {
    console.log('YES JSON');
    console.log((JSON.parse(str) && !!str));
    return (JSON.parse(str) && !!str);

  } catch (e) {
    console.log('NO JSON');
    return false;
  }
}

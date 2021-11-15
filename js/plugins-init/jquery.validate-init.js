jQuery("#login-form").validate({
    rules: {
        "val-email": {
            required: !0,
            email: !0
        },
        "val-password": {
            required: !0,
            minlength: 5
        }
    },
    messages: {
        "val-email": "Please enter a valid email address",
        "val-password": {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
        }
    },

    ignore: [],
    errorClass: "invalid-feedback animated fadeInUp",
    errorElement: "div",
    errorPlacement: function(e, a) {
        jQuery(a).parents(".form-group > div").append(e)
    },
    highlight: function(e) {
        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")
    },
    success: function(e) {
        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()
    },

    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "callbacks/scripts.php",
            data: $(form).serialize(),
            success: function (data) {
              var result = isJSON(data) ? JSON.parse(data) : data;
              console.log(result.msg);
              if( result.msg == 'success') {
                window.location.href='dashboard.php';
              } else {
                swal(result.msg, "", "error")
              }
            }
        });
        return false; // required to block normal submit since you used ajax
    }
});

jQuery(".signup-form").validate({
    rules: {
        "val-firstname": {
            required: !0,
            minlength: 3
        },
        "val-lastname": {
            required: !0,
            minlength: 3
        },
        "val-email": {
            required: !0,
            email: !0
        },
        "val-password": {
            required: !0,
            minlength: 5
        },
        "val-confirm-password": {
            required: !0,
            equalTo: "#val-password"
        },
        "val-phoneus": {
            required: !0,
            minlength: 10
        },
        "val-terms": {
            required: !0
        }
    },
    messages: {
        "val-username": {
            required: "Please enter a username",
            minlength: "Your username must consist of at least 3 characters"
        },
        "val-email": "Please enter a valid email address",
        "val-password": {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
        },
        "val-confirm-password": {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long",
            equalTo: "Please enter the same password as above"
        },
        "val-phoneus": "Please enter a  phone!",
        "val-terms": "You must agree to the service terms!"
    },

    ignore: [],
    errorClass: "invalid-feedback animated fadeInUp",
    errorElement: "div",
    errorPlacement: function(e, a) {
        jQuery(a).parents(".form-group > div").append(e)
    },
    highlight: function(e) {
        jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")
    },
    success: function(e) {
        jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()
    },
    submitHandler: function (form) {
        console.log('submithandler');
        $.ajax({
            type: "POST",
            url: "callbacks/scripts.php",
            data: $(form).serialize(),
            success: function (data) {
                console.log(data);
                var result = isJSON(data) ? JSON.parse(data) : data;
                console.log(result.msg);
                if( result.msg == 'success') {
                    // window.location.href='payment.php';
                    var msg = "We've finished setting up your account." +
				            "We sent you a confirmation to your email account";
                    swal(msg, "", "success");
                } else {
                    // swal(result.msg, "", "error")
                    swal(result.description, "", "error");
                }
                // 
            }
        });
        return false; // required to block normal submit since you used ajax
    }
});

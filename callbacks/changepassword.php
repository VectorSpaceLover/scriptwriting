<?php


if (isset($_GET["id"])) {
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Profile - Wraparoundkids</title>
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="../css/bootstrap.min.css" rel="stylesheet" />
   <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
  <link rel="stylesheet" href="../css/font-awesome.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">

    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="../css/login.css" rel="stylesheet">
    <link href="../css/site.css" rel="stylesheet" />
     <script  src="../js/jquery.min.js"></script>
  <script   src="../js/bootstrap.min.js"></script>
    <!-- start Mixpanel -->
    


    <style>
        .actionMsgContainer
        {
            top: 15px;
        }
    </style>
     <script>
  
  $(document).ready(function()
		  {
			  
	  $('#error').hide();
	
		});

  function validate()
  {
	  
	  var msg='';
	  var error=true;
	  var password = document.getElementById("Password").value;
      var confirmPassword = document.getElementById("ConfirmPassword").value;
      if (password != confirmPassword) {
         msg='Password doesn\'t match';
         error=false;
      }
    
    
          if(password.length < 5 || password.length > 60)
          {
              msg+='Password must be in the length of 5 to 60 characters';
              error=false;
          }
      
      if(!error)
      {
          var p='<p class="text-danger text-center">'+msg+'</p>';
          $('#error').empty();
          $('#error').append(p);
          $('#error').show();
          return false;
      }
      else

          return true;
      
  }
 
  </script>
</head>
<body class='login_body'>
<div class="actionMsgContainer">
</div>

    <div class="wrap">
        

<h2>Profile</h2>
<h4></h4>


<style>
    .login_body .wrap
    {
        width: 450px;
        left: 48%;
    }
</style>


<form action="update_user_password.php" name='login' class="form-horizontal" method="post" onSubmit='return validate()'>
<div id='error'>

</div>
<input id="Code" name="Code" type="hidden" value="<?php echo $id;?>" /> 
   <div class="login">
       
      
        <div class="pw">
            <label for="Password">Password</label>
            <div class="pw-input">
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-lock"></i></span>
                        <input id="Password" name="password" type="password" required/>
                    </div>
                </div>
            </div>
        </div>
        <div class="pw">
            <label for="ConfirmPassword" style="width:130px">Confirm password</label>
            <div class="pw-input">
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-lock"></i></span>
                        <input id="ConfirmPassword" name='confirmpass' type="password" required/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="submit">
        <input type="submit" class="btn btn-blue4 btn-large" value="Save" />
    </div>
</form>

    </div>

    
</body>
</html>

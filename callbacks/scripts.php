<?php

session_start();
include 'config.php';
include 'functions.php';


//Login
if(isset($_POST['function']) && $_POST['function'] == 'login')
{

  $ans=array();
  $email=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-email'])));
  $password=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-password'])));
  $query=mysqli_query($con,"select * from users where active = 1 and email='$email' and password='".encrypt_decrypt($password,'encrypt')."'");
  $login=mysqli_num_rows($query);
  if($login!=0)
  {
    $row=mysqli_fetch_assoc($query);

    if($row['email_verify']==1)
    {

      $ans['msg']="success";
      $ans['id']=$row['id'];
      $ans['email']=$row['email'];
      $_SESSION['email']=$row['email'];
      $_SESSION['userid']=$row['id'];
      $_SESSION['usertype']=$row['user_type'];
      $ans['type']=$row['user_type'];

      if($row['picture'] !='' || !empty($row['picture']))
      {
        $ans['user_picture']='https://wraparoundkids.com.au/callbacks/php/uploads/'.$row['picture'];
      }
      else
      {
        $ans['user_picture']='https://wraparoundkids.com.au/callbacks/php/uploads/profile.jpg';

      }

      echo json_encode($ans);
      die();

    }
    else
    {
      $ans['msg']="Email Verification failed";
      echo json_encode($ans);
      die();
    }
  }
  else
  {
    $ans['msg']="You have entered an invalid email or password";
    echo json_encode($ans);
    die();
  }
}



if(isset($_POST['function']) && $_POST['function'] == "signup")
{
  $ans=array();

  $email=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-email'])));
  $password=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-password'])));
  $confirm_password=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-confirm-password'])));
  $firstname=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-firstname'])));
  $lastname=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-lastname'])));
  $address1=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-address2'])));
  $address2=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-address1'])));
  $city=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-city'])));
  $state=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-state'])));
  $zip=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-zip'])));
  $phone=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-phoneus'])));

  if(mysqli_query($con,"insert into users (firstname,lastname,email,password,address1,address2,city,state,zip,phone,active,email_verify)  values('$firstname','$lastname','$email','".encrypt_decrypt($password,'encrypt')."','$address1','$address2','$city','$state','$zip','$phone',0,0)"))
  {

    $id=mysqli_insert_id($con);
    $ans['msg']="success";
    $ans['id']=$id;
    $ans['type']=4;
    $ans['company']=$business_name;
    $_SESSION['userid']=$id;
    $_SESSION['email']=$email;
    $_SESSION['usertype']=$ans['type'];

    // send email Verification

    $code=base64_encode($id);

    $message = "";

    $message.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Demystifying Email Design</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
    <td style="padding: 10px 0 30px 0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
    <tr>
    <td align="center" style="background:linear-gradient(to right, rgb(84, 76, 249) 0%, rgb(204, 51, 255) 100%);
    padding: 30px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
    <h2 style="color: #ffffff; font-family: Lato;">Screenwriting</h2>
    </td>
    </tr>
    <tr>
    <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">

    <tr>
    <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">';

    $message.="Click On The Following Link to verify Your Account Password<br><br>
    </p><br> <a style='padding:2px;'
    target='_blank'  href='http://localhost.com/screenwriting/activate.php?email_verify=verify&code=".$code."'
    > Reset Password</a></br></br><p>Sample text about org.
    </p>
    </td>
    </tr>
    <tr>";

    $message.='<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
    <br><br>Regards,

    <p>Screenwriting Team</p>
    <p>You have received this email because you are a registered on screenwriting.</p>

    </td>
    </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td  style="background:linear-gradient(to right, rgb(84, 76, 249) 0%, rgb(204, 51, 255) 100%);padding: 30px 30px 30px 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
    <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
    &copy; 2018 Wraparoundkids
    </td>

    </tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </body>
    </html>';

    $subject = "Email Verification";

  //  send_mail($email,$message,$subject);


    echo json_encode($ans);
    die();
    //echo "success";
  }
  else
  {
    $ans['msg']="Something went wrong - please try again later!";
    echo json_encode($ans);
    die();
  }


}



if(isset($_POST['function']) && $_POST['function'] == 'coupon')
{

  $ans=array();
  $coupon_code=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['coupon_code'])));
  $query=mysqli_query($con,"select * from coupons where used = 0 and coupon_code='$coupon_code'");
  $code=mysqli_num_rows($query);
  if($code!=0)
  {
    $row=mysqli_fetch_assoc($query);

      $ans['msg']="success";
      $ans['id']=$row['id'];
      $ans['coupon_code']=$row['coupon_code'];
      $ans['coupon_price']=$row['coupon_price'];
      $update = mysqli_query($con,"UPDATE coupons set used=1 where coupon_code='$coupon_code'");
      echo json_encode($ans);
      die();
  }
  else
  {
    $ans['msg']="You have entered an invalid coupon";
    echo json_encode($ans);
    die();
  }
}





?>

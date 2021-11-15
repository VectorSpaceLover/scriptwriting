<?php
  session_start();
  include 'config.php';
  include 'functions.php';


//Login
if(isset($_POST['function']) && $_POST['function'] == 'login'){
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



if(isset($_POST['function']) && $_POST['function'] == "signup"){
  $ans=array();

  $email = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-email'])));
  $password = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-password'])));
  $confirm_password = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-confirm-password'])));
  $firstname = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-firstname'])));
  $lastname = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-lastname'])));
  $address1 = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-address2'])));
  $address2 = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-address1'])));
  $city = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-city'])));
  $state = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-state'])));
  $zip = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-zip'])));
  $phone = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['val-phoneus'])));

  // print_r($_POST);
  $sql = "insert into users (firstname,lastname,email,password,address1,address2,city,state,zip,phone,active,email_verify)  
  values('$firstname','$lastname','$email','".encrypt_decrypt($password,'encrypt') . 
  "','$address1','$address2','$city','$state','$zip','$phone',0,0)";

  // echo "<br/>";
  // echo $query;
  // echo "<br/>";
  $message = '';
  $ans = array('msg' => '', 'id' => '', 'type' => '', 'description' => '');

  if(mysqli_query($con, $sql)){

    $id=mysqli_insert_id($con);
    $ans['msg'] = "failed";
    $ans['id'] = $id;
    $ans['type'] = 4;
    // $ans['company']=$business_name;
    $_SESSION['userid'] = $id;
    $_SESSION['email'] = $email;
    $_SESSION['usertype'] = $ans['type'];

    $message .= 'User Account created.';
    // send email Verification

    $code = base64_encode($id);
    
    $message .= "verification code is " . $code ." \n";
    
    $subject = "Email Verification";

    // send_mail("sashkamaslo@mail.ru","Email Verify","sdfsdfsdf");
    //SEND MESSAGE
    // require_once('email_verify.php');
    $link = 'http://' . $_SERVER['SERVER_NAME'] . "/scriptwriting/callbacks/email_verified.php?email=".$email."&code=".$code;
		$email_message = "Hello $firstname $lastname! <br>"
        . "Please click the link below to confirm your email and complete the registration process.<br>"
        . "You will be automatically redirected to a welcome page where you can then sign in.<br><br>"            
        . "Please click below to activate your account:<br>"
        . "<a href='$link'>Click Here!</a>";

    $sql = "SELECT * from email_information WHERE user_id = 1";
    $result = $con->query($sql);

    $admin_email = '';
    $admin_email_password = '';

    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      $admin_email = $row['email'];
      $admin_password = $row['password'];
    }
    // echo "<br/>" . $admin_email . '<br/>' . $admin_password . '<br/>';
    // echo $email_message;
    // echo $_SERVER['SERVER_NAME'] . "<br/>";
    // echo $_SERVER['REQUEST_URI'] . "<br/>";
    $send_email_res = send_email_verify_code($email, $admin_email, $admin_email_password, $email_message);
    if($send_email_res['success']){
      $ans['msg'] = 'success';
    }
      
    $message .= $send_email_res['message'] . '\n';

    if(mysqli_query($con, "update users set verification_code = '".$code."' where id = '".$id."'"))
      $message .= "update email verification code succeed.\n";
    else 
      $message .= "update email verification code failed.\n";

    // echo json_encode($ans);
    // die();
    //echo "success";
  } else {
    $message .= "Insert New User data failed - please try again later!\n";
    // echo json_encode($ans);
    // die();
  }
  $ans['description'] = $message;
  echo json_encode($ans);
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

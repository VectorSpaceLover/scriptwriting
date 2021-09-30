<?php
session_start();
include_once 'config.php';
include_once 'functions.php';
require 'class.phpmailer.php';
require 'PHPMailerAutoload.php';
define('GUSER', 'team@procedurerock.com'); // GMail username
define('GPWD', 'Itsarembrandt6'); // GMail password



if(isset($_POST['function']) && $_POST['function']== 'new_user')
{

  //user login creation
  $email=$_POST['email'];
  $password=encrypt_decrypt('screenwriting','encrypt');
  $message='';

  $login_query=mysqli_query($con,"insert into users(firstname,lastname,email,password,phone,user_type) values('".$_POST['first-name']."','".$_POST['lastname']."','".$email."', '".$password."', '".$_POST['phone']."','".$_POST['usertype']."')");
  $id=mysqli_insert_id($con);

  if($id && $id > 0)
  {
    $code=base64_encode($id);


      $subject='EMAIL VERIFICATION';

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
      <h2 style="color: #ffffff; font-family: Lato;">Screen writing</h2>
      </td>
      </tr>
      <tr>
      <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
      <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
      <b>HELLO "'.$_POST['first-name'].'"</b>
      </td>
      </tr>
      <tr>
      <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">';
      $message.="<p><a href='http://localhost/screenwriting/callbacks/activate.php?code=".$code."'>CLICK TO ACTIVATE YOUR ACCOUNT</a></p><br>";
      $message.="<p>Your Default Password is <b>screenwriting</b></p>";
      $message.="<p>Please change your password once you logged in.</p>";
      $message.='</td>
      </tr>
      <tr>
      <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
      <br><br>Regards,

      <p>Screen writing Team</p>
      <p>You have received this email because you are a user of screenwriting.</p>

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
      &copy; 2018 Screen writing
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

      if(send_mail($email,$message,$subject))
      echo '1';
      else
      echo '1';
      die();
  }
  else
  {
    echo '2';
    die();

  }

}


if(isset($_POST['function']) && $_POST['function']== 'deleteuser')
{

  $id=$_POST['id'];

  if(mysqli_query($con ,"delete from users where id=".$id))
  {
    echo '1';
    die();
  }
  else
  {
    echo '0';
    die();
  }

}


if(isset($_POST['function']) && $_POST['function']== 'setuserid')
{

  $_SESSION['edit_user_id']=$_POST['id'];
  echo '1';die();

}

if(isset($_POST['function']) && $_POST['function']== 'edituserdata')
{
  $result=array();
  $id=$_POST['userid'];
  $sql=mysqli_query($con,"select * from users where id=".$id);

  $data=mysqli_fetch_assoc($sql);
  $result=array();
  if($data['id']!=null){
    $result['id']=$data['id'];
    $result['first_name'] = $data['firstname'];
    $result['last_name'] = $data['lastname'];
    $result['email'] = $data['email'];
    $result['phone'] = $data['phone'];
    $result['type']=$data['user_type'];
  }
  else
  $result['id']=$_SESSION['edit_user_id'];

  echo json_encode($result);
  die();

}

if(isset($_POST['function']) && $_POST['function']== 'updateuser')
{

  $id=$_POST['userid'];

  $query="update users set firstname= '".$_POST['first-name']."', lastname='".$_POST['last-name']."',
  email='".$_POST['email']."', phone='".$_POST['phone']."',  user_type='".$_POST['usertype']."'
  where id=".$id;

  $ans=mysqli_query($con,$query);

  if($ans)
  {
    echo '1';
  }
  else
  echo '0';
  die();
}


if(isset($_POST['function']) && $_POST['function']== 'update_settings')
{

  $id=$_POST['userid'];

  $query="update users set payment_charge= '".$_POST['user-charge']."'where id=".$id;

  $ans=mysqli_query($con,$query);

  if($ans)
  {
    echo '1';
  }
  else
  echo '0';
  die();
}





if(isset($_POST['function']) && $_POST['function']== 'update_profile')
{

  $id=$_POST['userid'];

  $query="update users set firstname= '".$_POST['first-name']."', lastname='".$_POST['last-name']."',
  email='".$_POST['email']."', phone='".$_POST['phone']."',  password='".encrypt_decrypt($_POST['password'],'encrypt')."'
  where id=".$id;

  $ans=mysqli_query($con,$query);

  if($ans)
  {
    echo '1';
  }
  else
  echo '0';
  die();
}

if(isset($_POST['function']) && $_POST['function']== 'newgroup')
{

  //user login creation
  $name=$_POST['groupname'];
  $users=implode(",",$_POST['selected_users']);
  $id=$_POST['userid'];
  $query="insert into groups(name,users,created_on,created_by)
  values('".$name."', ".$_POST['selected_users'][0].", NOW(), ".$id.")";

  //print_r($query);
  $ans=mysqli_query($con,$query);

  if($ans)
  echo '1';
  else
  echo '0';
  die();

}

if(isset($_POST['function']) && $_POST['function']== 'deletegroup')
{

  $id=$_POST['id'];

  if(mysqli_query($con ,"delete from groups where id=".$id))
  {

    echo '1';
    die();
  }
  else
  {
    echo '0';
    die();
  }

}


if(isset($_POST['function']) && $_POST['function']== 'setgroupid')
{

  $_SESSION['edit_group_id']=$_POST['id'];
  echo '1';die();

}


if(isset($_POST['function']) && $_POST['function']== 'updategroup')
{

  //user login creation
  $id=$_POST['group_id'];
  $name=$_POST['groupname'];
  $users=implode(",",$_POST['selected_users']);

  $query="update groups set name= '".$name."', users='".$users."' where id=".$id;


  $ans=mysqli_query($con,$query);

  if($ans)
  echo '1';
  else
  echo '0';
  die();

}


if(isset($_POST['function']) && $_POST['function']== 'editgroupdata')
{
  $result=array();
  $id=$_POST['groupid'];
  $sql=mysqli_query($con,"select * from groups where id=".$id);

  $data=mysqli_fetch_assoc($sql);
  $result=array();
  if($data['id']!=null){
    $result['id']=$data['id'];
    $result['name'] = $data['name'];
    $result['users'] = $data['users'];

  }
  else
  $result['id']=$id;

  echo json_encode($result);
  die();

}



if(isset($_POST['function']) && $_POST['function']== 'unsubscribe')
{

  $id=$_POST['userid'];
  $query="update login set active = 0  where id=".$id;
  $ans=mysqli_query($con,$query);

  if($ans)
  {

    echo '1';
  }
  else
  echo '0';
  die();


}



if(isset($_POST['function']) && $_POST['function']== 'userPicture')
{

  $id=$_POST['userid'];

  $sub=mysqli_query($con,"select * from userprofile where user_id=".$id);

  $res=mysqli_fetch_assoc($sub);

  if($res['picture'] !='' || !empty($res['picture']))
  {
    echo 'https://procedurerock.com/callbacks/php/uploads/'.$res['picture'];
    die();
  }
  else
  {
    echo 'https://procedurerock.com/callbacks/php/uploads/profile.jpg';
    die();
  }

}



if(isset($_POST['function']) && $_POST['function'] == 'getUsers') {

$user_query = '';
$html = '';
$usertype = $_POST['usertype'];

$user_query = "select * from users";

$user_result = mysqli_query($con,$user_query);

if(mysqli_num_rows($user_result) > 0) {
  $html.= '<tr>';
  while($row = mysqli_fetch_assoc($user_result)) {

    $html.='<td>'.$row['id'].'</td>';
    $html.='<td>'.$row['firstname'].' '.$row['lastname'].'.</td>';
    $html.='<td>'.$row['email'].'</td>';

    if($row['email_verify'] == 1)
  		$html.='<td><span class="text-success">Email Verified</span></td>';
  	else
  		$html.='<td><span class="text-danger">Not Verified</span></td>';

    $html.='<td>'.$row['active'].'</td>';
    $html.='<td>'.$row['user_type'].'</td>';

    if($usertype ==1){
    $html.='<td><div style="display:flex"><button class="btn btn-primary edit_user" id="'.$row['id'].'"><i class="fa fa-edit"></i></button>';
    $html.=' &nbsp;<button class="btn btn-danger del_user" id="'.$row['id'].'"><i class="fa fa-trash"></i></button></div></td>';
    }
      else if($_POST['usertype']==1){
    $html.='<td><div style="display:flex"><button class="btn btn-primary edit_user" id="'.$row['id'].'"><i class="fa fa-edit"></i></button>';
    $html.=' &nbsp;<button class="btn btn-danger del_user" id="'.$row['id'].'"><i class="fa fa-trash"></i></button></div></td>';
    }

    $html.= '</tr>';

  }

  echo $html;

  die();

}
else
{
  echo json_encode($result);
  die();
}


}

?>

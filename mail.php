<?php
session_start();
include_once 'config.php';
require 'class.phpmailer.php';
require 'PHPMailerAutoload.php';
define('GUSER', 'team@procedurerock.com'); // GMail username
define('GPWD', 'Itsarembrandt6'); // GMail password
$file=fopen('t.txt','w');

fwrite($file,print_r('Invite Single Users',true)."\n");


if(isset($_POST['emails']))
{

  $res=array();
  $html='';
  $nomail=0;
  $exits=array();
  $company=$_POST['company'];
  $sql=mysqli_query($con,"select email from userprofile");

  while($row=mysqli_fetch_assoc($sql))
  {
    array_push($exits,$row['email']);
  }
  fwrite($file,print_r($exits,true)."\n");

  $subject='Invitation';

  $emails=$_POST['emails'];
  $usertype=$_POST['usertype'];

  fwrite($file,print_r($usertype,true)."\n");
  //for($i=0;$i<count($emails)-1;$i++)
  foreach($emails as $k => $email)
  {

    $type=$usertype[$k];
    //$email=$emails[$i];
    //user login creation
    $password=md5('procedurerock');
    if(!empty($email))
    {

      if(!in_array($email,$exits)){

        mysqli_query($con,"insert into login(email,password,business_name) values('".$email."', '".$password."', '".$_POST['company']."')");
        $id=mysqli_insert_id($con);
        $query="insert into userprofile(email,user_type,user_id)
        values('".$email."','".$type."','".$id."')";
        if(mysqli_query($con,$query))
        $code=base64_encode($id);


        $message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Email Design</title>
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
        <h2 style="color: #ffffff; font-family: Lato;">PROCEDUREROCK</h2>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
        <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
        <b>HELLO "'.$email.'"</b>
        </td>
        </tr>
        <tr>
        <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">';
        $message.="<p>You have been invited to ".$company."  by a team member or fellow user of our cloud based software</p>";
        $message.="<p>We are using Procedurerock  to manage procedures,processes,policies etc and make collaborations across the company.</p>";
        $message.='<p>We need your input to facilitate this, <br>Click our link here to accept the invitation and Default password is <b>procedurerock</b><br/><br/><br/>
        <a style="padding:10px;background-color:green;color:white;" href="https://procedurerock.com/callbacks/php/activate.php?company='.$company.'&code='.$code.'"> Accept Invitation</a></p>
        </td>
        </tr>
        <tr>
        <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
        <br><br>Regards,

        <p>ProcedureRock Team</p>
        <p>You have received this email because you are a user of ProcedureRock.</p>

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
        &copy; 2018 ProcedureRock
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

        if(send_mail($email,$message,$subject) )
        {
          $flag=true;
        }

      }
      else{
        $nomail=1;
        $flag=true;
        if(!empty($html)){
          $html.=",".$email;
        }
        else{

          $html.=$email;
        }


      }

    }

  }



  if($flag)
  {
    $res['msg']=1;
    $res['nomail']=$nomail;
    $res['mails']=$html;
    echo json_encode($res);
    die();
  }
  else
  {
    $res['msg']=0;
    echo json_encode($res);
    die();
  }



}



function send_mail($email,$message,$subject)
{

  $from="team@procedurerock.com";
  $from_name="ProcedureRock Team";
  //$cc="";
  $to=$email;

  $mail = new PHPMailer();  // create a new object
  $mail->IsSMTP(); // enable SMTP
  $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
  $mail->SMTPAuth = true;  // authentication enabled
  $mail->Host = 'ssl://smtp.gmail.com:465';
  $mail->Username = GUSER;
  $mail->Password = GPWD;
  $mail->SetFrom($from, $from_name);

  $mail->Subject = $subject;

  $mail->Body=$message;
  $mail->IsHTML();
  $mail->AddAddress($to);

  // $mail->AddCC($cc);
  $mail->Send();

  return true;
}
?>

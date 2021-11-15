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


                $message = "Hi ".$email."<br>";
                $message.="<br><p>You have been invited to ".$_POST['company']."  by a team member or fellow user of our cloud based software</p>";
                $message.="<br><p>We are using Procedurerock  to manage procedures,processes,policies etc and make collaborations across the company<br><br>
	                       </p><p>We need your input to facilitate this,
	                       <br>Click our link here to accept the invitation and Default password is <h2><b>procedurerock</b><h2> <a style='padding:10px;background-color:green;color:white;'
                            target='_blank'  href='https://wraparoundkids.com.au/callbacks/php/activate.php?company=".$_POST['company']."&code=".$code."'
	                       > Accept Invitation</a><br>";
                $message.="<br><br>Regards,
                            <p>ProcedureRock Team</p>
                            <p>Team@ProcedureRock.com.au</p>
                            <p>You have received this email because somebody has entered your email as a request to join</p>
                            <p>Please disregard if received in error .</p>
                            <p> ©2018 ProcedureRock, Inc</p>";

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

    // $from="team@procedurerock.com";
    // $from_name="ProcedureRock Team";
    // //$cc="";
    // $to=$email;   

    // $mail = new PHPMailer();  // create a new object
    // $mail->IsSMTP(); // enable SMTP
    // $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    // $mail->SMTPAuth = true;  // authentication enabled
    // $mail->Host = 'ssl://smtp.gmail.com:465';
    // $mail->Username = GUSER;  
    // $mail->Password = GPWD;           
    // $mail->SetFrom($from, $from_name);

    // $mail->Subject = $subject;

    // $mail->Body=$message;
    // $mail->IsHTML();
    // $mail->AddAddress($to);

    // // $mail->AddCC($cc);
    // $mail->Send();

    // return true;
    $from = "Sandra Sender <sender@example.com>";
    $to = "sashkamaslo@mail.ru";
    $subject = "Hi!";
    $body = "Hi,\n\nHow are you?";

    $host = "smtp.gmail.com";
    $port = "587";
    $username = "venus9023gold@mail.ru";
    $password = "venus199023";

    $headers = array ('From' => $from,
      'To' => $to,
      'Subject' => $subject);
    $smtp = Mail::factory('smtp',
      array ('host' => $host,
        'port' => $port,
        'auth' => true,
        'username' => $username,
        'password' => $password));

    $mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
      echo("<p>" . $mail->getMessage() . "</p>");
    } else {
      echo("<p>Message successfully sent!</p>");
    }
}
?>

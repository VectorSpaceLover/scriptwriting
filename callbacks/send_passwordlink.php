<?php 

include_once 'config.php';
require 'class.phpmailer.php';
require 'PHPMailerAutoload.php';
define('GUSER', 'team@procedurerock.com'); // GMail username
define('GPWD','Itsarembrandt6'); // GMail password

if(isset($_POST['email']))
{
    $email=$_POST['email'];
    $sql=mysqli_query($con,"select id from login where email='".$email."' ");
    $row=mysqli_fetch_assoc($sql);
    $code=base64_encode($row['id']);

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
              <h2 style="color: #ffffff; font-family: Lato;">Wrap Around Kids</h2>
						</td>
					</tr>
					<tr>
						<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">

								<tr>
									<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">';

    $message.="Click On The Following Link to Reset Your Account Password<br><br>
	                   </p><br> <a style='padding:2px;'
                                    target='_blank'  href='https://Wraparoundkids.com.au/dashboard/change_password.php?code=".$code."'
	 > Reset Password</a><br><p>Wraparoundkids is a smart, cloud-based Operations and Procedures Management Software that systemizes your business operations. It makes it easy for you to document and manage your procedures, processes, manuals, guidelines and much much more. 
							</p>
									</td>
								</tr>
							<tr>";

    $message.='<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
									<br><br>Regards,

 <p>Wraparoundkids Team</p>
 <p>You have received this email because you are a user of Wraparoundkids.</p>

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


    $subject='Reset Password';


    if(send_mail($email,$message,$subject) )
        $flag=true;
    else 
        $flag=0;


    echo json_encode($flag);
    die();




}



function send_mail($email,$message,$subject)
{

    $from="team@wraparoundkids.com.au";
    $from_name="team@wraparoundkids.com.au";
    //$cc="";
    $to=$email;   

    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->Host = 'ssl://smtp.gmail.com:465';
    $mail->Username = 'team@procedurerock.com';  
    $mail->Password = 'Itsarembrandt6';           
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

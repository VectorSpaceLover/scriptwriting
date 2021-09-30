<?php 

error_reporting( error_reporting() & ~E_NOTICE );
if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}
if(!isset($_SESSION))
{
   if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    if(session_id() == '') {session_start();}
} else  {
   if (session_status() == PHP_SESSION_NONE) {session_start();}
}   
}

$current_page=basename($_SERVER['PHP_SELF']);

if(empty($_SESSION) && $current_page != 'index.php' && $current_page!='signup.php' && $current_page!='signup_backup.php' && $current_page !='forgot_password.php')
{
    header('Location: index.php');
    die();
}



?>
<?php

include_once 'config.php';

if(isset($_POST['Code']))
{

    $id = $_POST["Code"];
    $company=$_POST["company"];	 
    $name=$_POST['name'];
    $username=$_POST['username'];
    $pass=md5($_POST['password']);

    mysqli_query($con,"update login set password='".$pass."' , access_code='created_user',name='".$name."',active = 1 ,business_name='".$company."' where id=".$id."");
    $sql=" UPDATE userprofile SET  username='".$username."', name='".$name."'  WHERE user_id =".$id."";
    //echo $sql;die();
    if(mysqli_query($con,$sql))
    {
        header("Location:https://wraparoundkids.com.au/wak/index.php");
    }


}

?>

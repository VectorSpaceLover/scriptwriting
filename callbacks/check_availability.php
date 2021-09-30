<?php

include_once 'config.php';
session_start();

if(!empty($_POST["email"])) {
  $result = mysqli_query($con,"SELECT count(*) FROM login WHERE email='" . $_POST["email"] . "'");
  $row = mysqli_fetch_row($result);
  $user_count = $row[0];
  if($user_count>0) {
      echo json_encode('false');
  }else{
      echo  json_encode('true');
  }
}


if(!empty($_POST["business_name"])) {
  $result = mysqli_query($con,"SELECT count(*) FROM login WHERE business_name='" . $_POST["business_name"] . "'");
  $row = mysqli_fetch_row($result);
  $user_count = $row[0];
  if($user_count>0) {
      echo  json_encode('false');
  }else{
      echo  json_encode('true');
  }
}

?>
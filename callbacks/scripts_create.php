<?php include '../session.php'; ?>

<?php
  session_start();
  include_once 'config.php';
  include_once 'functions.php';
  
  // echo $_POST['title'];
  var_dump($_POST);
  exit;


  if(isset($_POST['function']) && $_POST['function'] == 'saveScripts') 
  {
    $html = '';
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userid = $_SESSION['userid'];
    $script_query = "INSERT INTO scripts(user_id,content) VALUES('".$userid."','".$content."')";
    $script_result = mysqli_query($con,$script_query);
  }
?>

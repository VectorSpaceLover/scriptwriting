<?php include '../session.php'; ?>

<?php
  session_start();
  include_once '../callbacks/config.php';
  include_once '../callbacks/functions.php';
  

  if(isset($_POST['function']) && $_POST['function'] == 'saveScripts') 
  {
    $html = '';
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userid = $_SESSION['userid'];
    $scriptId = $_POST['scriptId'];
    
    $saveQuery = "INSERT INTO scripts(user_id,contents, title, scriptid) VALUES('".$userid."','".$content."','".$title."','".$scriptId."')";
    $searchQuery = "select * from scripts where scriptid = '".$scriptId."'";
    $script_result = mysqli_query($con,$searchQuery);
    if(mysqli_num_rows($script_result) > 0) 
    {
      $updateQuery="update scripts set contents = '".$_POST['content']."', title = '".$_POST['title']."' where scriptid = '".$scriptId."'";
      $ans=mysqli_query($con,$updateQuery);
      if($ans)
        echo 1;
      else
        echo 0;
    }
    else
    {
        if ($con->query($saveQuery) === TRUE)
          echo 1;
        else
          echo 0;
    }
  }

  if(isset($_GET['scriptId']) && $_GET['function'] == 'getScript') 
  {
    $html = '';
    $scriptId = $_GET['scriptId'];
    $script_query = "select * from scripts where scriptid = '".$scriptId."'";
    $script_result = mysqli_query($con,$script_query);
     if($script_result && mysqli_num_rows($script_result) > 0) {
      $row = mysqli_fetch_assoc($script_result);
      header('Content-Type: application/json');
      echo json_encode($row);
      exit;
    } else {
      header('Content-Type: application/json');
      echo json_encode('error');
    }
    // echo $script_result;
  }

  if(isset($_POST['scriptId']) && $_POST['function'] == 'updateScripts') 
  {
    // $id=$_POST['scriptId'];

    // echo $id;
    $query="update scripts set contents = '".$_POST['content']."' where scriptid = " . $_POST['scriptId'];
    // var_dump($query);
    // echo $query;
    $ans=mysqli_query($con,$query);

    if($ans)
      echo 1;
    else
      echo 0;
  }
?>

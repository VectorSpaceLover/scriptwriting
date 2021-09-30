<?php
  session_start();
  include_once 'config.php';
  include_once 'functions.php';

  if(isset($_POST['function']) && $_POST['function'] == 'getScripts') 
  {

    $html = '';
    $userid = $_POST['userid'];
    $script_query = "select * from scripts where user_id = ".$userid;

    $script_result = mysqli_query($con,$script_query);

    if(mysqli_num_rows($script_result) > 0) {
      
      $html.= '<div class="row">';
      while($row = mysqli_fetch_assoc($script_result)) {
        // $html.= '<a class = " col-lg-3 col-md-6 col-sm-12" href="'."script/#/".$row['id'].'" target = "_blank" id="'.$row['id'].'">';
        // $html.= '<div class="card col-md-12" style = "height: 100px; display:inline-block">';
        //           // .'<div class="card-left-bar col-md-3"></div>';
        // $html.=   '<div class="card-body col-md-9">';
        // $html.=     '<h5 class="card-title">'.$row['title'].'</h5>';
        // $html.=     '<p class="card-text text-right"> '.$row['modified'].'</p>';
        // $html.=   '</div>';
        // $html.= '</div>';
        // $html.= '</a>';
        $html .= '<a href= "script/#/'.$row['scriptid'].'" target = "_blank" class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card flex-row">
                      <div class = "card-sidebar">1.0</div>
                      <div class="stat-widget-two card-body flex-column">
                        <div class="stat-content">'.
                          
                          '<div class="stat-digit"><span class="text-dark" id = "">'.$row['title'].'</span></div>'.
                           //<div class="stat-text">'. $row['modified'].'</div>
                        '</div>
                        <div class="progress">
                          <div class="progress-bar progress-bar-success w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </a>';
      }
      $html.= '</div>';
      echo $html;
      die();

    } else {
      // echo json_encode($result);
      die();
    }
  }
?>

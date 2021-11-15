<?php include 'session.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php include_once('header.php'); ?>


<body>

  <?php include_once('preloader.php'); ?>


  <!--**********************************
  Main wrapper start
  ***********************************-->
  <div id="main-wrapper">


    <?php

    include_once('nav_header.php');
    include_once('page_header.php');
    include_once('left_navigation.php');

    ?>


    <!--**********************************
    Content body start
    ***********************************-->
    <div class="content-body">
      <!-- row -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <h4 class="pt-3 ml-3">Scripts</h4>
              
              <a href = 'script' target = '_blank' class="mr-3 btn btn-secondary float-right new-script-button">New Script</a>
            </div>
            <div class="card-body">
              <div class = "container scripts_detail"></div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
    <!--**********************************
    Content body end
    ***********************************-->


    <?php include_once('footer.php'); ?>

    <!--**********************************
    Support ticket button start
    ***********************************-->

    <!--**********************************
    Support ticket button end
    ***********************************-->


  </div>
  <!--**********************************
  Main wrapper end
  ***********************************-->

  <?php  include_once('scripts.php'); ?>
  <script src=" https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

  <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>

  <!-- <script type="text/javascript" src="js/scripts.js"></script> -->


  <script>


  $(document).ready(function() {

    $.ajax({
      type: "POST",
      url: "callbacks/scripts_detail.php",
      data: {'userid':<?php echo $_SESSION['userid']?>, 'function':'getScripts'},
      success: function (data) {
        var result = isJSON(data) ? JSON.parse(data) : data;
        console.log(result);
        $('.scripts_detail').html(result);
      }
    });
  });
  </script>

</body>

</html>

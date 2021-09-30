<?php
include 'session.php';
include 'callbacks/config.php';
?>
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
                <h4 class="pt-3 ml-3">Coupons</h4>

              </div>
              <div class="card-body">



                <!-- Display status message -->
                <?php if(!empty($statusMsg)){ ?>
                  <div class="col-xs-12">
                    <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
                  </div>
                <?php } ?>





                <div class="row">
                  <!-- Import link -->
                  <div class="col-md-12 head">
                    <div class="float-right">
                      <a href="javascript:void(0);" class="btn btn-danger mb-3 mt-3 mr-3" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
                    </div>
                  </div>
                  <!-- CSV file upload form -->
                  <div class="col-md-12 mb-3 mt-3" id="importFrm" style="display: none;">
                    <form action="callbacks/importData.php" method="post" enctype="multipart/form-data">
                      <input type="file" name="file" />
                      <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
                    </form>
                  </div>

                </div>


                <!-- Data list table -->
                <table class="table table-striped table-bordered">
                  <thead class="thead-dark">
                    <tr>
                      <th>#ID</th>
                      <th>code</th>
                      <th>price</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Get member rows
                    $result = $con->query("SELECT * FROM coupons ORDER BY id DESC");
                    if($result->num_rows > 0){
                      while($row = $result->fetch_assoc()){
                        ?>
                        <tr>
                          <td><?php echo $row['id']; ?></td>
                          <td><?php echo $row['coupon_code']; ?></td>
                          <td><?php echo $row['coupon_price']; ?></td>
                          <td><?php echo $row['used']; ?></td>

                        </tr>
                      <?php } }else{ ?>
                        <tr><td colspan="5">No coupons(s) found...</td></tr>
                      <?php } ?>
                    </tbody>
                  </table>


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


    <script>


    function formToggle(ID){
      var element = document.getElementById(ID);
      if(element.style.display === "none"){
        element.style.display = "block";
      }else{
        element.style.display = "none";
      }
    }

    $(document).ready(function() {





    });
    </script>


  </body>

  </html>

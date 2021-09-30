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
                <h4 class="pt-3 ml-3">Settings</h4>

              </div>
              <div class="card-body">


                <form class="form-horizontal"  method='post' id='update_settings_form'>
                  <input type='hidden' id='userid' name='userid' value='<?php echo $_SESSION['userid'];?>'>
                  <input type='hidden' id='function' name='function' value='update_settings'>

                <div class="form-group">
                    <label class="control-label col-sm-2" >Charge Money </label>
                    <div class="col-sm-10">
                      <div class="radio">
                        <label><input type="radio" class='user_charge' name="user-charge" value='1' required> On</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" class='user_charge'  name="user-charge" value='0' required> Off</label>
                      </div>

                    </div>
                  </div>


                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="update_settings"><i class='fa fa-save'></i> Save</button>
                    </div>
                  </div>
                </form>
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

  <script type="text/javascript" src="js/users.js"></script>


</body>

</html>

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
                <h4 class="pt-3 ml-3">New User</h4>

              </div>
              <div class="card-body">


                <form class="form-horizontal"  method='post' id='new_user_form'>
                  <input type='hidden' id='function' name='function' value='new_user'>

                  <div class="form-group">
                    <label class="control-label col-sm-2" >First Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="first-name"  name="first-name" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-sm-2" >Last Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="last-name"  name="last-name" required>
                    </div>
                  </div>


                  <div class="form-group">
                    <label class="control-label col-sm-2" >Email </label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="email"  name="email" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-sm-2" >Phone </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="phone"  name="phone" >
                    </div>
                  </div>


                  <div class="form-group">
                    <label class="control-label col-sm-2" >User Type </label>
                    <div class="col-sm-10">
                      <div class="radio">
                        <label><input type="radio" class='usertype' name="usertype" value='1' required> Administrator (Full control)</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" class='usertype'  name="usertype" value='2' required> Author (Create, Modify, View, Monitor, Execute)</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" class='usertype' name="usertype" value='3' required> Power User (View, Execute. Monitor)</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" class='usertype' name="usertype" value='4' required> User (View, Execute)</label>
                      </div>
                    </div>
                  </div>


                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="new_user"><i class='fa fa-save'></i> Save</button>
                      <button type="button" class="btn btn-danger" id='reset_user'><i class='fa fa-remove'></i> Cancel</button>
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

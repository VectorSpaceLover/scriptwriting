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
                <h4 class="pt-3 ml-3">Profile</h4>

              </div>
              <div class="card-body">


                <form class="form-horizontal"  method='post' id='update_user_profile'>
                  <input type='hidden' id='function' name='function' value='update_profile'>

                  <input type='hidden' id='userid' name='userid' value=''>

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
                      <input type="email" class="form-control" id="email" readonly  name="email" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-sm-2" >Phone </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="phone"  name="phone" required>
                    </div>
                  </div>


                  <div class="form-group">
                    <label class="control-label col-sm-2" >Password </label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="password"  name="password" required>
                    </div>
                  </div>



                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary" id="update_profile"><i class='fa fa-save'></i> Save</button>
                      <button type="button" class="btn btn-danger" id='reset_profile'><i class='fa fa-remove'></i> Cancel</button>
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

  <script>

  $(document).ready(function() {


    $.ajax({
      type: "POST",
      url:"callbacks/users.php",
      data:{'function':'edituserdata','userid':'<?php echo $_SESSION['userid']; ?>'},
      success:function(data)
      {
        var obj=JSON.parse(data);
        $('#userid').val(obj.id);
        $('#first-name').val(obj.first_name);
        $('#last-name').val(obj.last_name);
        $('#email').val(obj.email);
        $('#phone').val(obj.phone);

      }

    });


  });
  </script>

</body>

</html>

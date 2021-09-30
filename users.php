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
              <h4 class="pt-3 ml-3">Users</h4>
              <button class="mr-3 btn btn-secondary float-right new-user-button">New User</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class='table table-bordered table-hover table-striped' id='users_table'>
                  <thead>
                    <tr>
                      <th>ID</th><th>Name</th><th>Email</th><th>Email Status</th><th>User status</th><th>User Type</th><th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="users_table_body">
                  </tbody>
                </table>
              </div>
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

  <script type="text/javascript" src="js/users.js"></script>


  <script>


  $(document).ready(function() {

    $.ajax({
      type: "POST",
      url: "callbacks/users.php",
      data: {'usertype':<?php echo $_SESSION['usertype']?>, 'function':'getUsers'},
      success: function (data) {
        var result = isJSON(data) ? JSON.parse(data) : data;
        console.log(result);
        $('.users_table_body').html(result);
        $('#users_table').DataTable();
      }
    });
  });
  </script>

</body>

</html>

<?php 

require_once 'session.php'; 
include('callbacks/config.php');

?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<?php include_once('header.php'); ?>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form id="login-form" method="post">
                                        <div class="form-group">
                                          <div class="form-fields">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control" id="val-email" name="val-email" placeholder="">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="form-fields">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" id="val-password" name="val-password">
                                          </div>
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="form-check ml-2">
                                                    <!-- <input class="form-check-input" type="checkbox" id="basic_checkbox_1"> -->
                                                    <!-- <label class="form-check-label" for="basic_checkbox_1">Remember me</label> -->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="forgot-password.php">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <input type="hidden" name="function" value="login"/>
                                            <button type="submit" class="btn btn-primary btn-block">Sign me in</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Don't have an account? <a class="text-primary" href="signup.php">Sign up</a></p>
                                    </div>
                                    <div class = 'privacy-pages'>
                                        <p style = 'text-align: center; margin-top: 30px'>
                                        <?php 
                                        $sql = "SELECT * from pages";                                        
                                        $result = $con->query($sql);
                                        $flag = false;

                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                echo (($flag) ? " | " : "") . '<a href = "#" page-id = "' . $row['id'] . '"> ' . $row['page_title'] . ' </a>';
                                                if(!$flag) $flag = true;
                                            }
                                        }
                                        ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php include_once('scripts.php'); ?>

</body>

</html>

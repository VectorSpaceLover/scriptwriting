<?php require_once 'session.php'; ?>

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
                                    <h4 class="text-center mb-4">Sign up your account</h4>
                                    <form class="signup-form"  method="post">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-firstname">First Name
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="val-firstname" name="val-firstname" >
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-lastname">Last Name
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="val-lastname" name="val-lastname">
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-email">Email <span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="val-email" name="val-email" >
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-password">Password
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <input type="password" class="form-control" id="val-password" name="val-password">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-confirm-password">Confirm Password <span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <input type="password" class="form-control" id="val-confirm-password" name="val-confirm-password" >
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-suggestions">Address </label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="val-address1" name="val-address1" placeholder="Address1">
                                                        <input type="text" class="form-control mt-2" id="val-address2" name="val-address2" placeholder="Address1">
                                                        <input type="text" class="form-control mt-2" id="val-city" name="val-city" placeholder="City">
                                                        <input type="text" class="form-control mt-2" id="val-state" name="val-state" placeholder="State">
                                                        <input type="text" class="form-control mt-2" id="val-zip" name="val-zip" placeholder="Zip">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-phoneus">Phone
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="val-phoneus" name="val-phoneus">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"><a
                                                            href="javascript:void()">Terms &amp; Conditions</a> <span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <label class="css-control css-control-primary css-checkbox" for="val-terms">
                                                            <input type="checkbox" class="css-control-input mr-2"
                                                                id="val-terms" name="val-terms" value="1">
                                                            <span class="css-control-indicator"></span> I agree to the
                                                            terms</label>
                                                    </div>
                                                </div>

                                                <div class="text-center mt-4">
                                                    <input type="hidden" name="function" value="signup"/>
                                                    <button type="submit" class="btn btn-primary btn-block">Sign me up</button>
                                                </div>

                                            </div>

                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Already have an account? <a class="text-primary" href="./page_logout.php">Sign in</a></p>
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

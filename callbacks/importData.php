<?php
// Load the database configuration file
include_once 'config.php';

if(isset($_POST['importSubmit'])){

    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $code   = $line[0];
                $price  = $line[1];

                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT id FROM coupons WHERE code = '".$line[1]."'";
                $prevResult = $con->query($prevQuery);

                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $con->query("UPDATE coupons SET coupon_code = '".$code."', coupon_price = '".$price."', used = 1 WHERE coupon_code = '".$code."'");
                }else{
                    // Insert member data in the database
                    $con->query("INSERT INTO coupons (coupon_code, coupon_price, used) VALUES ('".$code."', '".$price."', 1)");
                }
            }

            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: ../coupons.php".$qstring);

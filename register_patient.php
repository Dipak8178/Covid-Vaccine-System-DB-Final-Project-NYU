<?php

// Include config file
require_once "config.php";
include('header.php');

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["type"]) && $_SESSION["type"] === "patient"){
        header("location: patient.php");
        exit;
    }
    if (isset($_SESSION["type"]) && $_SESSION["type"] === "provider"){
        header("location: provider.php");
        exit;
    }
}

// Define variables and initialize with empty values
$patientEmail =  $patientPassword = $confirm_password = $patientName = $ssn = $dob = $patientAddress = $patientPhone = $distancePreference = "";
$patientEmail_err = $password_err = $confirm_password_err = $patientName_err = $ssn_err = $dob_err = $patientAddress_err = $patientPhone_err = $distancePreference_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $err = false;

    $patientName = trim($_POST["patientName"]);
    if (empty($patientName)) {
        $patientName_err = "Please enter a full name.";
        $err = true;
    }
    $ssn = trim($_POST["ssn"]);
    if (empty($ssn)) {
        $ssn_err = "Please enter a SSN.";
        $err = true;
    }
    $dob = trim($_POST["dob"]);
    if (empty($dob)) {
        $dob_err = "Please enter a Date of Birth.";
        $err = true;
    }
    $patientAddress = trim($_POST["patientAddress"]);
    if (empty($patientAddress)) {
        $patientAddress_err = "Please enter a Address.";
        $err = true;
    }
    $patientPhone = trim($_POST["patientPhone"]);
    if (empty($patientPhone)) {
        $patientPhone_err = "Please enter a Phone Number.";
        $err = true;
    }
    $distancePreference = trim($_POST["distancePreference"]);
    if (empty($distancePreference)) {
        $distancePreference_err = "Please enter a Distance Preference.";
        $err = true;
    }
    $patientEmail = trim($_POST["patientEmail"]);
    if (empty($patientEmail)) {
        $patientEmail_err = "Please enter an Email.";
        $err = true;
    }

    // Validate password
    if (empty(trim($_POST["patientPassword"]))) {
        $password_err = "Please enter a password.";
        $err = true;
    } elseif (strlen(trim($_POST["patientPassword"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
        $err = true;
    } else {
        $patientPassword = trim($_POST["patientPassword"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
        $err = true;
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($patientPassword != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
            $err = true;
        }
    }

    // } added before but it is not worked
    if (!$err) {
        // Prepare a select statement
        $sql = "SELECT patientId FROM Patient WHERE patientEmail = ?";

        $num_row = -1;

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $patientEmail);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $num_row = mysqli_stmt_num_rows($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $patientEmail_err = "This Email is already taken.";
                    $err = true;
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        if ($num_row == 0) {

            // Get Longitude and Latitude from patientAddress using Google Maps API
            $api_key = GOOGLE_API_KEY;
            $address = str_replace(" ", "+", $patientAddress);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=$api_key");
            $json = json_decode($json);
            $patientLatitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $patientLongitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};


            // Prepare an insert statement
            $sql = "INSERT INTO Patient (patientName, ssn, dob, patientAddress, patientLatitude, patientLongitude,
                                        patientPhone, distancePreference, patientEmail, patientPassword
                                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param(
                    $stmt,
                    "sissiiiiss",
                    $param_patientName,
                    $param_ssn,
                    $param_dob,
                    $param_patientAddress,
                    $param_patientLatitude,
                    $param_patientLongitude,
                    $param_patientPhone,
                    $param_distancePreference,
                    $param_patientEmail,
                    $param_patientPassword
                );
                // Set parameters
                $param_patientName = $patientName;
                $param_ssn = $ssn;
                $param_dob = $dob;
                $param_patientAddress = $patientAddress;
                $param_patientLatitude = $patientLatitude;
                $param_patientLongitude = $patientLongitude;
                $param_patientPhone = $patientPhone;
                $param_distancePreference = $distancePreference;
                $param_patientEmail = $patientEmail;
                $param_patientPassword = password_hash($patientPassword, PASSWORD_DEFAULT); // Creates a password hash

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to login page
                    echo "Login Succesfull";
                    header("location: index.php");
                } else {
                    echo "Oops! Something went wrong. Please try again later. after validate success";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        // Close connection
        mysqli_close($link);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <div class="container" style="margin-top: 2rem;">
        <div class="row">
            <aside class="col-sm-9" style="margin: auto">
                <div class="card" style="align-items: center;">
                    <h2 class="card-title text-center mb-4 mt-4">
                        Patient Registration
                    </h2>
                    <p>Please fill this form to create an account.</p>

                    <article class="card-body" style="width:90%">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="patientEmail" placeholder="Enter email address for log in" class="form-control <?php echo (!empty($patientEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientEmail; ?>">
                                <span class="invalid-feedback"><?php echo $patientEmail_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="patientPassword" placeholder="Enter password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientPassword; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" placeholder="Re-enter password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="patientName" placeholder="Enter full name" class="form-control <?php echo (!empty($patientName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientName; ?>">
                                <span class="invalid-feedback"><?php echo $patientName_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>SSN</label>
                                <input type="text" placeholder="Enter number only" pattern="\d{9}" name="ssn" class="form-control <?php echo (!empty($ssn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ssn; ?>">
                                <span class="invalid-feedback"><?php echo $ssn_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" class="form-control <?php echo (!empty($dob_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dob; ?>">
                                <span class="invalid-feedback"><?php echo $dob_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="patientAddress" placeholder="Enter full address" class="form-control <?php echo (!empty($patientAddress_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientAddress; ?>">
                                <span class="invalid-feedback"><?php echo $patientAddress_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" pattern="\d{10}" name="patientPhone" placeholder="Enter number only without country code" class="form-control <?php echo (!empty($patientPhone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientPhone; ?>">
                                <span class="invalid-feedback"><?php echo $patientPhone_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Distance Preference</label>
                                <input type="text" pattern="\d{1,2}" placeholder="Enter maximum distance you are willing to travel to get vaccinated (1-99 miles)" name="distancePreference" class="form-control <?php echo (!empty($distancePreference_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $distancePreference; ?>">
                                <span class="invalid-feedback"><?php echo $distancePreference_err; ?></span>
                            </div>

                            <div class="form-group" style="display: flex;justify-content: center;">
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                            </div>
                            <div style="display: flex;justify-content: center;">

                            <p>Already have an account? <a href="index.php">Login here</a>.</p>
                            </div>
                        </form>
                </div>
            </aside>
        </div>
    </div>
    <?php
    include('footer.php');
    ?>

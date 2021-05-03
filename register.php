<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username =  $confirm_password = $patientName = $patientPassword = $ssn = $dob =
$patientAddress = $patientLatitude = $patientLongitude = $patientPhone = $distancePreference = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["patientName"]))){
        $username_err = "Please enter a first name.";
    }
    else if(empty(trim($_POST["ssn"]))){
        $username_err = "Please enter a ssn.";
    }
    else if(empty(trim($_POST["dob"]))){
        $username_err = "Please enter a dob.";
    }
    else if(empty(trim($_POST["patientAddress"]))){
        $username_err = "Please enter a address.";
    }
    else if(empty(trim($_POST["patientLatitude"]))){
        $username_err = "Please enter a Latitude.";
    }
    else if(empty(trim($_POST["patientLongitude"]))){
        $username_err = "Please enter a Longitude.";
    }
    else if(empty(trim($_POST["patientPhone"]))){
        $username_err = "Please enter a phone_number.";
    }
    else if(empty(trim($_POST["distancePreference"]))){
        $username_err = "Please enter a distancePreference.";
    }
    else{
        $patientName = trim($_POST["patientName"]);
        // $first_name = trim($_POST["first_name"]);
        // $surname = trim($_POST["surname"]);
        $ssn = trim($_POST["ssn"]);
        $dob = trim($_POST["dob"]);
        $patientAddress = trim($_POST["patientAddress"]);
        $patientLatitude = trim($_POST["patientLatitude"]);
        $patientLongitude = trim($_POST["patientLongitude"]);
        $patientPhone = trim($_POST["patientPhone"]);
        $distancePreference = trim($_POST["distancePreference"]);
       $username = trim($_POST["patientEmail"]);
       $patientPassword = trim($_POST["patientPassword"]);
    }

    // Validate username
    if(empty(trim($_POST["patientEmail"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT patientId FROM patient WHERE patientEmail = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["patientEmail"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["patientEmail"]);
                    //echo "Your username is: $username\n\n\n\n\n";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["patientPassword"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["patientPassword"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $patientPassword = trim($_POST["patientPassword"]);
    }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($patientPassword != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        echo "No errors before inserting into database\n\n\n\n\n";
        // Prepare an insert statement
        $sql = "INSERT INTO patient (patientName, ssn, dob, patientAddress, patientLatitude, patientLongitude,
                                    patientPhone, distancePreference, patientEmail, patientPassword
                                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sissiiiiss", $param_patientName,
                                    $param_ssn, $param_dob, $param_patientAddress, $param_patientLatitude,
                                    $param_patientLongitude, $param_patientPhone, $param_distancePreference,
                                    $param_patientEmail, $param_patientPassword);

            // Set parameters
            $param_patientName = $patientName;
            $param_ssn = $ssn;
            $param_dob = $dob;
            $param_patientAddress = $patientAddress;
            $param_patientLatitude = $patientLatitude;
            $param_patientLongitude = $patientLongitude;
            $param_patientPhone = $patientPhone;
            $param_distancePreference = $distancePreference;
            $param_patientEmail = $username;
            $param_patientPassword = password_hash($patientPassword, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                echo "Login Succesfull";
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later. after validate success";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="email" name="patientEmail" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="patientPassword" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientPassword; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>patientName</label>
                <input type="text" name="patientName" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>ssn</label>
                <input type="number" name="ssn" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>paientAddress</label>
                <input type="text" name="patientAddress" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>patient Latitude</label>
                <input type="number" name="patientLatitude" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>patient Longitude</label>
                <input type="number" name="patientLongitude" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>phone number</label>
                <input type="number" name="patientPhone" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>distance preference</label>
                <input type="number" name="distancePreference" class="form-control">
                <span class="invalid-feedback"></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
    <Body background="register.png"> <br>

</body>
</html>

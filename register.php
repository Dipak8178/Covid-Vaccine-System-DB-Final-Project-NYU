<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username =  $confirm_password = $providerName = $providerPassword =
$providerAddress = $providerLatitude = $providerLongitude = $providerPhone = $providerType = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["providerName"]))){
        $username_err = "Please enter a full name.";
    }
    else if(empty(trim($_POST["providerAddress"]))){
        $username_err = "Please enter a address.";
    }
    else if(empty(trim($_POST["providerLatitude"]))){
        $username_err = "Please enter a Latitude.";
    }
    else if(empty(trim($_POST["providerLongitude"]))){
        $username_err = "Please enter a Longitude.";
    }
    else if(empty(trim($_POST["providerPhone"]))){
        $username_err = "Please enter a phone_number.";
    }
    else if(empty(trim($_POST["providerType"]))){
        $username_err = "Please enter a providerType.";
    }
    else{
        $providerName = trim($_POST["providerName"]);
        $providerAddress = trim($_POST["providerAddress"]);
        $providerLatitude = trim($_POST["providerLatitude"]);
        $providerLongitude = trim($_POST["providerLongitude"]);
        $providerPhone = trim($_POST["providerPhone"]);
        $providerType = trim($_POST["providerType"]);
       $username = trim($_POST["providerEmail"]);
       $providerPassword = trim($_POST["providerPassword"]);
    }

    // Validate username
    if(empty(trim($_POST["providerEmail"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT providerId FROM provider WHERE providerEmail = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["providerEmail"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["providerEmail"]);
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
    if(empty(trim($_POST["providerPassword"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["providerPassword"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $providerPassword = trim($_POST["providerPassword"]);
    }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($providerPassword != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        echo "No errors before inserting into database\n\n\n\n\n";
        // Prepare an insert statement
        $sql = "INSERT INTO provider (providerName, providerAddress, providerLatitude, providerLongitude,
                                    providerPhone, providerType, providerEmail, providerPassword
                                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiissss", $param_providerName,
                                    $param_providerAddress, $param_providerLatitude,
                                    $param_providerLongitude, $param_providerPhone, $param_providerType,
                                    $param_providerEmail, $param_providerPassword);

            // Set parameters
            $param_providerName = $providerName;
            $param_providerAddress = $providerAddress;
            $param_providerLatitude = $providerLatitude;
            $param_providerLongitude = $providerLongitude;
            $param_providerPhone = $providerPhone;
            $param_providerType = $providerType;
            $param_providerEmail = $username;
            $param_providerPassword = password_hash($providerPassword, PASSWORD_DEFAULT); // Creates a password hash

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
                <input type="email" name="providerEmail" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="providerPassword" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerPassword; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Provider Name</label>
                <input type="text" name="providerName" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>Provider Address</label>
                <input type="text" name="providerAddress" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>Provider Latitude</label>
                <input type="number" name="providerLatitude" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>Provider Longitude</label>
                <input type="number" name="providerLongitude" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="number" name="providerPhone" class="form-control">
                <span class="invalid-feedback"></span>
            </div>
            <div class="form-group">
                <label>Provider Type</label>
                <input type="text" name="providerType" class="form-control">
                <span class="invalid-feedback"></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>

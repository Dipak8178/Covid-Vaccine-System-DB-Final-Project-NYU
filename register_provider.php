<?php
// Include config file
require_once "config.php";
include('header.php');

// Define variables and initialize with empty values
$providerEmail_err = $password_err = $confirm_password_err = $providerAddress_err = $providerPhone_err = $providerName_err = $providerType_err = "";
$providerEmail = $providerPassword = $confirm_password = $providerAddress = $providerPhone = $providerName = $providerType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $err = false;

    //validate providerName
    $providerName = trim($_POST["providerName"]);
    if (empty($providerName)) {
        $providerName_err = "Please enter a full name.";
        $err = true;
    }
    //validate providerAddress
    $providerAddress = trim($_POST["providerAddress"]);
    if (empty($providerAddress)) {
        $providerAddress_err = "Please enter a address.";
        $err = true;
    }

    //validate providerPhone
    $providerPhone = trim($_POST["providerPhone"]);
    if (empty($providerPhone)) {
        $providerPhone_err = "Please enter a phone_number.";
        $err = true;
    }

    //validate providerType
    $providerType = trim($_POST["providerType"]);
    if (empty($providerType)) {
        $providerType_err = "Please enter a providerType.";
        $err = true;
    }

    // Validate providerEmail
    $providerEmail =  trim($_POST["providerEmail"]);
    if (empty($providerEmail)) {
        $providerEmail_err = "Please enter an Email.";
        $err = true;
    }

    // Validate password
    if (empty(trim($_POST["providerPassword"]))) {
        $password_err = "Please enter a password.";
        $err = true;
    } elseif (strlen(trim($_POST["providerPassword"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
        $err = true;
    } else {
        $providerPassword = trim($_POST["providerPassword"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
        $err = true;
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($providerPassword != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
            $err = true;
        }
    }

    if ($err == false) {
        // Prepare a select statement to check for exiting provider with same email
        $sql = "SELECT providerId FROM provider WHERE providerEmail = ?";
        $num_row = -1;

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $providerEmail);
            // Set parameters
            $param_providerEmail = trim($_POST["providerEmail"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                $num_row = mysqli_stmt_num_rows($stmt);

                if ($num_row  == 1) {
                    $providerEmail_err = "This Email is already taken.";
                    $err = true;
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
        if ($num_row == 0) {
            // Get Longitude and Latitude from providerAddress using Google Maps API
            $address = str_replace(" ", "+", $providerAddress);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=AIzaSyA3mM2cTa1pPBc73_wsR2YEkpEb-W45b8k");
            $json = json_decode($json);
            $providerLatitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $providerLongitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};


            // Prepare an insert statement
            $sql = "INSERT INTO Provider (providerName, providerAddress, providerLatitude, providerLongitude,
                                    providerPhone, providerType, providerEmail, providerPassword
                                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param(
                    $stmt,
                    "ssiissss",
                    $param_providerName,
                    $param_providerAddress,
                    $param_providerLatitude,
                    $param_providerLongitude,
                    $param_providerPhone,
                    $param_providerType,
                    $param_providerEmail,
                    $param_providerPassword
                );

                // Set parameters
                $param_providerName = $providerName;
                $param_providerAddress = $providerAddress;
                $param_providerLatitude = $providerLatitude;
                $param_providerLongitude = $providerLongitude;
                $param_providerPhone = $providerPhone;
                $param_providerType = $providerType;
                $param_providerEmail = $providerEmail;
                $param_providerPassword = password_hash($providerPassword, PASSWORD_DEFAULT); // Creates a password hash
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

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
            align-self: center;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <aside class="col-sm-8" style="margin: auto">
                <div class="card">
                    <article class="card-body">
                        <h4 class="card-title text-center mb-4 mt-1">
                            Provider Registration Page
                        </h4>
                        </hr>
                        <p>Please fill this form to create an account.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" id="providerEmail" name="providerEmail" class="form-control <?php echo (!empty($providerEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerEmail; ?>">
                                <span class="invalid-feedback"><?php echo $providerEmail_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" id="providerPassword" name="providerPassword" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerPassword; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Provider Name</label>
                                <input type="text" id="providerName" name="providerName" class="form-control <?php echo (!empty($providerName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerName; ?>">
                                <span class="invalid-feedback"><?php echo $providerName_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Provider Address</label>
                                <input type="text" id="providerAddress" name="providerAddress" class="form-control <?php echo (!empty($providerAddress_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerAddress; ?>">
                                <span class="invalid-feedback"><?php echo $providerAddress_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" id="providerPhone" name="providerPhone" class="form-control <?php echo (!empty($providerPhone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerPhone; ?>">
                                <span class="invalid-feedback"><?php echo $providerPhone_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Provider Type</label>
                                <input type="text" id="providerType" name="providerType" class="form-control <?php echo (!empty($providerType_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $providerType; ?>">
                                <span class="invalid-feedback"><?php echo $providerType_err; ?></span>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Submit" onclick="">
                                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                            </div>
                            <p>Already have an account? <a href="index.php">Log In</a>.</p>
                        </form>
                    </article>
                </div>
            </aside>
        </div>
    </div>
</body>



</html>
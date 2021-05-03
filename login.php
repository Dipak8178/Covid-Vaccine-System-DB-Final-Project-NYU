<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $providerPassword = "";
$username_err = $providerPassword_err = $login_err = $providerName = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["providerEmail"]))){
        $patientEmail_err = "Please enter username.";
    } else{
        $username = trim($_POST["providerEmail"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["providerPassword"]))){
        $providerPassword_err = "Please enter your password.";
    } else{
        $patientPassword = trim($_POST["providerPassword"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT providerId, providerEmail, providerName, providerPassword FROM provider WHERE providerEmail = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    echo "attempt to verify password";
                    mysqli_stmt_bind_result($stmt, $providerId, $username, $providerName, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($patientPassword, $hashed_password)){
                            // Password is correct, so start a new session
                            echo "success password verify";
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["providerId"] = $providerId;
                            $_SESSION["providerEmail"] = $username;
                            $_SESSION["providerName"] = $providerName;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="providerEmail" class="form-control <?php echo (!empty($patientEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $patientEmail_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="providerPassword" class="form-control <?php echo (!empty($providerPassword_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $providerPassword_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>

<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["patientName"]); ?>.<br></b>Welcome to our site.</h1></br>
    <p>
        <a href="scheduleAppointment.php" class="btn btn-outline-success">Make an Appointment</a>
        <a href="editAppointment.php" class="btn btn-outline-secondary">Edit an Appointment</a>
        <a href="reset-password.php" class="btn btn-outline-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-outline-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>

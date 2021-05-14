<?php
require_once "config.php";
include('header.php');
// Initialize the session
//session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
// stop provider from accessing provider page
if (isset($_SESSION["provider"]) && $_SESSION["provider"] === true){
    header("location: provider.php");
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

</body>
</html>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="patientsavailableappointments.php">
                    <i class="fas fa-user-clock"></i>
                    <span>Schedule Appointments</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="editAppointment.php">
                    <i class="fas fa-notes-medical"></i>
                    <span>Edit Appointment</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <i class="far fa-id-card"></i>
                    <span>Patient Profile</span></a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            <div style="justify-content: center;display: flex;"><h3> Welcome <?php echo htmlspecialchars($_SESSION["patientName"]); ?> <h3></div>

<?php
$providerName = "";
$providerAddress = "";
$date = "";
$startTime = "";
$distance = "";
$appointmentId = "";
$patientId = "";
$appointmentStatus = "";


if (isset($_POST["cancel"])){

  $appointmentStatus = "cancelled";
  $patientId = $_SESSION["patientId"];
  $appointmentId = $_POST["appointmentId"];
  $providerName = $_POST["providerName"];

    // Prepare an insert statement
    $sql =  " UPDATE patientappointmentoffer SET status = ?, dateReplied = now()
              WHERE patientId = $patientId and appointmentId = $appointmentId ";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $param_appointmentStatus
      );
        // Set parameters
        $param_appointmentStatus = $appointmentStatus;

        mysqli_stmt_execute($stmt);
        echo "Your appointment with $providerName has been cancelled";
      }
      else{echo "Appointment Not Updated";}
}

else {
  $patientId = $_SESSION["patientId"];
  $sqlQuery = "CALL getacceptedappointments ($patientId)";
  $providerName = "";
  $providerAddress = "";
  $date = "";
  $startTime = "";
  $distance = "";

  $result = $link->query($sqlQuery);

  if ($result->num_rows > 0) {
    echo  '<table border="0" cellspacing="2" cellpadding="2">
          <tr>
              <form>
              <input type="text" name=providername value="Provider Name" style="width: 200px;" readonly />
              <input type="text" name=provideraddress value="Provider Address" style="width: 300px;" readonly />
              <input type="text" name=date value="Date" style="width: 80px;" readonly />
              <input type="text" name=startTime value="Startime" style="width: 80px;" readonly />
              <input type="text" name=dateOfferExpire value="dateOfferExpire" style="width: 150px;" readonly />
              <input type="text" name=distance value="Distance" style="width: 80px;" readonly />
              <input type="text" name="cancel" value="Cancel" style="width: 60px;" readonly />
              </form>
              <br>
          </tr>';

      while ($row = $result->fetch_assoc()) {
          $providerName = $row["providerName"];
          $providerAddress = $row["providerAddress"];
          $date = $row["date"];
          $startTime = $row["startTime"];
          $dateOfferExpire = $row["dateOfferExpire"];
          $distance = $row["distance"];
          $appointmentId = $row["appointmentId"];
          echo '<tr>
                    <form action="editAppointment.php" method="post">
                    <input type="text" name="providerName" value="'.$providerName.'" style="width: 200px;" readonly />
                    <input type="text" name="providerAddress" value="'.$providerAddress.'" style="width: 300px;" readonly >
                    <input type="text" name="date" value="'.$date.'" style="width: 80px;" readonly >
                    <input type="text" name="startTime" value="'.$startTime.'" style="width: 80px;" readonly >
                    <input type="text" name=dateOfferExpire value="'.$dateOfferExpire.'" style="width: 150px;" readonly />
                    <input type="text" name="distance" value="'.$distance.'" style="width: 80px;" readonly >
                    <input type="hidden" name="appointmentId" value="'.$appointmentId.'" style="width: 200px;" readonly >
                    <input type="submit" name="Cancel" value="Cancel" style="width: 60px;" readonly />
                    <input type="hidden" name="cancel" value="cancel" style="width: 60px;" readonly />
                    </form>
                    <br>
                </tr>';
      }
      $result->free();
  }
  else{
    echo "No records exist for that search";
  }
}
?>
<?php
    include('footer.php');
?>

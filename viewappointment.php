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


$providerName = $_POST["ProviderName"];
$providerAddress = $_POST["providerAddress"];
$date = $_POST["date"];
$startTime = $_POST["startTime"];
$distance = $_POST["distance"];
$appointmentId = $_POST["appointmentId"];
$patientId = $_SESSION["patientId"];

if (isset($_POST["accept"])){
  echo"accepted";
  $appointmentStatus = "accepted";
echo $patientId;
echo $appointmentId;

    // Prepare an insert statement
    $sql =  " UPDATE patientappointmentoffer SET status = ?, dateReplied = now()
              WHERE patientId = $patientId and appointmentId = $appointmentId ";
echo $appointmentStatus;
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
      }
      else{echo "Appointment Not Updated";}
}
else if (isset($_POST["decline"])) {

  $appointmentStatus = "declined";

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
      }
      else{echo "Appointment Not declined";}
}
else {
  echo  '<table border="0" cellspacing="2" cellpadding="2">
        <tr>
            <form>
            <input type="text" name=ProviderName value="Provider Name" style="width: 200px;" readonly />
            <input type="text" name=providerAddress value="Provider Address" style="width: 200px;" readonly />
            <input type="text" name=date value="Date" style="width: 200px;" readonly />
            <input type="text" name=startTime value="Startime" style="width: 80px;" readonly />
            <input type="text" name=distance value="Distance" style="width: 200px;" readonly />
            <input type="text" name=accept value="accept" style="width: 80px;" readonly />
            <input type="text" name=decline value="decline" style="width: 80px;" readonly />
            <input type="text" name=submit value="Submit" style="width: 80px;" readonly />
            </form>
            <br>
        </tr>';

        echo '<tr>
                  <form action="viewappointment.php" method="post">
                  <input type="text" name="ProviderName" value="'.$providerName.'" style="width: 200px;" readonly />
                  <input type="text" name="providerAddress" value="'.$providerAddress.'" style="width: 200px;" readonly >
                  <input type="text" name="date" value="'.$date.'" style="width: 200px;" readonly >
                  <input type="text" name="startTime" value="'.$startTime.'" style="width: 80px;" readonly >
                  <input type="text" name="distance" value="'.$distance.'" style="width: 200px;" readonly >
                  <input type="hidden" name="appointmentId" value="'.$appointmentId.'" style="width: 200px;" readonly >
                  <input type="submit" name=accept value="accept" style="width: 80px;" readonly />
                  <input type="submit" name=decline value="decline" style="width: 80px;" readonly />

                  </form>
                  <br>
              </tr>';
}

?>
<?php
    include('footer.php');
?>

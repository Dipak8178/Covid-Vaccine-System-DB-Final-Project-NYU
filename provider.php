<?php
include('header.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// stop patients from accessing provider page
if (isset($_SESSION["patient"]) && $_SESSION["patient"] === true){
    header("location: patient.php");
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
                <a class="nav-link" href="doctor_schedule.php">
                    <i class="fas fa-user-clock"></i>
                    <span>Scheduled Appointments</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="appointment.php">
                    <i class="fas fa-notes-medical"></i>
                    <span>Add Appointment</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <i class="far fa-id-card"></i>
                    <span>Provider Profile</span></a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            <div style="justify-content: center;display: flex;"><h3> Welcome <?php echo htmlspecialchars($_SESSION["providerName"]); ?> <h3></div>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                </div>
            </div>
        </div>
    </div>

<?php
    include('footer.php');
?>

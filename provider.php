<?php
include('header.php');
include('provider_functions.php');
require_once "config.php";


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
// stop patients from accessing provider page
if (isset($_SESSION["type"]) && $_SESSION["type"] === "patient"){
    header("location: patient.php");
    exit;
}

$vaccinatedTotal = NULL;
$result = getCount($_SESSION["providerId"], "total_vaccinated", $link);
foreach ($result as $item){
    $vaccinatedTotal = $item['count'];
}

$scheduledTotal = NULL;
$result = getCount($_SESSION["providerId"], "total_scheduled", $link);
foreach ($result as $item){
    $scheduledTotal = $item['count'];
}

$scheduledToday = NULL;
$result = getCount($_SESSION["providerId"], "total_scheduled_today", $link);
foreach ($result as $item){
    $scheduledToday = $item['count'];
}

$cancelledToday = NULL;
$result = getCount($_SESSION["providerId"], "total_cancelled_today", $link);
foreach ($result as $item){
    $cancelledToday = $item['count'];
}

$totalNoShow = NULL;
$result = getCount($_SESSION["providerId"], "total_noshows", $link);
foreach ($result as $item){
    $totalNoShow = $item['count'];
}

 ?>

<body id="page-top">
<style>
        .countbadges {
            padding: 10px !important;
            padding-left: 1.75rem !important;
            font: 25px sans-serif;
            font-weight: bold;
        }

        label {
            margin-top: 0.5rem;
            margin-right: 0.5rem;
        }

        .bg-badge {
            background-color: #3c4b6491;
        }
        .card-header {
            background-color: #3c4b6491;
        }
        .card {
            min-height: 90%;
            font: 18px sans-serif;
            
        }
    </style>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="provider.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="provider_appt.php">
                    <i class="fas fa-user-clock"></i>
                    <span>Scheduled Appointments</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="provider_add_appt.php">
                    <i class="fas fa-notes-medical"></i>
                    <span>Add Appointment</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="provider_profile.php">
                    <i class="far fa-id-card"></i>
                    <span> Profile</span></a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <div style="justify-content: center;display: flex; margin-bottom: 20px; margin-top:10px" >
                    <h3> Welcome <?php echo htmlspecialchars($_SESSION["providerName"]); ?> <h3>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid" style="display: flex; flex-flow: wrap;">
                <div class="col-xl-6 col-md-6">
                <div class="card bg-badge mb-3 shadow">
                        <div class="card-header countbadges">Appointment Statistics</div>
                        <div class="card-body">
                            <p class="card-text">Scheduled Appointments: <?php echo $scheduledTotal;?></p>
                            <p class="card-text">Scheduled Today: <?php echo $scheduledToday;?></p>
                            <p class="card-text">Cancellations Today: <?php echo $cancelledToday;?></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                <div class="card bg-badge mb-3 shadow">
                        <div class="card-header countbadges">Vaccinated Statistics</div>
                        <div class="card-body ">
                            <p class="card-text">Vaccinated Till Date: <?php echo $vaccinatedTotal;?></p>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>

    <?php
    include('footer.php');
    ?>
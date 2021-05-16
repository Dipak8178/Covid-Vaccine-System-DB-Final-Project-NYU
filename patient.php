<?php
include('header.php');
include('patient_functions.php');

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
// stop providers from accessing patient page
if (isset($_SESSION["provider"]) && $_SESSION["provider"] === true) {
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
                <a class="nav-link" href="patient.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="patient_profile.php">
                    <i class="far fa-id-card"></i>
                    <span>Profile</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="patient_preferences.php">
                <i class="fas fa-clipboard-check"></i>
                    <span>Pereferences</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <div style="justify-content: center;display: flex;">
                    <h3> Welcome <?php echo htmlspecialchars($_SESSION["patientName"]); ?> <h3>
                </div>
                <?php
                $result = getApptOffer($_SESSION["patientId"], $link);
                $result2 = getAcceptedAppt($_SESSION["patientId"], $link);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <div class="card" style="padding:30px">
                            <div class="row">
                                <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                                <i class="fas fa-bell fa-3x" style="color:red"></i>
                                </div>
                                <div class="col-sm">
                                    <div class="card-title">

                                        <h4 style="margin-top:5px"><strong>Appointment Offer</strong></h4>
                                    </div>
                                    <p><strong> Provider Name:</strong> <?php echo $row["providerName"] ?></p>
                                    <p><strong> Provider Address:</strong> <?php echo $row["providerAddress"] ?></p>
                                    <p><strong> Date and Time:</strong> <?php echo $row["date"] ?> at <?php echo date("g:i a", strtotime($row["startTime"])) ?> </p>
                                    <p><strong> Distance:</strong> <?php echo $row["distance"] ?> miles</p>

                                    <div>
                                        <button class="btn btn-primary btn-round" name="accept" onclick="accept(<?php echo $row['appointmentId'] ?> )">Accept</button>
                                        <button class="btn btn-primary btn-round" name="decline" onclick="decline(<?php echo $row['appointmentId'] ?> )">Decline</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                    $result->free();
                } else if ($result2->num_rows > 0) {
                    foreach ($result2 as $item) {
                        $appointmentId = $item["appointmentId"];?>
                        <div class="card" style="padding:30px">
                            <div class="row">
                                <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                                    <i class="fas fa-tasks fa-3x"></i>
                                </div>
                                <div class="col-sm">
                                    <div class="card-title">

                                        <h4 style="margin-top:5px"><strong>Scheduled Appointment</strong></h4>
                                    </div>
                                    <p><strong> Provider Name:</strong> <?php echo $item["providerName"] ?></p>
                                    <p><strong> Provider Address:</strong> <?php echo $item["providerAddress"] ?></p>
                                    <p><strong> Date and Time:</strong> <?php echo $item["date"] ?> at <?php echo date("g:i a", strtotime($item["startTime"])) ?> </p>
                                    <p><strong> Distance:</strong> <?php echo $item["distance"] ?> miles</p>

                                    <div>
                                        <button class="btn btn-primary btn-round" name="cancel" onclick="cancel(<?php echo $appointmentId ?> )">Cancel</button>
                                    </div>
                                </div>
                           </div>
                        </div>
                    <?php
                        }
                    $result2->free();
                    }
                    else{
                        ?>
                        <div class="card" style="padding:30px">
                        <div class="row">
                            <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                            <i class="fas fa-bell fa-3x"></i>
                            </div>
                            <div class="col-sm">
                                <div class="card-title">

                                    <h4 style="margin-top:5px"><strong>No Appointment Offer Available</strong></h4>
                                </div>
                                <p><strong> Check back at 8 am every morning.</strong></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
      </div>

    <script>
        function accept(apptId) {
            $.ajax({
                type: "POST",
                url: 'patient_functions.php',
                data: {
                    action: 'accept',
                    appointmentId: apptId,
                    patientId: <?php echo $_SESSION["patientId"] ?>
                },
                success: function() {
                    alert("Appointment Accepted");
                    location.reload();
                }
            });
        }

            function decline(apptId) {
                $.ajax({
                    type: "POST",
                    url: 'patient_functions.php',
                    data: {
                        action: 'decline',
                        appointmentId: apptId,
                        patientId: <?php echo $_SESSION["patientId"] ?>
                    },
                    success: function() {
                        alert("Appointment Declined");
                        location.reload();
                    }
                });
            }

            function cancel(apptId) {
                $.ajax({
                    type: "POST",
                    url: 'patient_functions.php',
                    data: {
                        action: 'cancel',
                        appointmentId: apptId,
                        patientId: <?php echo $_SESSION["patientId"] ?>
                    },
                    success: function() {
                        alert("Appointment Cancelled");
                        location.reload();
                    }
                });
            }
    
    </script>

    <?php
    include('footer.php');
    ?>
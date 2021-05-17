<?php
// Include config file & header file
require_once "config.php";
include('header.php');
include('provider_functions.php');

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
// stop patients from accessing provider page
if (isset($_SESSION["patient"]) && $_SESSION["patient"] === true) {
    header("location: patient.php");
    exit;
}
?>

<?php
    $result = NULL;
    $result = getAppt($link, "confirmed", $_SESSION["providerId"]);
?>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark">

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="provider.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <li class="nav-item active">
                <a class="nav-link" href="provider_appt.php">
                    <i class="fas fa-user-clock"></i>
                    <span>Scheduled Appointments</span></a>
            </li>

        <li class="nav-item ">
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

            <div style="justify-content: center;display: flex;">
                <h3> Welcome <?php echo htmlspecialchars($_SESSION["providerName"]); ?> <h3>
            </div>

            <!-- Begin Page Content -->

            <div class="container-fluid" >
                <h1 class="h4 mt-4 text-gray-800" style="justify-content: center;display: flex;">Scheduled Appointment Management</h1>

                <!-- DataTales Example -->
                <div class="card-body">
                
                    <div class="table-responsive">
                        <table class="table table-bordered" id="appointment_table">
                            <thead>
                                <tr>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Patient Name</th>
                                    <th>Patient Phone Number</th>
                                    <th>Status</th>                                    
                                </tr>
                            </thead>
                            <tbody> <?php 
                                    $currDate = date("Y-m-d");
                                    if (!empty($result)) { ?>

                                    <?php 
                                    $counter = 0;
                                    foreach ($result as $item) { ?>
                                        <tr >
                                        
                                            <td><?php echo $item['date']; ?></td>
                                            <td><?php echo $item['startTime']; ?></td>
                                            <td><?php echo $item['patientName']; ?></td>
                                            <td><?php echo $item['patientPhone']; ?></td>
                                            <td >
                                            <div id="readonly<?php echo $counter; ?>">
                                            <?php echo ucwords($item['status']);?>
                                            
                                            <button type="button" class="btn btn-primary" onclick="edit(<?php echo $counter; ?>)" <?php echo $item['date'] != $currDate ? 'disabled style="pointer-events:auto" title="Scheduled Appointments can only be updated on the day of the appointment."' : ''?> 
                                            >Edit</button>
                                            </div>
                                            <div id="editing<?php echo $counter; ?>" style="display:none">
                                            <select id ="status" class="form-select" aria-label="Default select example">
                                            <option value="accepted" selected><?php echo ucwords($item['status']); ?></option>
                                            <option value="noshow">No Show</option>
                                            <option value="vaccinated">Vaccinated</option>
                                            </select>
                                                                                        
                                            <button type="button" style= "margin-left:5px" class="btn btn-primary" onclick="save(<?php echo $item['appointmentId']?>,<?php echo $item['patientId']?>)"  <?php echo $item['date']< $currDate ? 'disabled' : ''?>>Save</button>
                                            <button type="button" style= "margin-left:5px"class="btn btn-primary secondary" onclick="cancel(<?php echo $counter; ?>)">Cancel</button>
                                            <div>
                                            </td>
                                        </tr>
                                    <?php 
                                    $counter++;
                                    } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
</div>

<script>
        function edit(c) {
            document.getElementById('editing'+c).style.display = "flex";
            document.getElementById('readonly'+c).style.display = "none";
        }

        function cancel(c) {
            document.getElementById('editing'+c).style.display = "none";
            document.getElementById('readonly'+c).style.display = "block";
        }


        function save(apptId, patientId) {
            var selected = $('#status').find(":selected").val();
            console.log(selected);
            console.log(apptId);
            console.log(patientId);

            $.ajax({
                type: "POST",
                url: "provider_functions.php",
                data: {
                    action: 'status_update',
                    patientId: patientId,
                    selected: selected,
                    appointmentId: apptId
                },
                success: function() {
                    alert("Status Updated");
                    location.reload();
                }
            });
        }
</script>

<?php
include('footer.php');
?>
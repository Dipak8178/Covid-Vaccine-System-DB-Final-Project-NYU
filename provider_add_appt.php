<?php
// Include config file & header file
require_once "config.php";
include('header.php');
include('functions.php');

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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        addApp($link, $_SESSION["providerId"] , $date, $startTime);
        Header('Location: '.$_SERVER['PHP_SELF']);
        Exit(); 
    }
  $result = getAppt($link, "future", $_SESSION["providerId"]);
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

        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-user-clock"></i>
                <span>All Appointments</span></a>
        </li>

        <li class="nav-item active">
            <a class="nav-link" href="provider_add_appt.php">
                <i class="fas fa-notes-medical"></i>
                <span>Add Appointment</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="provider_profile.php">
                <i class="far fa-id-card"></i>
                <span>Provider Profile</span></a>
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

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Appointment Management</h1>

                <!-- DataTales Example -->

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <h6 class="m-0 font-weight-bold text-primary">Appointment List</h6>
                            </div>

                            <div class="col-sm-6" align="right">
                                <button type="button" name="addNewAppt" id="addNewAppt" data-toggle="modal" data-target="#addAppointment" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                
                    <div class="table-responsive">
                        <table class="table table-bordered" id="appointment_table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                </tr>
                            </thead>
                            <tbody> <?php if (!empty($result)) { ?>

                                    <?php foreach ($result as $item) { ?>
                                        <tr>
                                            <td><?php echo $item['date']; ?></td>
                                            <td><?php echo $item['startTime']; ?></td>
                                            <td><?php echo 'Available'; ?></td>
                                        </tr>
                                    <?php } ?>
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


<div id="addAppointment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form method="post" id="addAppt">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Vaccine Appointment</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="appointment-form" method="post">
                        <div class="form-group">
                            <label>Schedule Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" name="appointmentDate" id="appointmentDate" class="form-control" required />
                            </div>

                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon"><i class="fas fa-clock"></i></span>
                                </div>
                                <input type="time" name="startTime" id="startTime" class="form-control" required />
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="addApptSub" id="addApptSub" class="btn btn-primary" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
//  $(document).ready(function(){  
//       $('#appointment_table').DataTable();  
//  });
    var date = new Date();
    date.setDate(date.getDate() + 3);
    // Only allow adding appointments 3 days in the future
    $('#appointmentDate').datepicker({
        startDate: date,
        format: "yyyy-mm-dd",
        autoclose: true
    });

    $('#addNewAppt').click(function() {
        $('#addAppt')[0].reset();
        $('#addAppt').parsley().reset();
        $('#addAppt').modal('show');
    });

    $('#appointment-form').on('submit', function(event){
		event.preventDefault();
        $('#appointment_table').data().reload();  
	});

</script>


<?php
include('footer.php');
?>
<?php
require_once "config.php";
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

$result1 = getPatientTimePreference($_SESSION["patientId"], $link);

$slots = array(); // initializing  
while ($row = $result1->fetch_assoc()) {
    $slots[] = $row['slotId']; // store in an array
}

$distance = NULL;
$result2 = getPatientDistancePreference($_SESSION["patientId"], $link);
while ($row = $result2->fetch_assoc()) {
    $distance = $row['distancePreference']; // store in an array
}


?>

<body id="page-top">

<style> 
.card {
    background-color: white;
    margin-bottom:30px;
    border: 1px solid #c1c6cb !important;

}
</style>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="patient.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="patient_profile.php">
                    <i class="far fa-id-card"></i>
                    <span>Profile</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="patient_preference.php">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Pereferences</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="patient_med_history.php">
                    <i class="fas fa-file-medical"></i>
                    <span>Medical History</span></a>
            </li>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <div style="justify-content: center;display: flex; margin-bottom: 20px; margin-top:10px">
                    <h3> Welcome <?php echo htmlspecialchars($_SESSION["patientName"]); ?> <h3>
                </div>
                <div class="card shadow" style="padding:30px">
                    <div class="row">
                        <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                            <i class="fas fa-road fa-3x"></i>
                        </div>
                        <div class="col-sm">
                            <div class="card-title">
                                <h4 style="margin-top:5px"><strong>Distance Preference<re /strong>
                                </h4>
                            </div>
                            <div id="readonly" style="display:block">
                                <div class="form-group row">
                                    <label class="col-sm-2"> <strong> Distance:</strong></label>
                                    <div class="col-sm-10" style="font-weight:normal"><?php echo $distance ?> miles</div>
                                </div>
                            </div>
                            <div id="editing" style="display:none">
                                <div class="form-group row">
                                    <label class="col-sm-2"> <strong> Distance:</strong></label>
                                    <div class="col-sm-10" style="font-weight:normal">
                                        <input type="text" name="neDdistance" id="newDistance" value="<?php echo $distance ?>"> miles </input>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-round" id="edit-dist" onclick="editDist()">Edit</button>
                            <button class="btn btn-primary btn-round" style="display:none" id="save-dist" onclick="saveDist()">Save</button>
                            <button class="btn btn-primary btn-round" style="display:none" id="cancel-dist" onclick="cancelDist()">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="card shadow" style="padding:30px; margin-top:30px">
                    <div class="row">
                        <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                            <i class="far fa-clock fa-3x"></i>
                        </div>
                        <div class="col-sm">
                            <div class="card-title">

                                <h4 style="margin-top:5px"><strong>Time Preference</strong></h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="timePreference">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Morning (8am to 12pm)</th>
                                            <th>Afternoon (12pm to 4pm)</th>
                                            <th>Evening (4pm to 8pm)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $dayOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                                        $counter = 1;
                                        for ($i = 0; $i < count($dayOfWeek); $i++) {
                                            for ($x = 0; $x <= 3; $x++) {
                                                if ($x == 0) { ?>
                                                    <tr>
                                                        <th><?php echo $dayOfWeek[$i] ?></th>
                                                    <?php
                                                } else if ($x < 3) { ?>
                                                        <?php $counter ?>

                                                        <td><input type='checkbox' name='checkbox[]' disabled="true" value="<?php echo $counter ?>" id="<?php echo $counter ?>" <?php if (in_array($counter, $slots)) echo "checked = 'checked'" ?> /></td>
                                                    <?php
                                                    $counter++;
                                                } else { ?>
                                                        <td><input type='checkbox' name='checkbox[]' disabled="true" value="<?php echo $counter ?>" id="<?php echo $counter ?>" <?php if (in_array($counter, $slots)) echo "checked = 'checked'" ?> /></td>
                                                    </tr>
                                        <?php
                                                    $counter++;
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary btn-round" id="edit-time" onclick="edit()">Edit</button>
                                <button class="btn btn-primary btn-round" style="display:none" id="save-time" onclick="save()">Save</button>
                                <button class="btn btn-primary btn-round" style="display:none" id="cancel-time" onclick="cancel()">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function edit() {
            $("#timePreference :input").prop("disabled", false);
            document.getElementById('edit-time').style.display = "none";
            document.getElementById('save-time').style.display = "";
            document.getElementById('cancel-time').style.display = "";

        }

        function cancel() {
            $("#timePreference :input").prop("disabled", true);
            // document.getElementById('edit-time').style.display = "";
            // document.getElementById('save-time').style.display = "none";
            // document.getElementById('cancel-time').style.display = "none";
            location.reload();

        }

        function save() {
            var checkboxes = new Array();
            $("input:checked").each(function() {
                checkboxes.push($(this).val());
            });
            console.log(checkboxes);
            if (checkboxes.length == 0){
                alert("Must make a selection for Time Preference");
            }
            else {
                $.ajax({
                type: "POST",
                url: 'patient_functions.php',
                data: {
                    action: 'preference',
                    patientId: <?php echo $_SESSION["patientId"] ?>,
                    data: checkboxes
                },
                success: function(data) {
                    alert("Time Preference Updated");
                    location.reload();
                }
            });
                
            }
        }

        function editDist() {
            document.getElementById('editing').style.display = "block";
            document.getElementById('readonly').style.display = "none";

            document.getElementById('edit-dist').style.display = "none";
            document.getElementById('save-dist').style.display = "";
            document.getElementById('cancel-dist').style.display = "";

        }

        function cancelDist() {
            document.getElementById('editing').style.display = "none";
            document.getElementById('readonly').style.display = "block";
            document.getElementById('edit-dist').style.display = "";
            document.getElementById('save-dist').style.display = "none";
            document.getElementById('cancel-dist').style.display = "none";
        }

        function saveDist() {
            var newDistance = document.getElementById('newDistance').value;
            console.log(newDistance);
            if (!newDistance){
                alert("Distance Preference cannot be empty")
            }
            else{
                $.ajax({
                    type: "POST",
                    url: 'patient_functions.php',
                    data: {
                        action: 'distance',
                        patientId: <?php echo $_SESSION["patientId"] ?>,
                        distance: newDistance
                    },
                    success: function(data) {
                        alert("Distance Preference Updated");
                        location.reload();

                    }
                });
            }
        }
    </script>



    <?php
    include('footer.php');
    ?>
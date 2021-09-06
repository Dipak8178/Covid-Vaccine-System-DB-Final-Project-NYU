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
if (isset($_SESSION["type"]) && $_SESSION["type"] === "provider"){
    header("location: provider.php");
    exit;
}

$result = getPatientMedicalHistory($_SESSION["patientId"], $link);
$medHistory = array(); // initializing  
while($row = $result->fetch_assoc()){
        $medHistory[] = $row['medCondition']; // store in an array
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
            <li class="nav-item">
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

                <div class="card shadow" style="padding:30px; margin-top:30px">
                    <div class="row">
                        <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                        <i class="fas fa-file-medical fa-3x"></i>                        
                        </div>
                        <div class="col-sm">
                            <div class="card-title">

                                <h4 style="margin-top:5px"><strong>Medical History</strong></h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="medCondition">
                                    <thead>
                                        <tr>
                                            <th>Check if applicable</th>
                                            <th>Medical Condition</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type='checkbox' name='cancer_checkbox' id="cancer_checkbox" disabled = "true" value ="Cancer" <?php if(in_array("Cancer",$medHistory)){echo "checked = 'checked'";}?>/></td>
                                            <td>Cancer</td>
                                        </tr>
                                        <tr >
                                            <td><input type='checkbox' name='kidney_checkbox' id="kidney_checkbox" disabled = "true" value="Cronic Kidney Disease" <?php if(in_array("Cronic Kidney Disease",$medHistory)){echo "checked = 'checked'";}?>/></td>
                                            <td>Cronic Kidney Disease</td>
                                        </tr>
                                        <tr>
                                            <td><input type='checkbox' name='copd_checkbox' id="copd_checkbox" disabled = "true" value="Chronic Obstructive Pulmonary Disease(COPD)" <?php if(in_array("Chronic Obstructive Pulmonary Disease(COPD)",$medHistory)){echo "checked = 'checked'";}?>/></td>
                                            <td>Chronic Obstructive Pulmonary Disease(COPD)</td>
                                        </tr>
                                        <tr>
                                            <td><input type='checkbox' name='heart_checkbox' id="heart_checkbox" disabled = "true" value="Heart Conditions" <?php if(in_array("Heart Conditions",$medHistory)) {echo "checked = 'checked'";}?>/></td>
                                            <td>Heart Conditions(e.g. heart failure, coronary artery disease, cardiomyopathies)</td>
                                        </tr>
                                        <tr>
                                            <td><input type='checkbox' name='immuno_checkbox' id="immuno_checkbox" disabled = "true" value="Immunocompromised" <?php if(in_array("Immunocompromised",$medHistory)) {echo "checked = 'checked'";}?> /></td>
                                            <td>Immunocompromised(weakened immune system) due to solid organ transplant</td>
                                        </tr>
                                        <tr>
                                            <td><input type='checkbox' name='obese_checkbox' id="obese_checkbox" disabled = "true" value="Obesity" <?php if(in_array("Obesity",$medHistory)){echo "checked = 'checked'";}?>/></td>
                                            <td>Obesity(e.g. body mass index of 30kg/m2 or higher)</td>
                                        </tr>
                                        <tr>
                                            <td><input type='checkbox' name='sickle_checkbox' id="sickle_checkbox" disabled = "true" value="Sickle Cell Disease" <?php if(in_array("Sickle Cell Disease",$medHistory)) {echo "checked = 'checked'";}?>/></td>
                                            <td>Sickle cell disease</td>
                                        </tr>
                                        <tr>
                                            <td><input type='checkbox' name='smoking_checkbox' id="smoking_checkbox" disabled = "true" value="Smoking" <?php if(in_array("Smoking",$medHistory)) {echo "checked = 'checked'";}?> /></td>
                                            <td>Smoking</td>
                                        </tr>
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

    <script>
        function edit() {
            $("#medCondition :input").prop("disabled", false);    
            document.getElementById('edit-time').style.display = "none";
            document.getElementById('save-time').style.display = "";
            document.getElementById('cancel-time').style.display = "";
     
        }

        function cancel() {
            // $("#medCondition :input").prop("disabled", true);    
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
           
        $.ajax({
            type: "POST",
            url: 'patient_functions.php',
            data: {
                action: 'med_history',
                patientId: <?php echo $_SESSION["patientId"] ?>,
                data: checkboxes
            },
            success: function(data) {
                alert("Medical History Updated");
                location.reload();

            }
        });
    }

    </script>

    <?php
    include('footer.php');
    ?>
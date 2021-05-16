<?php
require_once "config.php";
include('header.php');
include('patient_functions.php');
// Initialize the session
//session_start();

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
            <li class="nav-item">
                <a class="nav-link" href="patient.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item active">
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
                $result = getPatientDetail($_SESSION["patientId"], $link);
                if (!empty($result)) {
                    foreach ($result as $item) { ?>
                        <div class="card" style="padding:30px">
                            <div class="row">
                                <div class="col" style="flex-basis: 0; flex-grow: 0; max-width: 100%;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                                <div class="col-sm">
                                    <div class="card-title">

                                        <h4 style="margin-top:5px"><strong>Profile</strong></h4>
                                    </div>

                                    <div id="readonly" style="display:block">
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Name:</strong></label>
                                            <div class="col-sm-10"><?php echo $item["patientName"] ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 "> <strong> SSN:</strong></label>
                                            <div class="col-sm-10"><?php echo $item["ssn"] ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 "> <strong> Date of Birth:</strong></label>
                                            <div class="col-sm-10">
                                                <?php echo $item["dob"] ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 "> <strong> Address:</strong></label>
                                            <div class="col-sm-10">
                                                <?php echo $item["patientAddress"] ?> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Phone:</strong></label>
                                            <div class="col-sm-10">
                                                <?php echo $item["patientPhone"] ?> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Email:</strong></label>
                                            <div class="col-sm-10">
                                                <?php echo $item["patientEmail"] ?> </div>
                                        </div>
                                        <button class="btn btn-primary btn-round" id="edit-profile" onclick="edit()">Edit</button>
                                        <button class="btn btn-primary btn-round" id="reset-password" onclick="resetPassword()">Reset Password</button>
                                    </div>
                                    <div id = "pass-reset" style="display:none">
                                        <div class="form-group row"  >
                                            <label class="col-sm-2"><strong> Password:</strong> </label>
                                            <div class="col-sm-10">
                                                <input type="password" name="patientPassword" id="patientPassword" placeholder="************" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Confirm Password:</strong></label>
                                            <div class="col-sm-10">
                                                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="************" />
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-round" id="save-pass" onclick="savePass()">Save Password</button>
                                        <button class="btn btn-primary btn-round" id="cancel-pass" onclick="cancelPass()">Cancel</button>
                                        </div>

                                    <div id="editing" style="display:none">
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Name:</strong></label>
                                            <div class="col-sm-10 ">
                                                <p type="text" name="patientName" id="patientName" data-placement="right" title="Name cannot be Edited. Email customer@support.com to udpate."> 
                                                <?php echo $item["patientName"] ?> </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> SSN:</strong> </label>
                                            <div class="col-sm-10">
                                            <p data-toggle="tooltip" data-placement="right" title="SSN cannot be Edited. Email customer@support.com to udpate."> <?php echo $item["ssn"] ?> </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Date of Birth:</strong> </label>
                                            <div class="col-sm-10">
                                                <input type="date" name="dob" id="dob" value="<?php echo $item["dob"] ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"><strong> Address:</strong> </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="patientAddress" id="patientAddress" value="<?php echo $item["patientAddress"] ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"><strong> Phone:</strong> </label>
                                            <div class="col-sm-10">
                                                <input type="text" pattern="\d{10}" name="patientPhone" id="patientPhone" value="<?php echo $item["patientPhone"] ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <strong> Email:</strong></label>
                                            <div class="col-sm-10">
                                                <input type="email" name="patientEmail" id="patientEmail" value="<?php echo $item["patientEmail"] ?>" />
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-round" id="save-profile" onclick="save()">Save Profile</button>
                                        <button class="btn btn-primary btn-round" id="cancel-profile" onclick="cancel()">Cancel</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function edit() {
            document.getElementById('editing').style.display = "block";
            document.getElementById('readonly').style.display = "none";
        }

        function cancel() {
            document.getElementById('editing').style.display = "none";
            document.getElementById('readonly').style.display = "block";
            document.getElementById('reset-password').style.display = "";

        }

        function resetPassword() {
            document.getElementById('pass-reset').style.display = "block";
            document.getElementById('edit-profile').style.display = "none";
            document.getElementById('reset-password').style.display = "none";

        }

        function cancelPass() {
            document.getElementById('pass-reset').style.display = "none";
            document.getElementById('editing').style.display = "none";
            document.getElementById('readonly').style.display = "block";
            document.getElementById('edit-profile').style.display = "";
            document.getElementById('reset-password').style.display = "";
        }


        function save() {
            var email = document.getElementById("patientEmail").value;
            var address = document.getElementById("patientAddress").value;
            var phone = document.getElementById("patientPhone").value;
            var dob = document.getElementById("dob").value;
            
            $.ajax({
                type: "POST",
                url: 'patient_functions.php',
                data: {
                    action: 'update',
                    patientId: <?php echo $_SESSION["patientId"] ?>,
                    email: email,
                    address: address,
                    phone: phone,
                    dob: dob
                },
                success: function() {
                    alert("Profile Updated");
                    location.reload();
                }
            });
        }

        function savePass() {
            var password = document.getElementById("patientPassword").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password!=confirmPassword){
                alert("Password Donot Match");
            }
            else {
                $.ajax({
                    type: "POST",
                    url: 'patient_functions.php',
                    data: {
                        action: 'password',
                        patientId: <?php echo $_SESSION["patientId"] ?>,
                        password: password,
                    },
                    success: function() {
                        alert("Password Changed");
                        location.reload();
                    }
                });
            }
        };
    </script>

    <?php
    include('footer.php');
    ?>
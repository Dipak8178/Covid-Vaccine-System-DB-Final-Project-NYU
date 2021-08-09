<?php
// Include config file & header file
include('header.php');
include('provider_functions.php');

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
// stop patients from accessing provider page
if (isset($_SESSION["type"]) && $_SESSION["type"] === "patient"){
    header("location: patient.php");
    exit;
}
?>

<!-- Page Wrapper -->
<div id="wrapper">
    <style>
        .card {
            border: 1px solid #c1c6cb !important;
        }
    </style>

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
            <a class="nav-link" href="provider_appt.php">
                <i class="fas fa-user-clock"></i>
                <span>Scheduled Appointments</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="provider_add_appt.php">
                <i class="fas fa-notes-medical"></i>
                <span>Add Appointment</span></a>
        </li>

        <li class="nav-item active">
            <a class="nav-link" href="provider_profile.php">
                <i class="far fa-id-card"></i>
                <span>Profile</span></a>
        </li>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <div style="justify-content: center;display: flex; margin-bottom: 20px; margin-top:10px">
                <h3> Welcome <?php echo htmlspecialchars($_SESSION["providerName"]); ?> <h3>
            </div>

            <?php
            $result = getProviderDetail($_SESSION["providerId"], $link);
            if (!empty($result)) {
                foreach ($result as $item) { ?>
                    <div class="card shadow" style="padding:30px">
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
                                        <div class="col-sm-10"><?php echo $item["providerName"] ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 "> <strong> Address:</strong></label>
                                        <div class="col-sm-10">
                                            <?php echo $item["providerAddress"] ?> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2"> <strong> Phone:</strong></label>
                                        <div class="col-sm-10">
                                            <?php echo $item["providerPhone"] ?> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2"> <strong> Email:</strong></label>
                                        <div class="col-sm-10">
                                            <?php echo $item["providerEmail"] ?> </div>
                                    </div>
                                    <button class="btn btn-primary btn-round" id="edit-profile" onclick="edit()">Edit</button>
                                    <button class="btn btn-primary btn-round" id="reset-password" onclick="resetPassword()">Reset Password</button>
                                </div>
                                <div id="pass-reset" style="display:none">
                                    <div class="form-group row">
                                        <label class="col-sm-2"><strong> Password:</strong> </label>
                                        <div class="col-sm-10">
                                            <input type="password" name="providerPassword" id="providerPassword" placeholder="************" />
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
                                            <p type="text" name="providerName" id="providerName" data-placement="right" title="Name cannot be Edited. Email customer@support.com to udpate.">
                                                <?php echo $item["providerName"] ?> </p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2"><strong> Address:</strong> </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="providerAddress" id="providerAddress" style="width:50%" value="<?php echo $item["providerAddress"] ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2"><strong> Phone:</strong> </label>
                                        <div class="col-sm-10">
                                            <input type="text" pattern="\d{10}" name="providerPhone" id="providerPhone" style="width:50%" value="<?php echo $item["providerPhone"] ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2"> <strong> Email:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="email" name="providerEmail" id="providerEmail" style="width:50%" value="<?php echo $item["providerEmail"] ?>" />
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
        var email = document.getElementById("providerEmail").value;
        var address = document.getElementById("providerAddress").value;
        var phone = document.getElementById("providerPhone").value;

        if (email == "" || address == "" || phone == "") {
            alert("All fields must be completed in order to save.");
            return false

        } else if (!ValidateEmail(email)) {
            return false
        } else if (!ValidateAddress(address)) {
            return false
        } else if (!ValidatePhone(phone)) {
            return false
        } else {

            $.ajax({
                type: "POST",
                url: "provider_functions.php",
                data: {
                    action: "update",
                    providerId: <?php echo $_SESSION["providerId"] ?>,
                    email: email,
                    address: address,
                    phone: phone,
                },
                success: function() {
                    alert("Profile Updated");
                    location.reload();
                }
            });
        }
    }

    function ValidateEmail(email) {
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)) {
            return (true)
        }
        alert("You have entered an invalid email address!")
        return (false)
    }

    function ValidateAddress(address) {
        if (/[<>=%\?]/.test(address)) {
            alert("Address cannot have scripting text or <>, %, =, and ?")
            return (false)
        }
        return (true)
    }

    function ValidatePhone(phone) {
        if (/[0-9]{10}/.test(phone)) {
            return (true)
        }
        alert("Enter 10 Digit US Phone Number")
        return (false)
    }

    function savePass() {
        var password = document.getElementById("providerPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        if (password != confirmPassword) {
            alert("Password Donot Match");
        } else if (password == "" || confirmPassword == "") {
            alert("All fields must be completed.");

        } else {
            $.ajax({
                type: "POST",
                url: "provider_functions.php",
                data: {
                    action: 'password',
                    providerId: <?php echo $_SESSION["providerId"] ?>,
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
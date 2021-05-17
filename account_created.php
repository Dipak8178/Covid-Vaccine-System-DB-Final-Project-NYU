<?php

// Include config file
require_once "config.php";
include('header.php');

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["type"]) && $_SESSION["type"] === "patient") {
        header("location: patient.php");
        exit;
    }
    if (isset($_SESSION["type"]) && $_SESSION["type"] === "provider") {
        header("location: provider.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<div class="container" style="margin-top: 2rem;">
    <div class="row">
        <aside class="col-sm-9" style="margin: auto">
            <div class="card" style="align-items: center; padding:20px">
                <h2 class="card-title text-center mb-4 mt-4">
                    Registration Completed
                </h2>

                <article class="card-body"  style="width:90%; display: contents;">

                    <div style="display: flex;justify-content: center;">
                        <p>Thank You For Registering. </p>
                
                    </div>
                    <div style="display:flex;text-align: center; width:65%">
                    <p>Please log in to your account and add your Availabilty and Medical History in order for us to 
                        send you appropiate Vaccine Appointment Offers.</p>
                    </div>
                    <div >
                    <button type="button" class="btn btn-primary"  onclick="location.href='index.php'">Log In</button>
                    </div>
                    </div>
            </div>
        </aside>
    </div>
</div>
<?php
include('footer.php');
?>
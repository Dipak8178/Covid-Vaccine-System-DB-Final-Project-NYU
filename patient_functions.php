<?php
require_once "config.php";
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'accept':
                acceptAppt($_POST['appointmentId'], $_POST['patientId'], $link);
                break;
            case 'decline':
                declineAppt($_POST['appointmentId'], $_POST['patientId'],$link);
                break;
            case 'cancel':
                cancelAppt($_POST['appointmentId'], $_POST['patientId'],$link);
                break;
            case 'update':
                updatePatientDetail($_POST['patientId'], $_POST['address'], $_POST['email'],  $_POST['phone'], $_POST['dob'], $link);
                break;
            case 'password':
                updatePassword($_POST['patientId'], $_POST['password'], $link);
                break;
            case 'preference':
                updateTimePreference($_POST['patientId'], $_POST['data'], $link);
                break;
            case 'distance':
                updateDistancePreference($_POST['patientId'], $_POST['distance'], $link);
                break;
            case 'med_history':
                uploadMedicalHistory($_POST['patientId'], $_POST['data'], $link);
                break;
        }
    }
    function getTimePrefCount($patientId, $link) {
        $result = NULL;
        $sql = "SELECT * FROM PatientTimePreference WHERE patientID = ?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $param_patientId,
                );
                // Set parameters
                $param_patientId= $patientId;
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function acceptAppt($appointmentId, $patientId, $link) {
        // Prepare an insert statement
        $sql =  " UPDATE PatientAppointmentOffer SET status = ?, dateReplied = now()
        WHERE patientId = $patientId and appointmentId = $appointmentId ";
        if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param(
        $stmt,
        "s",
        $param_appointmentStatus
        );
        // Set parameters
        $param_appointmentStatus = "accepted";

        mysqli_stmt_execute($stmt);
        echo "Appointment Accepted";
        }
        else{
            echo "Appointment Not Updated";
        }
    }

    function declineAppt($appointmentId, $patientId, $link) {
        // Prepare an insert statement
        $sql =  "UPDATE PatientAppointmentOffer SET status = ?, dateReplied = now()
        WHERE patientId = $patientId and appointmentId = $appointmentId ";
        if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param(
        $stmt,
        "s",
        $param_appointmentStatus
        );
        // Set parameters
        $param_appointmentStatus = "declined";

        mysqli_stmt_execute($stmt);
        echo "Appointment Declined";
        }
        else{
            echo "Appointment Not Updated";
        }
    }

    
    function cancelAppt($appointmentId, $patientId, $link) {
        // Prepare an insert statement
        $sql =  "UPDATE PatientAppointmentOffer SET status = ?
        WHERE patientId = $patientId and appointmentId = $appointmentId ";
        if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param(
        $stmt,
        "s",
        $param_appointmentStatus
        );
        // Set parameters
        $param_appointmentStatus = "cancel";
        mysqli_stmt_execute($stmt);
        echo "Appointment Cancelled";
        }
        else{
            echo "Appointment Not Updated";
        }
    }
    function getApptOffer($patientId, $link) {
        $result = NULL;
        $sql = "CALL getpatientoffers (?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $param_patientId
                );
                // Set parameters
                $param_patientId= $patientId;
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function getAcceptedAppt($patientId, $link) {
        $result = NULL;
        $sql = "CALL getacceptedappointments(?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $param_patientId
                );
                // Set parameters
                $param_patientId= $patientId;
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function getPatientDetail($patientId, $link) {
        $result = NULL;
        $sql = "CALL getUserInfo(?, ?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "is",
                $param_patientId,
                $param_type
                );
                // Set parameters
                $param_patientId= $patientId;
                $param_type= "patient";
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function updatePatientDetail($patientId, $patientAddress, $patientEmail,  $patientPhone, $dob, $link) {
        $result = NULL;
        $sql = "CALL updatePatientProfile(?, ?, ?, ?, ?, ?, ?);";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Get Longitude and Latitude from patientAddress using Google Maps API
            $api_key = 'AIzaSyA3mM2cTa1pPBc73_wsR2YEkpEb-W45b8k';
            $address = str_replace(" ", "+", $patientAddress);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=$api_key");
            $json = json_decode($json);
            $patientLatitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $patientLongitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

            
            mysqli_stmt_bind_param(
                $stmt,
                "issssdd",
                $param_patientId,
                $param_patientAddress,
                $param_patientEmail,
                $param_patientdob,
                $param_patientPhone,
                $param_patientLongitude,
                $param_patientLatitude
                );
                // Set parameters
                $param_patientId = $patientId;
                $param_patientAddress = $patientAddress;
                $param_patientEmail = $patientEmail;
                $param_patientdob = $dob;
                $param_patientPhone = $patientPhone;
                $param_patientLongitude = $patientLongitude ;
                $param_patientLatitude =  $patientLatitude;

                if(mysqli_stmt_execute($stmt)){
                    echo "Success";
                }
        }
        else{echo "Update Failed";}
    }

    function updatePassword($patientId, $patientPassword, $link) {
        $sql = "CALL updatePassword(?, ?, ?);";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "iss",
                $param_patientId,
                $param_type,
                $param_patientPassword,
                );
                // Set parameters
                $param_patientId = $patientId;
                $param_type = "patient";
                $param_patientPassword = password_hash($patientPassword, PASSWORD_DEFAULT);
       
                if(mysqli_stmt_execute($stmt)){
                    echo "Success";
                }
        }
        else{echo "Update Failed";}
    }    
    
    function getPatientTimePreference($patientId, $link) {
        $result = NULL;
        $sql = "CALL getPatientTimePreference(?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $param_patientId,
                );
                // Set parameters
                $param_patientId= $patientId;
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function getPatientDistancePreference($patientId, $link) {
        $result = NULL;
        $sql = "CALL getPatientDistancePreference(?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $param_patientId,
                );
                // Set parameters
                $param_patientId= $patientId;
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function updateTimePreference($patientId, $data, $link) {
        $sql = "DELETE FROM PatientTimePreference WHERE patientId =?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $param_patientId,
                );
                // Set parameters
                $param_patientId = $patientId;      
                if(mysqli_stmt_execute($stmt)){
                    echo "Deleted Records";
                }
        }

        $sql = "INSERT INTO PatientTimePreference VALUES (?,?)";

        foreach($data as $slotId) {
            if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "ii",
                $param_patientId,
                $param_slotId,
                );
                // Set parameters
                $param_patientId = $patientId;
                $param_slotId = $slotId;
       
                if(mysqli_stmt_execute($stmt)){
                    echo "Record Inserted";
                }
        }
        else{echo "Update Failed";}
    }  
}


function UpdateDistancePreference($patientId, $distance, $link) {
    $sql = "CALL UpdateDistancePreference(?, ?);";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $param_patientId,
            $param_distance 
            );
            // Set parameters
            $param_patientId = $patientId;
            $param_distance = $distance;
   
            if(mysqli_stmt_execute($stmt)){
                echo "Success";
            }
    }
    else{echo "Update Failed";}
}   

function getPatientMedicalHistory($patientId, $link){

    $result = NULL;
    $sql = "CALL GetPatientMedicalHistory(?);";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $param_patientId,
            );
            // Set parameters
            $param_patientId= $patientId;
    
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
            }
    }  
    return $result;     
}
    
function uploadMedicalHistory($patientId, $data, $link) {
    $sql = "DELETE FROM PatientMedHistory WHERE patientId =?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $param_patientId,
            );
            // Set parameters
            $param_patientId = $patientId;      
            if(mysqli_stmt_execute($stmt)){
                echo "Deleted Records";
            }
    }

    $sql = "INSERT INTO PatientMedHistory(patientId, medCondition) VALUES (?,?)";

    foreach($data as $medCondition) {
        if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "is",
            $param_patientId,
            $param_medCondition,
            );
            // Set parameters
            $param_patientId = $patientId;
            $param_medCondition = $medCondition;
   
            if(mysqli_stmt_execute($stmt)){
                echo "Record Inserted";
            }
        }
    else{echo "Update Failed";}
    }  
}   

   ?>
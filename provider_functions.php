<?php
require_once "config.php";

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'update':
            updateproviderDetail($_POST['providerId'], $_POST['address'], $_POST['email'],  $_POST['phone'], $link);
            break;
        case 'password':
            updatePassword($_POST['providerId'], $_POST['password'], $link);
            break;
        case 'status_update':
            updateStatus($_POST['patientId'], $_POST['appointmentId'], $_POST['selected'], $link);
            break;
    }
}

function getAppt($link, $condition, $providerId) {
        // Prepare an insert statement
       $sql = "CALL ProviderAppointment (?, ?)";
       $result = NULL;

        if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param(
        $stmt,
        "is",
        $param_providerId,
        $param_cond,
        );
        // Set parameters
        $param_providerId = $providerId ;
        $param_cond = $condition ;
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
        }

        // Close statement
        mysqli_stmt_close($stmt);
        }
        // Close connection
        mysqli_close($link);
      
        return $result;
    }

    function addApp($link, $providerId, $date,$startTime){
        $date = trim($_POST["appointmentDate"]);
        $startTime = trim($_POST["startTime"]);
 
        // Prepare an insert statement
        $sql = "INSERT INTO ProviderAppointment (providerId, date, startTime
                                ) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param(
                $stmt,
                "iss",
                $param_providerId,
                $param_date,
                $param_startTime,
            );
            // Set parameters
            $param_providerId = $providerId;
            $param_date = $date ;
            $param_startTime = $startTime;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
  
            } else {
                echo "Oops! Something went wrong. Please try again later. after validate success";
            }
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Close connection
            mysqli_close($link);
        }
    }


    function getProviderDetail($providerId, $link) {
        $result = NULL;
        $sql = "CALL getUserInfo(?, ?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "is",
                $param_providerId,
                $param_type
                );
                // Set parameters
                $param_providerId= $providerId;
                $param_type= "provider";
        
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                }
        }
        else{echo "Failed";}
        
        return $result;     
    }

    function updateProviderDetail($providerId, $providerAddress, $providerEmail,  $providerPhone, $link) {
        $sql = "CALL updateproviderProfile(?, ?, ?, ?, ?, ?);";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Get Longitude and Latitude from providerAddress using Google Maps API
            $api_key = 'AIzaSyA3mM2cTa1pPBc73_wsR2YEkpEb-W45b8k';
            $address = str_replace(" ", "+", $providerAddress);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=$api_key");
            $json = json_decode($json);
            $providerLatitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $providerLongitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

            mysqli_stmt_bind_param(
                $stmt,
                "isssdd",
                $param_providerId,
                $param_providerAddress,
                $param_providerEmail,
                $param_providerPhone,
                $param_providerLongitude,
                $param_providerLatitude
                );
                // Set parameters
                $param_providerId = $providerId;
                $param_providerAddress = $providerAddress;
                $param_providerEmail = $providerEmail;
                $param_providerPhone = $providerPhone;
                $param_providerLongitude = $providerLongitude ;
                $param_providerLatitude =  $providerLatitude;

                if(mysqli_stmt_execute($stmt)){
                    echo "Success";
                }
        }
        else{echo "Update Failed";}
    }

    function updatePassword($providerId, $providerPassword, $link) {
        $sql = "CALL updatePassword(?, ?, ?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "iss",
                $param_providerId,
                $param_type,
                $param_providerPassword,
                );
                // Set parameters
                $param_providerId = $providerId;
                $param_type = "provider";
                $param_providerPassword = password_hash($providerPassword, PASSWORD_DEFAULT);
       
                if(mysqli_stmt_execute($stmt)){
                    echo "Success";
                }
        }
        else{echo "Update Failed";}
    }  

    function updateStatus($patientId, $apptId, $status, $link) {
        $sql = "CALL UpdateApptStatus(?, ?, ?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "iis",
                $param_patientId,
                $param_apptId,
                $param_status,
                );
                // Set parameters
                $param_patientId = $patientId;
                $param_apptId = $apptId;
                $param_status = $status;
       
                if(mysqli_stmt_execute($stmt)){
                    echo "Success";
                }
                else{echo "Update Failed";}
        }
        else{echo "Update Failed";}
    }  


function getCount($providerId, $type, $link){
    $result = NULL;
    $sql = "CALL getCount(?, ?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "is",
                $param_providerId,
                $param_type 
                );
                // Set parameters
            $param_providerId = $providerId;
            $param_type = $type;
            echo "here";
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
            }
        }
        return $result;
    }
?>
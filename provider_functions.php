<?php 
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
            $param_providerId = $_SESSION["providerId"] ;
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

    ?>
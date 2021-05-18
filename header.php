<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<title>Covid Vaccine Portal</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
	 	
	<!-- styles for this template-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" crossorigin="anonymous">
	<link href="css.min.css" rel="stylesheet">
	<!-- Bootstrap Date-Picker Plugin -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" /> 
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>   
	

	<style>
        body {
			color: black;
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
            align-self: center;
        }

        label {
            font: 15px sans-serif;
        }
    </style>

</head>

<?php
// Initialize the session
session_start();
?>

<body>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

	<nav class="navbar navbar-dark" style="background-color:#3c4b64!important;">
		<div class="container-fluid" style="display: inline-table;">
			<i class="fas fa-hand-holding-medical fa-3x" style="color: white; margin-right: 5px;"></i>
			<a class="navbar-brand" style="font:28px sans-serif">COVID VACCINE PORTAL</a>
			<?php
			if (!isset($_SESSION['loggedin'])) {
			?>	<div style="display: flex; float: inline-end;margin-right: 60px;">
				<ul class="navbar-nav ms-auto flex" style="flex-direction: row;">
					<li class="nav-item">
						<a href="index.php" class="nav-link m-2 menu-item nav-active">Log In</a>
					</li>
				</ul>
				<ul class="navbar-nav ms-auto flex" style="flex-direction: row;">


					<li class="nav-item dropdown" style="margin-top: 8px;margin-left: 8px;">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" >
					Register
					</a>
					<div class="dropdown-menu dropdown-menu-right" style="position:absolute; background-color:#3c4b64!important;">
					<a class="dropdown-item" style="color:white;" href="register_patient.php">Patient Registration</a>
					<a class="dropdown-item" style="color:white;" href="register_provider.php">Provider Registration</a>

					</div>
					</li>
				</ul>
		 </div>

			<?php
			} else {
			?>
			<div style="display: flex; float: inline-end;margin-right: 60px;">
			<ul class="navbar-nav ms-auto flex" style="flex-direction: row;">
					<li class="nav-item">
						<a href="logout.php" class="nav-link m-2 menu-item nav-active">Log Out</a>
					</li>
				</ul>

			<?php
			}
			?>
			</div>
			</div>
	</nav>


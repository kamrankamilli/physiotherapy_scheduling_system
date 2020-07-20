<?php
	
	require_once "session.php";
	require_once "dbconnect.php";
	
	$date = $start_time = $finish_time = $appointment = "";
	$date_err = $start_time_err = $finish_time_err = $appointment_err = $over_time_err = "";
	$appointmentCount = 0;
	$percentAmount = 60;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$input_date = trim($_POST["date"]);
		if (empty($input_date)) {
			$date_err = "Please enter a date";
		} else {
			$date = $input_date;
		}
		if (empty(trim($_POST["starttime"]))) {
			$start_time_err = "Please enter start time";
		} else {
			$start_time = $_POST["starttime"];
		}
		if (empty(trim($_POST["finishtime"]))) {
			$finish_time_err = "Please enter finish time";
		} else {
			$finish_time = $_POST["finishtime"];
		}

$timeCheck1 = new DateTime($finish_time);
$timeCheck2 = new DateTime($start_time);
$intervalTime = $timeCheck1->diff($timeCheck2);
$timeDifference = ((intval($intervalTime->format('%h'))*60) + (intval($intervalTime->format('%i'))));

		if ($timeDifference >=91) {
			$over_time_err = "Please take under 90 minutes appointment";
		}

		$day = strtolower(strftime("%A", strtotime($date)));
	
		if (empty($date_err) && empty($start_time_err) && empty($finish_time_err) && empty($over_time_err)) {
	
			$sql = "SELECT d.national_id FROM days_of_the_week d, working_hours w WHERE d.national_id = w.national_id AND $day = 1 AND '$start_time' BETWEEN start_time AND finish_time AND '$finish_time' BETWEEN start_time AND finish_time";
	        // print_r($sql);
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$array = [];
			foreach ($stmt->get_result() as $row) {
				$array[] = $row['national_id'];
			}
			print_r($array);
			if (empty($array)) {
				header("location:unavailable.php");
			} else {
				for ($i = 0; $i < count($array); $i++) {
					$sql_2 = "SELECT physiotherapist_national_id FROM appointment WHERE physiotherapist_national_id = $array[$i] AND date = '$date' AND ('$start_time' BETWEEN start_time AND finish_time OR '$finish_time' BETWEEN start_time AND finish_time)";
					$res = mysqli_query($mysqli, $sql_2);
					if (mysqli_num_rows($res) > 0) {
						header("location: exists.php");
					} else {
					
					//sql baslangıc
					
					$sqlappointmentCount = "SELECT COUNT(id) as id FROM appointment where date = '$date'";
					$res = mysqli_query($mysqli, $sqlappointmentCount);
					if (mysqli_num_rows($res) > 0) {
						while ($row = $res->fetch_array()) {
							$appointmentCount = $row['id'];
						}
					}
					
					if($appointmentCount <=3){
						$percentAmount = 60;
					}else if($appointmentCount >3 && $appointmentCount <=6){
						$percentAmount = 50;
					}else if($appointmentCount >6 && $appointmentCount <=9){
						$percentAmount = 40;
					}else{
						$percentAmount = 30;
					}
					//sql bitis
					
					$time1 = new DateTime($finish_time);
					$time2 = new DateTime($start_time);
					$interval = $time1->diff($time2);
					$percentage = round(((intval($interval->format('%h'))*60) + (intval($interval->format('%i'))))*$percentAmount/100,0);
					
					
					$sqlCheckEntry = "SELECT * FROM appointment WHERE machine_duration = '$percentage'";
				if($res = $mysqli->query($sqlCheckEntry)){
					if($res->num_rows > 0){
						while ($row = $res->fetch_array())
						{
							$percentage += 1;
						}
					}
				}

						$sql_3 = "INSERT INTO appointment(patient_national_id,physiotherapist_national_id,start_time,finish_time,date,machine_duration)
						VALUES($user_nationalId,$array[$i],'$start_time','$finish_time','$date','$percentage')";
						$stmt_3 = $mysqli->prepare($sql_3);
						if ($stmt_3->execute()) {
							header("location: success.php");
						} else {
							header("location:error.php");
						}
						break;
					}
				}
			}
		}
	}
	$sql_2 = "SELECT p.first_name,p.last_name, p.email, p.phone_number, p.birth_date, p.gender, ap.start_time, ap.finish_time,ap.date,ap.machine_duration FROM physiotherapist p, appointment ap WHERE p.national_id=ap.physiotherapist_national_id AND ap.patient_national_id = $user_nationalId";
	$res_2 = mysqli_query($mysqli, $sql_2);
	if ($res_2->num_rows > 0) {
	    //$row_2 = mysqli_fetch_array($res_2,MYSQLI_ASSOC);
		while ($row = $res_2->fetch_row()) {
	
			$p_fname = $row[0];
			$p_lname = $row[1];
			$p_email = $row[2];
			$p_phoneNumber = $row[3];
			$p_birthDate = $row[4];
			$p_gender = $row[5];
			$p_start_time = $row[6];
			$p_finish_time = $row[7];
			$p_date = $row[8];
			$p_mtime = $row[9];
			$appointment_err_message = "";
		}
		function getAge($p_birthDate)
		{
			$p_birthDate = date('Ymd', strtotime($p_birthDate));
			$diff = date('Ymd') - $p_birthDate;
			return substr($diff, 0, -4);
		}
	
		$p_birthDate = getAge($p_birthDate);
	} else {
		$p_fname = "";
		$p_lname = "";
		$p_email = "";
		$p_phoneNumber = "";
		$p_birthDate = "";
		$p_gender = "";
		$p_start_time = "";
		$p_finish_time = "";
		$p_date = "";
		$p_mtime = "";
		$appointment_err_message = "You have no any appointment.";
	}
	
	
	
	?>
	
	<!DOCTYPE html>
	<html lang="en">
	
	<head>
		<meta charset="UTF-8" />
		<meta name="description" content="Physiotherapy Appointment" />
		<meta name="keywords" content="Psyiotherapy, Appointment, Hospital, Doctor, Patient" />
		<meta name="author" content="Kamran Kamilli, Dogacan Unver, Onat Basaran, Dogukan Demircin" />
		<!--<meta http-equiv="refresh" content="60">-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" />
		<link rel="icon" href="Images/P-Logo-Design.jpg" type="image/png" />
		<link rel="stylesheet" type="text/css" href="welcome.css">
		<title>Welcome</title>
	</head>
	<style>
		a {
			text-decoration: none;
			color: darkred;
			font-weight: bold;
			font-size: 20px;
		}
	</style>
	
	<body>
		<nav>
			<div class="container-fluid">
				<div class="row">
					<div class="col">
						<div class="sidebar" id="sidebar">
							<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
							<div id="nav">
								<a href="#form" onclick="closeNav()"><i class="far fa-id-card"></i> Profile</a>
								<a href="#form2" onclick="closeNav()"><i class="far fa-calendar-alt"></i> Appointment</a>
							</div>
							<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<div id="welcome">
			<div id="header">
				<button class="openbtn" onclick="openNav()">☰</button>
				<h1>Welcome <?php echo $user_fname; ?></h1>
			</div>
				<center><h1><?php echo $appointment_err_message?></h1></center>

			<div class="container emp-profile" id="form" style="display: none;">
				<form method="POST">
					<div class="container">
						<div class="row">
							<div class="col-md-4">
								<div class="profile-img">
									<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" />
									<div class="file btn btn-lg btn-primary">
										Change Photo
										<input type="file" name="file" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="profile-head">
									<h3>
										<?php echo $user_fname; ?> <?php echo $user_lname; ?>
									</h3>
									<ul class="nav nav-tabs" id="myTab" role="tablist" style="bottom: 0px; position:absolute;">
										<li class="nav-item">
											<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Active Appointment</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="col-md-2">
								<input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile" />
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-6">
								<div class="tab-content profile-tab" id="myTabContent">
									<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
										<div class="row">
											<div class="col-md-6">
												<label>National ID</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_nationalId; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Name</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_fname; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Lastname</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_lname; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Email</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_email; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Phone</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_phoneNumber; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Birth Date</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_birthDate; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Height</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_height; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Weight</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_weight; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Gender</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $user_gender; ?></p>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
										<div class="row">
											<div class="col-md-6">
												<label>Name</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_fname; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Last Name</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_lname; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Email</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_email; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Phone Number</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_phoneNumber; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Gender</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_gender; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Age</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_birthDate; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Start Time</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_start_time; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Finish Time</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_finish_time; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Date</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_date; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Machine Time</label>
											</div>
											<div class="col-md-6">
												<p><?php echo $p_mtime; ?> minutes</p>
											</div>
										</div>
										<div class="row">
											<div class="col offset-6">
												<?php
												$sql = "SELECT MAX(id) AS id FROM appointment WHERE patient_national_id = $user_nationalId";
												if ($res = $mysqli->query($sql)) {
													if ($res->num_rows > 0) {
														while ($row = $res->fetch_array()) {
															echo "<a href='cancelapp.php?id=" . $row['id'] . "' title='Cancel Appointment' data-toggle='tooltip'>Cancel</a>";
														}
														$res->free();
													} else {
														echo "<p class='lead'><em>No records were found.</em></p>";
													}
												} else {
													echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
												}
												$mysqli->close();
												?>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
	
			<div class="container emp-profile" id="form2" style="display: none;">
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<div class="container">
						<div class="row">
							<div class="col-sm-5">
								<div class="form-data">
									<div class="form-head">
										<h2>Appointment Request</h2>
										<p>Required fields*</p>
									</div>
									<div class="form-body">
										<div class="row form-row <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
											<label for="Date" class="col control-label">Date*</label>
											<div class="col" id="timerange">
												<input type="date" name="date" id="date" min="9/10/2018">
											</div>
											<span id="spnError" class="error col" style="color: red;"><?php echo $date_err; ?></span>
										</div>
										<div class="row form-row <?php echo (!empty($start_time_err)) ? 'has-error' : ''; ?>">
											<label for="timerange" class="col control-label">Select start time*</label>
											<div class="col" id="timerange">
												<input type="time" name="starttime" id="starttime" value="<?php echo $start_time; ?>">
											</div>
											<span id="spnError" class="error col" style="color: red;"><?php echo $start_time_err; ?></span>
										</div>
										<div class="row form-row <?php echo (!empty($finish_time_err)) ? 'has-error' : ''; ?>">
											<label for="timerange" class="col control-label">Select finish time*</label>
											<div class="col" id="timerange">
												<input type="time" name="finishtime" id="finishtime" value="<?php echo $finish_time; ?>">
											</div>
											<span id="spnError" class="error col" style="color: red;"><?php echo $finish_time_err; ?></span>
											<span id="spnError" class="error col" style="color: red;"><?php echo $over_time_err; ?></span>
										</div>
										<div class="row form-row">
											<button class="btn btn-success btn-appointment" type="submit">
												Sumbit
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<footer>
			<div class="footer-copyright text-center py-3">
				© 2020 Copyright Bahcesehir University - Capstone Project_1081
			</div>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<script src="sidebar.js"></script>
		<script>
			$("#nav a").click(function(e) {
				e.preventDefault();
				$("#form").hide();
				var toShow = $(this).attr('href');
				$(toShow).show();
	
			});
			$("#nav a").click(function(e) {
				e.preventDefault();
				$("#form2").hide();
				var toShow = $(this).attr('href');
				$(toShow).show();
	
			});
		</script>
		<script>
			$(function() {
				var dtToday = new Date();
	
				var month = dtToday.getMonth() + 1;
				var day = dtToday.getDate();
				var year = dtToday.getFullYear();
				if (month < 10)
					month = '0' + month.toString();
				if (day < 10)
					day = '0' + day.toString();
	
				var maxDate = year + '-' + month + '-' + day;
				$('#date').attr('min', maxDate);
			});
		</script>
	</body>
	
	</html>
	
	<!--https://getbootstrap.com/docs/4.0/examples/dashboard/#-->
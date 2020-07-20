<?php
require_once "dbconnect.php";


$national_id = $first_name = $last_name = $email = $password = $confirm_password = $birth_date = $phone_number = $gender = $days = $start_time = $end_time = "";
$national_id_err = $first_name_err = $last_name_err = $email_err = $password_err = $confirm_password_err = $birth_date_err = $phone_number_err = $gender_err = $days_err = $start_time_err = $end_time_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$input_national_id = trim($_POST["nationalid"]);
	if (empty($input_national_id)) {
		$national_id_err = "Please enter your national ID";
	} elseif (!is_numeric($input_national_id)) {
		$national_id_err = "National ID should be numbers only";
	} elseif (strlen($input_national_id) != 11) {
		$national_id_err = "National ID must contain 11 digits";
	} else {

		$sql = "SELECT * FROM physiotherapist WHERE national_id = '$input_national_id'";
		$res = mysqli_query($mysqli, $sql);
		if (mysqli_num_rows($res) > 0) {
			$national_id_err = "National ID  already registered";
		} else {
			$national_id = $input_national_id;
		}
	}

	$input_first_name = trim($_POST["firstname"]);
	if (empty($input_first_name)) {
		$first_name_err = "Please enter your first name";
	} elseif (!preg_match("/^[a-zA-Z ]*$/", $input_first_name)) {
		$first_name_err = "First name must contain letters only";
	} else {
		$first_name = $input_first_name;
	}

	$input_last_name = trim($_POST["lastname"]);
	if (empty($input_last_name)) {
		$last_name_err = "Please enter your last name";
	} elseif (!preg_match("/^[a-zA-Z ]*$/", $input_last_name)) {
		$last_name_err = "Last name must contain letters only";
	} else {
		$last_name = $input_last_name;
	}

	$input_email = trim($_POST["email"]);
	if (empty($input_email)) {
		$email_err = "Please enter your email";
	} elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
		$email_err = "Invalid email format";
	} else {
		$sql = "SELECT * FROM physiotherapist WHERE email = '$input_email'";
		$res = mysqli_query($mysqli, $sql);
		if (mysqli_num_rows($res) > 0) {
			$email_err = "Email is already registered";
		} else {
			$email = $input_email;
		}
	}

	$input_password = trim($_POST["password"]);
	$input_confirm_password = trim($_POST["confirm_password"]);
	if (!empty($input_password) && ($input_password == $input_confirm_password)) {
		if (strlen($input_password) < 6) {
			$password_err = "Password must be at least 6 characters long";
		} elseif (!preg_match("#[0-9]+#", $input_password)) {
			$password_err = "Password must contain at least 1 number";
		} elseif (!preg_match("#[A-Z]+#", $input_password)) {
			$password_err = "Password must contain at least 1 uppercase letter";
		} elseif (!preg_match("#[a-z]+#", $input_password)) {
			$password_err = "Password must contain at least 1 lowwercase letter";
		} else {
			$password = password_hash($input_password, PASSWORD_BCRYPT);
		}
	} elseif (!empty($input_password)) {
		$confirm_password_err = "Passwords do not match!";
	} else {
		$password_err = "Please create a password";
	}

	$input_birth_date = trim($_POST["birthDate"]);
	if (empty($input_birth_date)) {
		$birth_date_err = "Please enter your birth date";
	} else {
		$birth_date = $input_birth_date;
	}

	$input_phone_number = trim($_POST["phoneNumber"]);
	if (empty($input_phone_number)) {
		$phone_number_err = "Please enter your phone number";
	} elseif (!is_numeric($input_phone_number)) {
		$phone_number_err = "Phone number must be digits only";
	} else {
		$sql = "SELECT * FROM physiotherapist WHERE phone_number = '$input_phone_number'";
		$res = mysqli_query($mysqli, $sql);
		if (mysqli_num_rows($res) > 0) {
			$phone_number_err = "Phone number is already registered";
		} else {
			$phone_number = $input_phone_number;
		}
	}

	if (!isset($_POST['gender'])) {
		$gender_err = "Please your gender";
	} else {
		$gender = $_POST['gender'];
	}

	if (!empty($_POST['day'])) {
		$days = $_POST['day'];
		$monday = isset($days[0]) ? 1 : 0;
		$tuesday = isset($days[1]) ? 1 : 0;
		$wednesday = isset($days[2]) ? 1 : 0;
		$thursday = isset($days[3]) ? 1 : 0;
		$friday = isset($days[4]) ? 1 : 0;
	} else {
		$days_err = "At least one day must be selected";
	}

	if (empty($_POST["starttime"])) {
		$start_time_err = "Please enter work start time";
	} else {
		$start_time = $_POST["starttime"];
	}
	if (empty($_POST["endtime"])) {
		$end_time_err = "Please enter work end time";
	} else {
		$end_time = $_POST["endtime"];
	}
	if (empty($national_id_err) && empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($birth_date_err) && empty($phone_number_err) && empty($start_time_err) && empty($end_time_err) && empty($gender_err) && empty($days_err)) {
		$sql_1 = "INSERT INTO physiotherapist (national_id, first_name, last_name, email, password, birth_date, phone_number, gender) VALUES (?,?,?,?,?,?,?,?)";
		$sql_2 = "INSERT INTO working_hours (national_id, start_time, finish_time) VALUES (?,?,?)";
		$sql_3 = "INSERT INTO days_of_the_week (national_id, monday, tuesday, wednesday, thursday, friday) VALUES (?,?,?,?,?,?)";

		if (($stmt_1 = $mysqli->prepare($sql_1)) && ($stmt_2 = $mysqli->prepare($sql_2)) && ($stmt_3 = $mysqli->prepare($sql_3))) {
			$stmt_1->bind_param("isssssis", $pnational_id, $pfirst_name, $plast_name, $pemail, $ppassword, $pbirth_date, $pphone_number, $pgender);
			$pnational_id = $national_id;
			$pfirst_name = $first_name;
			$plast_name = $last_name;
			$pemail = $email;
			$ppassword = $password;
			$pbirth_date = $birth_date;
			$pphone_number = $phone_number;
			$pgender = $gender;


			$stmt_2->bind_param("iss", $pnational_id, $pstart_time, $pend_time);
			$pnational_id = $national_id;
			$pstart_time = $start_time;
			$pend_time = $end_time;


			$stmt_3->bind_param("iiiiii", $pnational_id, $pmonday, $ptuesday, $pwednesday, $pthursday, $pfriday);
			$pnational_id = $national_id;
			$pmonday = $monday;
			$ptuesday = $tuesday;
			$pwednesday = $wednesday;
			$pthursday = $thursday;
			$pfriday = $friday;

			if (($stmt_1->execute()) && ($stmt_2->execute()) && ($stmt_3->execute())) {
				header("location: admin.php");
				exit();
			} else {
				echo "Something went wrong";
			}
			$stmt_1->close();
			$stmt_2->close();
			$stmt_3->close();
		}
	}
	else
	{
    //echo "<style>#form{display: default !important;}</style>";
    //echo "<script src=\"not_reload.js\"><script>";
	}

	$mysqli->close();
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
	<link rel="stylesheet" type="text/css" href="admin.css" />
	<title>Admin</title>
</head>

<body>
	<nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<div class="sidebar" id="sidebar">
						<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
						<button class="dropdown-btn"><i class="fas fa-user-md"></i> Physiotherapist
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-container" id="nav">
							<a href="#form" onclick="closeNav()"><i class="fas fa-plus"></i> Add</a>
							<a href="#" onclick="closeNav()"><i class="fas fa-user-cog"></i> Update</a>
							<a href="#" onclick="closeNav()"><i class="fas fa-minus"></i> Delete</a>
						</div>
						<button class="dropdown-btn"><i class="fas fa-user"></i> Patient
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-container">
							<a href="?optimize=true" onclick="closeNav()"><i class="fas fa-plus"></i> Add</a>
							<a href="#" onclick="closeNav()"><i class="fas fa-user-cog"></i> Update</a>
							<a href="#" onclick="closeNav()"><i class="fas fa-minus"></i> Delete</a>
						</div>
						<a href="?optimize=true" onclick="closeNav()"><i class="fa fa-fw fa-wrench"></i> Optimize Machine</a>
						<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a>
					</div>
				</div>
			</div>
		</div>
	</nav>
	<div id="welcome">
		<div id="header">
			<button class="openbtn" onclick="openNav()">☰</button>
			<h1>Welcome Admin</h1>
		</div>
		<div id="form" class="toggle" style="display:none;">
			<form name="doctor_form" class="form-horizontal" id="doctor_form" role="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<h3>ADD PHYSIOTHERAPIST</h3>
				<br />
				<div class="form-group <?php echo (!empty($national_id_err)) ? 'has-error' : ''; ?>">
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<span class="help-block" style="color: red;">*Required fields</span>
						</div>
					</div>
					<label for="National ID" class="col-sm-3 control-label">National ID*</label>
					<div class="col-sm-9">
						<input type="text" name="nationalid" id="nationalid" value="<?php echo $national_id; ?>" class="form-control" placeholder="Enter your National ID" autofocus />
						<span class="help-block" style="color: red;"><?php echo $national_id_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
					<label for="firstname" class="col-sm-3 control-label">First Name*
					</label>
					<div class="col-sm-9">
						<input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $first_name; ?>" placeholder="Enter your first name" />
						<span class="help-block" style="color: red;"><?php echo $first_name_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
					<label for="lastName" class="col-sm-3 control-label">Last Name*
					</label>
					<div class="col-sm-9">
						<input type="text" id="lastname" name="lastname" value="<?php echo $last_name; ?>" placeholder="Enter your last name" class="form-control" />
						<span class="help-block" style="color: red;"><?php echo $last_name_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
					<label for="email" class="col-sm-3 control-label">E-mail* </label>
					<div class="col-sm-9">
						<input type="email" id="email" placeholder="Enter an e-mail address" class="form-control" name="email" value="<?php echo $email; ?>" />
						<span class="help-block" style="color: red;"><?php echo $email_err; ?></span>
					</div>
				</div>
				<div class="form-group  <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
					<label for="password" class="col-sm-3 control-label">Password*</label>
					<div class="col-sm-9">
						<input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="Password" class="form-control" />
						<span class="help-block" style="color: red;"><?php echo $password_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
					<label for="password" class="col-sm-3 control-label">Confirm Password*</label>
					<div class="col-sm-9">
						<input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="Confirm password" class="form-control" />
						<span class="help-block" style="color: red;"><?php echo $confirm_password_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($birth_date_err)) ? 'has-error' : ''; ?>">
					<label for="birthDate" class="col-sm-3 control-label">Date of Birth*</label>
					<div class="col-sm-9">
						<input type="date" id="birthDate" name="birthDate" class="form-control" value="<?php echo $birth_date; ?>" />
						<span class="help-block" style="color: red;"><?php echo $birth_date_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($phone_number_err)) ? 'has-error' : ''; ?>">
					<label for="phoneNumber" class="col-sm-3 control-label">Phone number*
					</label>
					<div class="col-sm-9">
						<input type="phoneNumber" id="phoneNumber" name="phoneNumber" value="<?php echo $phone_number; ?>" placeholder="Phone number" class="form-control" />
						<span class="help-block" style="color: red;"><?php echo $phone_number_err; ?></span>
					</div>
				</div>
				<div class="form-group <?php echo (!empty($days_err)) ? 'has-error' : ''; ?>">
					<label for="daysoftheweek" class="col control-label">Working days of the week*</label>
					<div class="col" id="weekDays-selector">
						<input type="checkbox" name="day[0]" id="monday" />
						<label for="monday">Monday</label>
						<input type="checkbox" name="day[1]" id="tuesday" />
						<label for="tuesday">Tuesday</label>
						<input type="checkbox" name="day[2]" id="wednesday" />
						<label for="wednesday">Wednesday</label>
						<input type="checkbox" name="day[3]" id="thursday" />
						<label for="thursday">Thursday</label>
						<input type="checkbox" name="day[4]" id="friday" />
						<label for="friday">Friday</label>
					</div>
					<span id="spnError" class="error col" style="color: red;"><?php echo $days_err; ?></span>
				</div>
				<div class="form-group <?php echo (!empty($start_time_err)) ? 'has-error' : ''; ?>">
					<label for="timerange" class="col control-label">Select work start time*</label>
					<div class="col" id="timerange">
						<input type="time" name="starttime" id="starttime">
					</div>
					<span id="spnError" class="error col" style="color: red;"><?php echo $start_time_err; ?></span>
				</div>
				<div class="form-group <?php echo (!empty($end_time_err)) ? 'has-error' : ''; ?>">
					<label for="timerange" class="col control-label">Select work finish time*</label>
					<div class="col" id="timerange">
						<input type="time" name="endtime" id="endtime">
					</div>
					<span id="spnError" class="error col" style="color: red;"><?php echo $end_time_err; ?></span>
				</div>
				<div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-3">Gender* </label>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-4">
								<label class="radio-inline"></label>
								<input type="radio" name="gender" id="femaleRadio" value="Female" />Female
							</div>
							<div class="col-sm-4">
								<label class="radio-inline"></label>
								<input type="radio" name="gender" id="maleRadio" value="Male" />Male
							</div>
						</div>
						<br>
						<span class="help-block" style="color: red;"><?php echo $gender_err; ?></span>
					</div>
				</div>
				<div class="form-group">
					<input type="submit" value="ADD" class="btn btn-primary btn-block">
				</div>
			</form>
		</div>

		<?php
		$display1 = "none";
		$display2 = "none";
		if(isset($_GET["optimize"]) == "true") {
			$display1 = "block";
		}else if(isset($_GET["date"]) <> ""){
			$display2 = "block";
		}
		?>
		<div id="optimize" class="toggle" style="display:<?php echo $display1; ?>;">
			<center>
			<h2>Patient Optimization page</h2><br><br>
			<form>
			<input type="date" name="date" class="btn-success"> <input type="submit" value="  Bring Patient List  " class="btn-success">
			</form>
			</center>
		</div>
		
		<div id="optimize" class="toggle" style="display:<?php echo $display2; ?>;">
			<center>
			<h2>Patient Optimization List</h2><br><br>
			<?php
			$myTotal=0;
			
										if(!empty($_GET["optimization"])) {
										$optimization = $_GET["optimization"];
										$myDeleteDate = $_GET["date"];
										
										if($optimization)
										{
										$sqltotal = "SELECT ap.machine_duration FROM appointment ap WHERE ap.date = '$myDeleteDate'";
										if ($res = $mysqli->query($sqltotal))
										{
											if ($res->num_rows > 0)
											{
												while ($row = $res->fetch_array())
												{
													$myTotal += $row['machine_duration'];
												}
												//machine time
												while($myTotal >=420)
												{
													$sqlgetmax = "SELECT MAX(machine_duration) AS machine_duration FROM appointment WHERE date = '$myDeleteDate'";
													$sqlgetmaxid = "SELECT MAX(id) AS id, machine_duration FROM appointment WHERE date = '$myDeleteDate'";
													if($res = $mysqli->query($sqlgetmax)){
														if($res->num_rows == 1){
															while ($row = $res->fetch_array())
															{
																$myTotal -= $row['machine_duration'];
															}
			                           				$sqldelete = "DELETE FROM appointment WHERE machine_duration= (SELECT MAX(machine_duration) FROM appointment WHERE date = '$myDeleteDate')";
													$deletenumber=$mysqli->prepare($sqldelete);
													$deletenumber->execute();
														}else if($res->num_rows > 1)
																{
																	if($res2 = $mysqli->query($sqlgetmaxid)){
																	while($row = $res2->fetch_array())
																{
																	$myTotal -= $row['machine_duration'];
																}
			                           				$sqldelete = "DELETE FROM appointment WHERE machine_duration= (SELECT MAX(machine_duration) FROM appointment WHERE date = '$myDeleteDate')";
													$deletenumber=$mysqli->prepare($sqldelete);
													$deletenumber->execute();
															}
														}
													}
												
												}
											}
											}
										}
										}
										?>
						<form>
			<input type="date" name="date" class="btn-success" value="<?php echo $_GET["date"]?>"> <input type="submit" value="  Bring Patient List  " class="btn-success">
			</form><br>

			                            <?php
										$total = 0;
										$myDate = $_GET["date"];
										
                            $sql = "SELECT ap.id, p.first_name, p.last_name, p.email, p.birth_date,p.phone_number,p.height,p.weight,p.gender, ap.start_time,ap.finish_time,ap.date, ap.machine_duration FROM patient p, appointment ap WHERE p.national_id= ap.patient_national_id AND ap.date = '$myDate'";
							
                            if ($res = $mysqli->query($sql)) {
                                if ($res->num_rows > 0) {
                                    echo "<table class='table' style='width:800px; color:#FFFFFF'>";
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th scope='col'></th>";
                                    echo "<th scope='col'><B>Name</B></th>";
                                    echo "<th scope='col'><B>Last Name</B></th>";
                                    echo "<th scope='col'><B>Start Time</B></th>";
                                    echo "<th scope='col'><B>Finish Time</B></th>";
                                    echo "<th scope='col'><B>Date</B></th>";
                                    echo "<th scope='col'><B>Machine Time</B></th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    $i = 1;
                                    while ($row = $res->fetch_array()) {

                                        echo "<tr>";
                                        echo "<td scope='row'>" . $i . "</td>";
                                        echo "<td scope='row'>" . $row['first_name'] . "</td>";
                                        echo "<td scope='row'>" . $row['last_name'] . "</td>";
                                        echo "<td scope='row'>" . $row['start_time'] . "</td>";
                                        echo "<td scope='row'>" . $row['finish_time'] . "</td>";
                                        echo "<td scope='row'>" . $row['date'] . "</td>";
                                        echo "<td scope='row'>" . $row['machine_duration'] . " minutes</td>";
                                        echo "</tr>";
										$total += $row['machine_duration'];
                                        $i++;
                                    }
                                        echo "<tr style='border:0px solid'>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'>Total:</td>";
                                        echo "<td scope='row'>" . $total . " minutes</td>";
                                        echo "</tr>";
                                        echo "<tr style='border:0px solid'>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'></td>";
                                        echo "<td scope='row'><form method='get' action='admin.php'><input type='hidden' name='date' value='" . $myDate . "'><input type='hidden' name='optimization' value='true'><input type='submit' class='btn-success' value='  Optimize List  '></form></td>";
                                        echo "</tr>";
                                    echo "</tbody>";
                                    echo "</table>";
                                    $res->free();
                                } else {
                                    echo "<p class='lead'><em>No records were found.</em></p>";
                                }
                            } else {
                                echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                            }
                            $mysqli->close();
                            ?>

			</center>
		</div>

	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="sidebar.js"></script>
	<script src="sidebar_dropdown.js"></script>
	<script>
		$("#nav a").click(function(e) {
			e.preventDefault();
			$(".toggle").hide();
			var toShow = $(this).attr('href');
			$(toShow).show();
		});
	</script>
</body>
</html>
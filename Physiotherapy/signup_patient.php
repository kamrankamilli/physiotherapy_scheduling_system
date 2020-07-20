<?php

require_once "dbconnect.php";

$national_id = $first_name = $last_name = $email = $password = $confirm_password = $birth_date = $phone_number = $height = $weight = $gender = "";
$national_id_err = $first_name_err = $last_name_err = $email_err = $password_err = $confirm_password_err = $birth_date_err = $phone_number_err = $height_err = $weight_err = $gender_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $input_national_id = trim($_POST["nationalid"]);
  if (empty($input_national_id)) {
    $national_id_err = "Please enter your national ID";
  } elseif (!is_numeric($input_national_id)) {
    $national_id_err = "National ID should be numbers only";
  } elseif (strlen($input_national_id) != 11) {
    $national_id_err = "National ID must contain 11 digits";
  } else {

    $sql = "SELECT * FROM patient WHERE national_id = '$input_national_id'";
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
    $sql = "SELECT * FROM patient WHERE email = '$input_email'";
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
    $sql = "SELECT * FROM patient WHERE phone_number = '$input_phone_number'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
      $phone_number_err = "Phone number is already registered";
    } else {
      $phone_number = $input_phone_number;
    }
  }



  if (!isset($_POST['gender'])) {
    $gender_err = "Please select your gender";
  } else {
    $gender = $_POST['gender'];
  }

  $input_height = trim($_POST['height']);
  if (empty($input_height)) {
    $height_err = "Please enter your height";
  } else {
    $height = $input_height;
  }

  $input_weight = trim($_POST['weight']);
  if (empty($input_weight)) {
    $weight_err = "Please enter your weight";
  } {
    $weight = $input_weight;
  }

  if (empty($national_id_err) && empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($birth_date_err) && empty($phone_number_err) && empty($height_err) && empty($weight_err) && empty($gender_err)) {
    $sql = "INSERT INTO patient (national_id, first_name, last_name, email, password, birth_date, phone_number, height, weight, gender) VALUES (?,?,?,?,?,?,?,?,?,?)";

    if ($stmt = $mysqli->prepare($sql)) {

      $stmt->bind_param("isssssiiis", $pnational_id, $pfirst_name, $plast_name, $pemail, $ppassword, $pbirth_date, $pphone_number, $pheight, $pweight, $pgender);

      $pnational_id = $national_id;
      $pfirst_name = $first_name;
      $plast_name = $last_name;
      $pemail = $email;
      $ppassword = $password;
      $pbirth_date = $birth_date;
      $pphone_number = $phone_number;
      $pheight = $height;
      $pweight = $weight;
      $pgender = $gender;

      if ($stmt->execute()) {
        header("location: login_patient.php");
        exit();
      } else {
        echo "Something went wrong. Please try again later";
      }
      $stmt->close();
    }
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
  <link rel="stylesheet" type="text/css" href="index.css" />
  <link rel="stylesheet" type="text/css" href="signup_patient.css" />
  <title>Sign Up</title>
</head>

<body>
  <header>
  <a class="head" href="index.php" style="text-decoration: none;"><h1>Physiotherapy</h1></a>
    <nav id="navbar">
      <div class="row">
        <div class="col-4 offset-4">
          <div class="navbar">
            <a id="account" href="login_option.php"><i class="fas fa-sign-in-alt"></i>Log In</a>
            <a class="active" id="account" href="signup_patient.php"><i class="fas fa-user-plus"></i>Sign Up</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="row">
      <div class="col">
        <div class="slides fade">
          <img src="Images/physiotherapy-mainphoto.jpg" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="slides fade">
          <img src="Images/physiotherapy-mainphoto_2.jpg" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="slides fade">
          <img src="Images/physiotherapy-mainphoto_3.webp" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="slides fade">
          <img src="Images/physiotherapy-mainphoto_4.jpg" />
        </div>
      </div>
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10090;</a>
    <a class="next" onclick="plusSlides(1)">&#10091;</a>

    <div style="text-align: center; background-color: gray;">
      <span class="dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
      <span class="dot" onclick="currentSlide(3)"></span>
      <span class="dot" onclick="currentSlide(4)"></span>
    </div>
  </header>
  <section class="content">
    <div class="container-fluid">
      <div class="container">
        <form id="patient_signup" name="patient_signup" class="form-horizontal" role="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <h3>Sign Up</h3>
          <br />
          <div class="form-group <?php echo (!empty($national_id_err)) ? 'has-error' : ''; ?>">
            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-3">
                <span class="help-block" style = "color: red;">*Required fields</span>
              </div>
            </div>
            <label for="National ID" class="col-sm-3 control-label">National ID*</label>
            <div class="col-sm-9">
              <input type="text" name="nationalid" id="nationalid" value="<?php echo $national_id; ?>" class="form-control" placeholder="Enter your National ID" autofocus />
              <span class="help-block" style = "color: red;"><?php echo $national_id_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
            <label for="firstname" class="col-sm-3 control-label">First Name*
            </label>
            <div class="col-sm-9">
              <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $first_name; ?>" placeholder="Enter your first name" />
              <span class="help-block" style = "color: red;"><?php echo $first_name_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
            <label for="lastName" class="col-sm-3 control-label">Last Name*
            </label>
            <div class="col-sm-9">
              <input type="text" id="lastname" name="lastname" value="<?php echo $last_name; ?>" placeholder="Enter your last name" class="form-control" />
              <span class="help-block" style = "color: red;"><?php echo $last_name_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label for="email" class="col-sm-3 control-label">E-mail* </label>
            <div class="col-sm-9">
              <input type="email" id="email" placeholder="Enter an e-mail address" class="form-control" name="email" value="<?php echo $email; ?>" />
              <span class="help-block" style = "color: red;"><?php echo $email_err; ?></span>
            </div>
          </div>
          <div class="form-group  <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label for="password" class="col-sm-3 control-label">Password*</label>
            <div class="col-sm-9">
              <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="Password" class="form-control" />
              <span class="help-block" style = "color: red;"><?php echo $password_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label for="password" class="col-sm-3 control-label">Confirm Password*</label>
            <div class="col-sm-9">
              <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="Confirm password" class="form-control" />
              <span class="help-block" style = "color: red;"><?php echo $confirm_password_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($birth_date_err)) ? 'has-error' : ''; ?>">
            <label for="birthDate" class="col-sm-3 control-label">Date of Birth*</label>
            <div class="col-sm-9">
              <input type="date" id="birthDate" name="birthDate" class="form-control" value="<?php echo $birth_date; ?>" />
              <span class="help-block" style = "color: red;"><?php echo $birth_date_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($phone_number_err)) ? 'has-error' : ''; ?>">
            <label for="phoneNumber" class="col-sm-3 control-label">Phone number*
            </label>
            <div class="col-sm-9">
              <input type="phoneNumber" id="phoneNumber" name="phoneNumber" value="<?php echo $phone_number; ?>" placeholder="Phone number" class="form-control" />
              <span class="help-block" style = "color: red;"><?php echo $phone_number_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($height_err)) ? 'has-error' : ''; ?>">
            <label for="Height" class="col-sm-3 control-label">Height*
            </label>
            <div class="col-sm-9">
              <input type="number" min="0" id="height" value="<?php echo $height; ?>" name="height" placeholder="Please write your height in centimetres" class="form-control" />
              <span class="help-block" style = "color: red;"><?php echo $height_err; ?></span>
            </div>
          </div>
          <div class="form-group <?php echo (!empty($weight_err)) ? 'has-error' : ''; ?>">
            <label for="weight" class="col-sm-3 control-label">Weight*
            </label>
            <div class="col-sm-9">
              <input type="number" min="0" id="weight" value="<?php echo $weight; ?>" name="weight" placeholder="Please write your weight in kilograms" class="form-control" />
              <span class="help-block" style = "color: red;"><?php echo $weight_err; ?></span>
            </div>
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
              <span class="help-block" style = "color: red;"><?php echo $gender_err; ?></span>
            </div>
          </div>
          <button
              type="submit"
              name="submit"
              class="btn btn-primary btn-block"
              value="submit"
              id="submit"
            >
              Sign Up
            </button>
        </form>
      </div>
    </div>
  </section>

  <footer>
    <div class="footer-copyright text-center py-3">
      © 2020 Copyright Bahcesehir University - Capstone Project_1081
    </div>
  </footer>

  <script src="slideshow.js"></script>
  <script src="sticky_navbar.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="reset_scroll.js"></script>
  </script>
</body>

</html>
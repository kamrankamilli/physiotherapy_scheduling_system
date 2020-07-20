<?php

require_once "dbconnect.php";

session_start();

$email = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $input_email = mysqli_escape_string($mysqli, $_POST["email"]);
  $input_password = mysqli_escape_string($mysqli, $_POST["password"]);
  if (empty($input_email) || empty($input_password)) {
    $error = "Invalid email or password";
  } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
    $error = "Ivalid email format";
  } else {
    $email = $input_email;
    $password = $input_password;
    $sql = "SELECT *FROM physiotherapist WHERE email  = '$email'";
    $res = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    if (mysqli_num_rows($res) == 1) {
      $validPassword = password_verify($password, $row["password"]);
      if ($validPassword) {
        $_SESSION['name'] = $row["first_name"];
        $_SESSION['lastname'] = $row["last_name"];
        $_SESSION['email'] = $row["email"];
        $_SESSION['loggedin_time'] = time();
        header("location: physiotherapist.php");
      } else {
        $error = "Password incorrect";
      }
    } else {
      $error = "User does not exist";
    }
  }
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
  <title>Log In</title>
</head>

<body>
  <header>
  <a class="head" href="index.php" style="text-decoration: none;"><h1>Physiotherapy</h1></a>
    <nav id="navbar">
      <div class="row">
        <div class="col-4 offset-4">
          <div class="navbar">
            <a class="active" id="account" href="login_option.php"><i class="fas fa-sign-in-alt"></i>Log In</a>
            <a id="account" href="signup_patient.php"><i class="fas fa-user-plus"></i>Sign Up</a>
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
        <form name="doctor_login" class="form-horizontal" role="form"method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <h3>Log In</h3>
          <br />
          <div class="form-group <?php echo (!empty($national_id_err)) ? 'has-error' : ''; ?>">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter an email" />
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Enter a password" />
            </div>
            <span class="help-block"><?php echo $error; ?></span>
            <br>
            <br>
            <button type="submit" class="btn btn-primary">Log In</button>
          </div>
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
</body>

</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="description" content="Physiotherapy Appointment" />
    <meta
      name="keywords"
      content="Psyiotherapy, Appointment, Hospital, Doctor, Patient"
    />
    <meta
      name="author"
      content="Kamran Kamilli, Dogacan Unver, Onat Basaran, Dogukan Demircin"
    />
    <!--<meta http-equiv="refresh" content="60">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css"
    />
    <link rel="icon" href="Images/P-Logo-Design.jpg" type="image/png" />
    <link rel="stylesheet" type="text/css" href="index.css" />
    <title>Physiotherapy</title>
  </head>
  
  <body>
    <header>
    <a class="head" href="index.php" style="text-decoration: none;"><h1>Physiotherapy</h1></a>
      <nav id="navbar">
      <div class="row">
        <div class="col-4 offset-4">
          <div class="navbar">
            <a id="account" href="login_option.php"
              ><i class="fas fa-sign-in-alt"></i>Log In</a
            >
            <a id="account" href="signup_patient.php"
              ><i class="fas fa-user-plus"></i>Sign Up</a
            >
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
    <footer id="footer">
      <div class="footer-copyright text-center py-3">
        © 2020 Copyright Bahcesehir University - Capstone Project_1081
      </div>
    </footer>
    <script src="slideshow.js"></script>
    <script src="sticky_navbar.js"></script>
    <script src="arrow_hoover.js"></script>
    <script
      src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
      crossorigin="anonymous"
    ></script>
    <script src="reset_scroll.js"></script>
  </body>
</html>

<!--REFERENCES-->
<!--https://ottawahealthgroup.com/wp-content/uploads/2019/10/physiotherapy-0521-1280x500.jpg-->
<!--https://raynentherapies.ca/wp-content/uploads/2019/12/physiotherapy-1202-1280x500.jpg-->
<!--http://ml6iwecxpfxx.i.optimole.com/w:780/h:304/q:auto/https://newhorizonclinic.ca/wp-content/uploads/2019/11/physiotherapy-1219-1280x500.jpg-->
<!--https://phppot.com/php/php-user-registration-form/-->
<!--https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database-->
<!--https://bootsnipp.com/snippets/z8b1X-->
<!--https://bootsnipp.com/tags/registration?page=2-->
<!--https://www.guru99.com/php-forms-handling.html-->

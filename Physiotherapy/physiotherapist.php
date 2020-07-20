<?php

require_once "session.php";
require_once "dbconnect.php";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" />
    <link rel="icon" href="Images/P-Logo-Design.jpg" type="image/png" />
    <link rel="stylesheet" type="text/css" href="welcome.css">
    <title>Welcome</title>
    <style>
        a
        {
            text-decoration: none;
            color: darkred;
            font-weight: bold;
            font-size: 20px;
        }
        tr {
            line-height: 14px;
            overflow: hidden;
            white-space: nowrap;

        }
    </style>
</head>

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
                                            <p><?php echo $user_pemail; ?></p>
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
                                            <label>Gender</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $user_gender; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container" id="form2" style="display: none;">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-data">
                            <div class="form-head">
                                <h2>List of Appointments</h2>
                            </div>
                            <br>
                            <?php
                            $sql = "SELECT ap.id, p.first_name, p.last_name, p.email, p.birth_date,p.phone_number,p.height,p.weight,p.gender, ap.start_time,ap.finish_time,ap.date FROM patient p, appointment ap WHERE p.national_id= ap.patient_national_id AND ap.physiotherapist_national_id = $user_nationalId";
                            if ($res = $mysqli->query($sql)) {
                                if ($res->num_rows > 0) {
                                    echo "<table class='table table-bordered'>";
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th scope='col'></th>";
                                    echo "<th scope='col'>Name</th>";
                                    echo "<th scope='col'>Last Name</th>";
                                    echo "<th scope='col'>E-mail</th>";
                                    echo "<th scope='col'>Birth Date</th>";
                                    echo "<th scope='col'>Phone Number</th>";
                                    echo "<th scope='col'>Height</th>";
                                    echo "<th scope='col'>Weight</th>";
                                    echo "<th scope='col'>Gender</th>";
                                    echo "<th scope='col'>Start Time</th>";
                                    echo "<th scope='col'>Finish Time</th>";
                                    echo "<th scope='col'>Date</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    $i = 1;
                                    while ($row = $res->fetch_array()) {

                                        echo "<tr>";
                                        echo "<td scope='row'>" . $i . "</td>";
                                        echo "<td scope='row'>" . $row['first_name'] . "</td>";
                                        echo "<td scope='row'>" . $row['last_name'] . "</td>";
                                        echo "<td scope='row'>" . $row['email'] . "</td>";
                                        echo "<td scope='row'>" . $row['birth_date'] . "</td>";
                                        echo "<td scope='row'>" . $row['phone_number'] . "</td>";
                                        echo "<td scope='row'>" . $row['height'] . "</td>";
                                        echo "<td scope='row'>" . $row['weight'] . "</td>";
                                        echo "<td scope='row'>" . $row['gender'] . "</td>";
                                        echo "<td scope='row'>" . $row['start_time'] . "</td>";
                                        echo "<td scope='row'>" . $row['finish_time'] . "</td>";
                                        echo "<td scope='row'>" . $row['date'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='cancelapp.php?id=" . $row['id'] . "' title='Cancel Appointment' data-toggle='tooltip'>Cancel</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
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
    <script type="text/javascript">
        $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
    </script>
</body>

</html>
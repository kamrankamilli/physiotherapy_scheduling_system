<?php
require_once "dbconnect.php";
require_once "session.php";

if (isset($_POST["id"]) && !empty($_POST["id"])) {

    $sql = "DELETE FROM appointment WHERE id = ?";

    if ($stmt = $mysqli->prepare($sql)) {

        $stmt->bind_param("i", $param_id);


        $param_id = trim($_POST["id"]);


        if ($stmt->execute()) {
            if ($user_check == $user_pemail) {
                header("location: physiotherapist.php");
                exit();
            } else if ($user_check==$user_email) {
                header("location: patient.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $mysqli->close();
} else {

    if (empty(trim($_GET["id"]))) {

        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cancel Appointment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Cancel Appointment</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                            <p>Are you sure you want to cancel this appointment?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="physiotherapist.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
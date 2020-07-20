<?php
require_once "dbconnect.php";

session_start();

$user_check = $_SESSION['email'];
$sql = "SELECT national_id, first_name,last_name,email, birth_date,phone_number, height, weight, gender FROM patient WHERE email ='$user_check'";
$res = mysqli_query($mysqli, $sql);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
if (mysqli_num_rows($res) > 0) {

    $user_nationalId = $row['national_id'];
    $user_fname = $row['first_name'];
    $user_lname = $row['last_name'];
    $user_email = $row['email'];
    $user_birthDate = $row['birth_date'];
    $user_phoneNumber = $row['phone_number'];
    $user_height = $row['height'];
    $user_weight = $row['weight'];
    $user_gender = $row['gender'];
} else {
    $sql_2 = "SELECT national_id, first_name,last_name,email, birth_date,phone_number,gender FROM physiotherapist WHERE email = '$user_check'";
    $res_2 = mysqli_query($mysqli, $sql_2);
    $row = mysqli_fetch_array($res_2, MYSQLI_ASSOC);
    $user_nationalId = $row['national_id'];
    $user_fname = $row['first_name'];
    $user_lname = $row['last_name'];
    $user_pemail = $row['email'];
    $user_birthDate = $row['birth_date'];
    $user_phoneNumber = $row['phone_number'];
    $user_gender = $row['gender'];
}
if (!isset($_SESSION['email'])) {
    header("location: login_option.php");
    die();
}

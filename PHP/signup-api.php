<?php

include("db_info.php");

$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$dob_d = $_POST["dob_d"];
$dob_m = $_POST["dob_m"];
$dob_y = $_POST["dob_y"];
$email = $_POST["email"];
$password = $_POST["password"];
$picture = $_POST["picture"];
$timestamp = $_POST["timestamp"];

$query = $mysqli->prepare("INSERT INTO users(first_name, last_name, dob_d, dob_m, dob_y, email, password, picture, timestamp) VALUES (?,?,?,?,?,?,?,?,?)"); 
$query->bind_param("ssiiiss", $first_name , $last_name, $dob_d, $dob_m, $dob_y, $email, $password, $picture, $timestamp);


$country = $_POST["country"];
$city = $_POST["city"];
$street = $_POST["street"];



?>
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

$query1 = $mysqli->prepare("INSERT INTO users(first_name, last_name, dob_d, dob_m, dob_y, email, password, picture, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
$query1->bind_param("ssiiisssi", $first_name , $last_name, $dob_d, $dob_m, $dob_y, $email, $password, $picture, $timestamp);
$query1->execute();


$country = $_POST["country"];
$city = $_POST["city"];
$street = $_POST["street"];

$query2 = $mysqli->prepare("INSERT INTO addresses(country, city, street) VALUES (?, ?, ?)"); 
$query2->bind_param("sss", $country, $city, $street);
$query2->execute();

$address_id = "SELECT address_id FROM addresses WHERE address_id = LAST_INSERT_ID()";
$query3 = $mysqli->prepare("INSERT INTO addresses(address_id) VALUES (?)");
$query3->bind_param("i", $address_id);
$query3->execute();

$array_response = [];
$array_response["status"] = "Welcome to Facebook";

$json_response = json_encode($array_response);
echo $json_response;



?>
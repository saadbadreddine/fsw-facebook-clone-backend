<?php

include("db_info.php");

$email = $_POST["email"];
$password = $_POST["password"];

$query = $mysqli->prepare("SELECT first_name, last_name, dob_d, dob_m, dob_y, email, picture, addresses.country, addresses.city, 
                            addresses.street FROM users JOIN addresses ON  users.id = addresses.user_id WHERE email = ? AND password = ?"); 
$query->bind_param("ss", $email, $password);
$query->execute();

$array_response = [];
$array_response["status"] = $query;

$json_response = json_encode($array_response);
echo $json_response;
?>
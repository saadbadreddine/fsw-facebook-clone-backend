<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;

if(isset($_POST["email"])){
    $email = $mysqli->real_escape_string($_POST["email"]);
}else{
    die("Email not found");
}

if(isset($_POST["password"])){
    $password = $mysqli->real_escape_string($_POST["password"]);
    $password = hash("sha256", $password);
}else{
    die("Wrong Password");
}

$query = $mysqli->prepare("SELECT first_name, last_name, dob_d, dob_m, dob_y, email, picture, addresses.country, addresses.city, 
                            addresses.street FROM users JOIN addresses ON  users.id = addresses.user_id WHERE email = ? AND password = ?"); 
$query->bind_param("ss", $email, $password);
$query->execute();

$array_response = [];
$array_response["status"] = $query;

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
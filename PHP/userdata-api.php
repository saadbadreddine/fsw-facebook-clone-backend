<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$decoded_key = JWT::decode($jwt, new Key($key, 'HS256'));

if(isset($_POST[$decoded_key])){
    $user = $_POST[$decoded_key];
}else{
    die("User not found");
}

$query = $mysqli->prepare("SELECT first_name, last_name, dob_d, dob_m, dob_y, email, picture, addresses.country, addresses.city, 
                            addresses.street FROM users JOIN addresses ON  users.id = addresses.user_id WHERE id = ?"); 
$query->bind_param("i", $decoded_key);
$query->execute();

$array_response = [];
$array_response = ["status" => "Get user data", $query];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
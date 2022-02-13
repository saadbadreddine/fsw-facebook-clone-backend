<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$data = json_decode($json);

if(isset($data -> id)){
    $key = JWT::decode($jwt, new Key($key, 'HS256'));
}else{
    die("User not found");
}

$query = $mysqli->prepare("SELECT first_name, last_name, dob_d, dob_m, dob_y, email, picture, addresses.country, addresses.city, 
                            addresses.street FROM users JOIN addresses ON  users.id = addresses.user_id WHERE id = ?"); 
$query->bind_param("i", $key);
$query->execute();

$query->store_result;
$query->bind_result($data);
$query->fetch();

$array_response = [];
$array_response = ["status" => "Get user data","data" => $data];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
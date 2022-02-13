<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;

if(isset($_POST["email"])){
    $email = $mysqli->real_escape_string($_POST["email"]);
}else{
    die("Please Sign up");
}

if(isset($_POST["password"])){
    $password = $mysqli->real_escape_string($_POST["password"]);
    $password = hash("sha256", $password);
}else{
    die("Wrong Password");
}

$query = $mysqli->prepare("SELECT id FROM users WHERE email = ? And password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$query->store_result;
$num_rows = $query->num_rows;
$query->bind_result($id);
$query->fetch();

$payload = [
    "iss" => "localhost",
    "aud" => "localhost",
    "iat" => 1356999524,
    "nbf" => 1357000000,
    "data" => $id
];

$jwt = JWT::encode($payload, $key, 'HS256');

$array_response = [];

if($num_rows ==0){
    $array_response["status"] = "User not found, please sign up.";
}else{
    $array_response = ["status" => "Logged In", "token" => $jwt];
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$data = json_decode($json);
$email = $data -> email;
$password = $data -> password;

if(empty($data -> email)){
    die("Please Sign up");
}else{
    $email = $data -> email;
}

if(empty($data -> password)){
    die("Wrong Password");
}else{
    $password = $data -> password;
    $password = hash("sha256", $password);
    echo $password;
}

$query = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();
$query->store_result();
$num_rows = $query->num_rows;
$query->bind_result($id);
$query->fetch();

$array_response = [];

if($num_rows == 0){
    $array_response = ["status" => "User not found, please sign up."];
}else{
    $payload = [
        "iss" => "localhost",
        "aud" => "localhost",
        "iat" => 1356999524,
        "nbf" => 1357000000,
        "data" => $id
    ];
    $jwt = JWT::encode($payload, $key, 'HS256');
    $array_response = ["status" => "Logged In", "token" => $jwt];
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
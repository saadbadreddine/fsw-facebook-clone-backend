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
if(isset($data -> email)){
    $email = $data -> email;
}else{
    $emailErr = "Please enter an email";
    $array_response = ["status" => $emailErr];
    echo json_encode($array_response);
}

if(isset($data -> password)){
    $password = $data -> password;
    $password = hash("sha256", $password);
}else{
    $passwordErr = "Please enter a Password";
    $array_response = ["status" => $passwordErr];
    echo json_encode($array_response);
}

$query = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$query->store_result();
$num_rows = $query->num_rows;
$query->bind_result($id);
$query->fetch();

if($num_rows == 0){
    $array_response = ["status" => "User not found"];
}else{
    $payload = [
        "iss" => "localhost",
        "aud" => "localhost",
        "iat" => 1356999524,
        "nbf" => 1357000000,
        "id" => $id
    ];
    $jwt = JWT::encode($payload, $key, 'HS256');
    $array_response = ["status" => "Logged In", "token" => $jwt, "error" => false];
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
<?php

include("db_info.php");
require __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/*
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
*/
$email = "saad@saad.saad";
$password = "saadsaadsaad";

$query = $mysqli->prepare("SELECT id FROM users WHERE email = ? And password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$query->store_result();
$num_rows = $query->num_rows;
$query->bind_result($id);
$query->fetch();


$key = "mypassword";
$payload = array(
    "iss" => "localhost",
    "aud" => "localhost",
    "iat" => 1356999524,
    "nbf" => 1357000000,
    "data" => $id
);

$jwt = JWT::encode($payload, $key, 'HS256');
//$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

//print_r($decoded);
//$decoded_array = (array) $decoded;
JWT::$leeway = 60;
//$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

if($num_rows ==0){
    $array_response["status"] = "User not found, please sign up.";
}else{
    $array_response["status"] = "Logged In";
}

$array_response = [];
$json_response = json_encode($array_response);
echo $json_response;
echo $jwt;

$query->close();
$mysqli->close();

?>
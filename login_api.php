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

if(empty($data)){
   die("Error");
}
    
$query = $mysqli->prepare("SELECT id FROM users WHERE email = ? And password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$query->store_result();
$num_rows = $query->num_rows;
$query->bind_result($id);
$query->fetch();


//$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
//print_r($decoded);
//$decoded_array = (array) $decoded;
//JWT::$leeway = 60;
//$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

if($num_rows == 0){
    $array_response = ["status" => "User not found, please sign up."];
}else{
    $payload = array(
    "iss" => "localhost",
    "aud" => "localhost",
    "iat" => 1356999524,
    "nbf" => 1357000000,
    "id" => $id);
    $jwt = JWT::encode($payload, $key, 'HS256');
    $x = gettype($jwt);
    $array_response = ["status" => "Logged in", "token" => $jwt, "x" => $x];
}


echo json_encode($array_response);



$query->close();
$mysqli->close();

?>
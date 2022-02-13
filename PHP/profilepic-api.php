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

// Validate Picture

$picture = $data -> picture;

$query = $mysqli->prepare("UPDATE users  SET picture=? WHERE users.id = ?"); 
$query->bind_param("si", $picture, $key);
$query->execute();

$query->store_result;
$query->bind_result($image);
$query->fetch();

$array_response = [];
$array_response = ["status" => "Show Picture", "picture" => $image];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
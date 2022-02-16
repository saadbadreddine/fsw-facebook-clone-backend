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
    $id = $data -> id;
    $key = JWT::decode($id, new Key($key, 'HS256'));
    $key = $key -> data;
}else{
    $userErr = "User not found";
}

// Validate Picture

$picture = $data -> picture;

$query = $mysqli->prepare("UPDATE users  SET picture=? WHERE users.id = ?"); 
$query->bind_param("si", $picture, $key);
$query->execute();

$array_response = [];
$array_response = ["status" => "Profile Picture Updated"];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
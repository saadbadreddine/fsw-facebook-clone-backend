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
$sender_id  = $data -> sender;
$receiver_id = $data -> receiver;

$decoded_sender = JWT::decode($sender_id, new Key($key, 'HS256'));
$decoded_sender = $decoded_sender -> id;
$decoded_receiver = JWT::decode($receiver_id, new Key($key, 'HS256'));
$decoded_receiver = $decoded_receiver -> id;


$query = $mysqli -> prepare("DELETE FROM friendships WHERE (sender = ? AND receiver = ?) 
OR (sender = ? AND receiver = ?)");
$query->bind_param("iiii", $decoded_sender, $decoded_receiver, $decoded_receiver, $decoded_sender );
$query->execute();

$array_response = [];
$array_response["status"] = "Friend Removed";
$json_response = json_encode($array_response);
echo $json_response;

?>
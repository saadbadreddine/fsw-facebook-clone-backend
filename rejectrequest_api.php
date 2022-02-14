<?php

include "db_info.php";
include "authorization_api.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents("php://input");
$data = json_decode($json);
$array_response = [];

if (!empty($data->sender) && !empty($data->receiver)) {
  $sender_id = $data->sender;
  $receiver_id = $data->receiver;

  $decoded_sender = JWT::decode($sender_id, new Key($key, "HS256"));
  $decoded_sender = $decoded_sender->id;

  $query = $mysqli->prepare("DELETE FROM friendships WHERE sender = ? AND receiver = ?");
  $query->bind_param("ii", $receiver_id, $decoded_sender);
  $query->execute();

  $array_response["status"] = "Friend Request Rejected";
} else {
  $array_response["status"] = "Error";
}

$json_response = json_encode($array_response);
echo $json_response;
$query->close();
$mysqli->close();

?>

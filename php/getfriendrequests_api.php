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

if (isset($data->sender)) {
  $sender_id = $data->sender;

  $decoded_sender = JWT::decode($sender_id, new Key($key, "HS256"));
  $decoded_sender = $decoded_sender->id;

  $query = $mysqli->prepare("SELECT id, first_name, last_name, picture
  FROM users INNER JOIN friendships ON users.id = friendships.sender OR users.id = friendships.receiver
  WHERE friendships.receiver = ? AND friendships.accepted = 0 AND id != ?");
  $query->bind_param("ii", $decoded_sender, $decoded_sender);
  $query->execute();

  $array = $query->get_result();

  while ($data = $array->fetch_assoc()) {
    $array_response[] = $data;
  }
} else {
  $array_response["status"] = "Error";
}

$json_response = json_encode($array_response);
echo $json_response;
$query->close();
$mysqli->close();

?>

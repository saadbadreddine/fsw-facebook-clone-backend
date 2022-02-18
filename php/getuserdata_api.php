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

if (isset($data->sender)) {
  $sender_id = $data->sender;
  $decoded_sender = JWT::decode($sender_id, new Key($key, "HS256"));
  $decoded_sender = $decoded_sender->id;
} else {
  $userErr = "User not found";
  $array_response = ["status" => $userErr];
  $json_response = json_encode($array_response);
  echo $json_response;
}

$query = $mysqli->prepare("SELECT first_name, last_name, dob, email, picture, addresses.country, addresses.city, 
                            addresses.street FROM users JOIN addresses ON  users.address_id = addresses.address_id WHERE id = ?");
$query->bind_param("i", $decoded_sender);
$query->execute();

$array = $query->get_result();

$array_response = [];
while ($data = $array->fetch_assoc()) {
  $array_response[] = $data;
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
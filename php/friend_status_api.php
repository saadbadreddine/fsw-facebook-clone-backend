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
  $decoded_sender = $decoded_sender->token;
} else {
  $userErr = "User not found";
  $array_response = ["status" => $userErr];
  $json_response = json_encode($array_response);
  echo $json_response;
}
$blocked = false;
$query = $mysqli->prepare("SELECT DISTINCT posts.post, posts.timestamp, posts.user_id, users.first_name, users.last_name, users.picture 
                            FROM posts JOIN users ON posts.user_id = users.id
                            JOIN friendships ON (posts.user_id = friendships.sender OR posts.user_id = friendships.receiver) 
                            WHERE(friendships.sender = ? OR friendships.receiver = ?)
                            AND friendships.accepted = 1 AND users.id NOT IN (SELECT blocks.receiver 
                            FROM blocks WHERE blocks.receiver = ? OR blocks.sender = ?) ORDER BY timestamp DESC;");

$query->bind_param("iiii", $decoded_sender, $decoded_sender, $decoded_sender, $decoded_sender);
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

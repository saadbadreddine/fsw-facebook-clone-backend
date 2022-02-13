<?php

include("db_info.php");
require __DIR__ . '/vendor/autoload.php';
include("authorization_api.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJhdWQiOiJsb2NhbGhvc3QiLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiZGF0YSI6Nn0.mJVKuGfsYWv60PTTD3apVB7PgSJKXj83PcmVBplnJj4";
$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

print_r($decoded);
$decoded_array = (array) $decoded;

$sender_id = 1;
$receiver_id = 2;
$accepted = false;

$query = $mysqli -> prepare("INSERT INTO friendships(sender, receiver, accepted) VALUES (?, ?, ?)"); 
$query->bind_param("iib", $sender_id, $receiver_id, $accepted);
$query->execute();

$array_response = [];
$array_response["status"] = "Friend Request Sent";
$json_response = json_encode($array_response);
echo $json_response;

?>
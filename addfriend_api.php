<?php

include("db_info.php");

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
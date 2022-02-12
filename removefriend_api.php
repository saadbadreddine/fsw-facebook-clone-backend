<?php

include("db_info.php");

$sender_id = 1;
$receiver_id = 2;

$query = $mysqli -> prepare("DELETE FROM friendships WHERE (sender = ? and receiver = ?) 
OR (sender = ? and receiver = ?)");
$query->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id );
$query->execute();

$array_response = [];
$array_response["status"] = "Friend Removed";
$json_response = json_encode($array_response);
echo $json_response;

?>
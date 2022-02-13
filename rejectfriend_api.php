<?php

include("db_info.php");

$sender_id = 1;
$receiver_id = 2;

$query = $mysqli -> prepare("DELETE FROM friendships WHERE sender = ? AND receiver = ?");
$query->bind_param("ii", $receiver_id, $sender_id);
$query->execute();

$array_response = [];
$array_response["status"] = "Friend Request Rejected";
$json_response = json_encode($array_response);
echo $json_response;

?>
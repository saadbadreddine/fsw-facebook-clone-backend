<?php

include("db_info.php");

include("db_info.php");

$sender_id = 1;
$receiver_id = 2;
$accepted = 1;

$query = $mysqli -> prepare("UPDATE friendships SET accepted = ? WHERE sender = ? AND receiver = ?");
$query->bind_param("iii", $accepted, $receiver_id, $sender_id);
$query->execute();

$array_response = [];
$array_response["status"] = "Friend Request Accepted";
$json_response = json_encode($array_response);
echo $json_response;

?>
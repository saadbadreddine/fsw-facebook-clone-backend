<?php

include("db_info.php");

$sender_id = 1;
$receiver_id = 2;

$unblock = true;

$query = $mysqli -> prepare("DELETE FROM blocks WHERE (sender = ? AND receiver = ?)"); 
$query->bind_param("ii", $sender_id, $receiver_id);
$query->execute();

$array_response = [];
$array_response["status"] = "User Unblocked";
$json_response = json_encode($array_response);
echo $json_response;

?>
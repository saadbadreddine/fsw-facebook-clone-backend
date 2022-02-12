<?php

include("db_info.php");

$sender_id = 1;
$receiver_id = 2;

$query = $mysqli -> prepare("INSERT INTO blocks(sender, receiver) VALUES (?, ?)"); 
$query->bind_param("ii", $sender_id, $receiver_id);
$query->execute();

$array_response = [];
$array_response["status"] = "User Blocked";
$json_response = json_encode($array_response);
echo $json_response;



?>
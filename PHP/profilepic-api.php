<?php

include("db_info.php");

$picture = $_POST["picture"];
$user_id = $_POST["id"];

$query = $mysqli->prepare("UPDATE users  SET picture=? WHERE users.id = ?"); 
$query->bind_param("si", $picture, $user_id);
$query->execute();

$array_response = [];
$array_response["status"] = $picture;

$json_response = json_encode($array_response);
echo $json_response;

?>
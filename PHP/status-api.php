<?php

include("db_info.php");

// Add a status(post)

$post = $_POST["post"];
$user_id = $_POST["user_id"];

$query = $mysqli->prepare("INSERT INTO posts(post, user_id) VALUES (?, ?)"); 
$query->bind_param("si", $post, $user_id);
$query->execute();

$array_response = [];
$array_response["status"] = $post;

$json_response = json_encode($array_response);
echo $json_response;

?>
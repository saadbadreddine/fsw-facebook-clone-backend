<?php

include("db_info.php");

$post = $_POST["post_id"];
$user = $_POST["user_id"];

$query = $mysqli->prepare("INSERT post_id, user_id INTO likes WHERE post_id = ? AND user_id = ?"); 
$query->bind_param("ii", $post, $user);
$query->execute();

$array_response = [];
$array_response["status"] = $query;

$json_response = json_encode($array_response);
echo $json_response;

?>
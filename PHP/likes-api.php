<?php

include("db_info.php");

$post = $_POST["post_id"];

$query = $mysqli->prepare("SELECT COUNT(post_id) FROM likes WHERE post_id = ?"); 
$query->bind_param("i", $post);
$query->execute();

$array_response = [];
$array_response["status"] = $query;

$json_response = json_encode($array_response);
echo $json_response;

?>
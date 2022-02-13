<?php

include("db_info.php");

$user_id = $_POST["id"];

$query = $mysqli->prepare("SELECT posts.post, posts.timestamp FROM posts WHERE posts.user_id = ?"); 
                           
$query->bind_param("i",$user_id);
$query->execute();

$array_response = [];
$array_response["status"] = $query;

$json_response = json_encode($array_response);
echo $json_response;

?>
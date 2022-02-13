<?php

include("db_info.php");

$post = $_POST["post"];
$user_id = $_POST["id"];
$blocked = false;
$query = $mysqli->prepare("SELECT posts.post, users.first_name, users.last_name 
                            FROM posts JOIN users ON posts.user_id = ?
                            JOIN friendships ON (friendships.sender = ?) OR (friendships.receiver = ?) 
                            WHERE friendships.blocked = ?"); 
                           
$query->bind_param("iiib",$user_id, $user_id, $user_id, $blocked);
$query->execute();

$array_response = [];
$array_response["status"] = $post;

$json_response = json_encode($array_response);
echo $json_response;

?>
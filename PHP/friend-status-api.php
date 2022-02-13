<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$decoded_key = JWT::decode($jwt, new Key($key, 'HS256'));

if(isset($_POST[$decoded_key])){
    $user_id = $_POST[$decoded_key];
}else{
    die("User not found");
}
$blocked = false;

$query = $mysqli->prepare("SELECT posts.post, posts.timestamp, users.id, users.first_name, users.last_name, users.picture 
                            FROM posts JOIN users ON posts.user_id = ?
                            JOIN friendships ON (friendships.sender = ? OR friendships.receiver = ?) 
                            AND friendships.accepted = 1 AND users.id NOT IN (SELECT blocks.sender, blocks.receiver 
                            FROM blocks WHERE blocks.sender = ? OR blocks.receiver = ?)"); 
                           
$query->bind_param("iiibb",$user_id, $user_id, $user_id, $blocked, $blocked);
$query->execute();

$array_response = [];
$array_response = ["status" => "List of posts", "posts" => $query];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
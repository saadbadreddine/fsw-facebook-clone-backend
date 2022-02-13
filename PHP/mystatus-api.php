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

$query = $mysqli->prepare("SELECT posts.post, posts.timestamp FROM posts WHERE posts.user_id = ?"); 
                           
$query->bind_param("i",$user_id);
$query->execute();

$array_response = [];
$array_response = ["status" => "User posts", "status" => $query];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
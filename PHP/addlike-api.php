<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$decoded_key = JWT::decode($jwt, new Key($key, 'HS256'));

if(isset($_POST["post_id"])){
    $post = $_POST["post_id"];
}else{
    die("Post not found");
}

if(isset($_POST[$decoded_key])){
    $user = $_POST[$decoded_key];
}else{
    die("User not found");
}

$query = $mysqli->prepare("INSERT post_id, user_id INTO likes WHERE post_id = ? AND user_id = ?"); 
$query->bind_param("ii", $post, $user);
$query->execute();

$array_response = [];
$array_response = ["status"=>"Like added", "post"=>$post];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
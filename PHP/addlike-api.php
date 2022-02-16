<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$data = json_decode($json);

if(isset($data -> post_id)){
    $post = $data -> post_id;
}else{
    $postErr = "Post not found";
}

if(isset($data -> user_id)){
    $user_id = $data -> user_id;
    $key = JWT::decode($user_id, new Key($key, 'HS256'));
    $key = $key -> data;
}else{
    $userErr = "User not found";
}

$query = $mysqli->prepare("INSERT INTO likes(post_id, user_id) VALUES (?, ?)"); 
$query->bind_param("ii", $post, $key);
$query->execute();


$array_response = [];
$array_response = ["status"=>"Like added"];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
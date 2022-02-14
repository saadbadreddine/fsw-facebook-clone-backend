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
$user_id = $data -> user_id;
if(isset($user_id)){
    $decoded_id = JWT::decode($user_id, new Key($key, 'HS256'));
    $decoded_id = $decoded_id -> data;
}else{
    die("User not found");
}

// Add a status(post)

if (empty($data -> post)) {
    die("Post is empty");
}else{
    $post = $data -> post;
}

$query = $mysqli->prepare("INSERT INTO posts(post, user_id) VALUES (?, ?)"); 
$query->bind_param("si", $post, $decoded_id);
$query->execute();

$array_response = array("status" => "Post added successfully", "post" => $post);

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
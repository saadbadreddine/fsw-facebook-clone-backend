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

if(isset($data -> id)){
    $key = JWT::decode($jwt, new Key($key, 'HS256'));
}else{
    die("User not found");
}
$blocked = false;

$query = $mysqli->prepare("SELECT posts.post, posts.timestamp, users.id, users.first_name, users.last_name, users.picture 
                            FROM posts JOIN users ON posts.user_id = ?
                            JOIN friendships ON (friendships.sender = ? OR friendships.receiver = ?) 
                            AND friendships.accepted = 1 AND users.id NOT IN (SELECT blocks.sender, blocks.receiver 
                            FROM blocks WHERE blocks.sender = ? OR blocks.receiver = ?)"); 
                           
$query->bind_param("iiibb",$key, $key, $key, $blocked, $blocked);
$query->execute();

$query->store_result;
$query->bind_result($post);
$query->fetch();

$array_response = [];
$array_response = ["status" => "List of posts", "posts" => $post];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
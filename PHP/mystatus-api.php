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
    $id = $data -> id;
    $key = JWT::decode($id, new Key($key, 'HS256'));
    $key = $key -> data;
}else{
    die("User not found");
}

$query = $mysqli->prepare("SELECT posts.post, posts.timestamp FROM posts WHERE posts.user_id = ?"); 
                           
$query->bind_param("i",$key);
$query->execute();

$array = $query->get_result();

$array_response = [];
while($data = $array->fetch_assoc()){
    $array_response[] = $data;
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
<?php

include("db_info.php");
include("authorization_api.php");
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
    die("Post not found");
}

$query = $mysqli->prepare("SELECT COUNT(like_id) FROM likes WHERE post_id = ?"); 
$query->bind_param("i", $post);
$query->execute();

$query->store_result();
$query->bind_result($count);
$query->fetch();

$array_response = [];
$array_response = ["status" => "Number of likes found", "count" => $count];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
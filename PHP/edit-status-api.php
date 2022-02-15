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
    $idErr = "User not found";
    $array_response = array("status" => $idErr);
    $json_response = json_encode($array_response);
    echo $json_response;
}

// Edit a status(post)

if(isset($data -> post_id)){
    $post_id = $data -> post_id;
    $updated_post = $data -> post;
}else{
    $postErr = "Post not found";
    $array_response = array("status" => $postErr);
    $json_response = json_encode($array_response);
    echo $json_response;
}

$query1 = $mysqli->prepare("UPDATE posts SET post=? WHERE post_id=? AND user_id=?");
$query1->bind_param("sii", $updated_post, $post_id, $decoded_id);
$query1->execute();

$query2 = $mysqli->prepare("SELECT timestamp FROM posts WHERE post_id = ?");
$query2->bind_param("i", $post_id);
$query2->execute();

$query2->store_result();
$query2->bind_result($timestamp);
$query2->fetch();

$array_response = [];
$array_response = ["status" => "Status updated", "post" => $updated_post, "post_id" => $post_id, "timestamp" => $timestamp];

$json_response = json_encode($array_response);
echo $json_response;

$query1->close();
$query2->close();
$mysqli->close();

?>
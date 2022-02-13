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

// Edit a status(post)

if(isset($data -> post_id)){
    $post_id = $data -> post_id;
    $updated_post = $data -> post;
}else{
    die("Post not found");
}

$query = $mysqli->prepare("UPDATE posts SET post=? WHERE post_id=?");
$query->bind_param("si", $updated_post, $post_id);
$query->execute();

$query->store_result;
$query->bind_result($like_id);
$query->fetch();

$array_response = [];
$array_response = ["status" => "Status updated", "like" => $like_id];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
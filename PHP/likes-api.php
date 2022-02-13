<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;

if(isset($_POST["post_id"])){
    $post = $_POST["post_id"];
}else{
    die("Post not found");
}

$query = $mysqli->prepare("SELECT COUNT(likes_id) FROM likes WHERE post_id = ?"); 
$query->bind_param("i", $post);
$query->execute();

$array_response = [];
$array_response = ["status" => "Find number of likes", "count" => $query];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
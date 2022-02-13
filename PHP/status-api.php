<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$decoded_key = JWT::decode($jwt, new Key($key, 'HS256'));

if(isset($_POST[$decoded_key])){
    $user = $_POST[$decoded_key];
}else{
    die("User not found");
}

// Add a status(post)

if (empty($_POST["post"])) {
    die("Post is empty");
}else{
    $post = $_POST["post"];
}

$query = $mysqli->prepare("INSERT INTO posts(post, user_id) VALUES (?, ?)"); 
$query->bind_param("si", $post, $user);
$query->execute();

$array_response = [];
$array_response = ["status" => "Post added", "post" => $post];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
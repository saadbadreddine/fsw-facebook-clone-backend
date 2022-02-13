<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$decoded_key = JWT::decode($jwt, new Key($key, 'HS256'));

// Validate Picture
$picture = $_POST["picture"];

if(isset($_POST["id"])){
    $user_id = $_POST[$decoded_key];
}else{
    die("User not found");
}
$query = $mysqli->prepare("UPDATE users  SET picture=? WHERE users.id = ?"); 
$query->bind_param("si", $picture, $user_id);
$query->execute();

$array_response = [];
$array_response = ["status" => "Show Picture", "picture" => $picture];

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
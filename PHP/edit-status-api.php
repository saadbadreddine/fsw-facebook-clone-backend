<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;

// Edit a status(post)

if (empty($_POST["post"])) {
    die("Post is empty");
}else{
    $updated_post =$_POST["post"];
}

if(isset($_POST["post_id"])){
    $post_id= $_POST["post_id"];
}else{
    die("Post not found");
}

$query = $mysqli->prepare("UPDATE posts SET post=? WHERE post_id=?");
$query->bind_param("si", $updated_post, $post_id);
$query->execute();

$array_response = [];
$array_response["status"] = $updated_post;

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
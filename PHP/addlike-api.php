<?php

include("db_info.php");

if(isset($_POST["post_id"])){
    $post = $_POST["post_id"];
}else{
    die("Post not found");
}

if(isset($_POST["user_id"])){
    $user = $_POST["user_id"];
}else{
    die("User not found");
}

$query = $mysqli->prepare("INSERT post_id, user_id INTO likes WHERE post_id = ? AND user_id = ?"); 
$query->bind_param("ii", $post, $user);
$query->execute();

$array_response = [];
$array_response["status"] = $query;

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
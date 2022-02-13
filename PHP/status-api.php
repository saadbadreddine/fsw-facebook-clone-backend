<?php

include("db_info.php");

// Add a status(post)

if (empty($_POST["post"])) {
    die("Post is empty");
}else{
    $post = $_POST["post"];
}

if(isset($_POST["user_id"])){
    $user_id = $_POST["user_id"];
}else{
    die("User not found");
}

$query = $mysqli->prepare("INSERT INTO posts(post, user_id) VALUES (?, ?)"); 
$query->bind_param("si", $post, $user_id);
$query->execute();

$array_response = [];
$array_response["status"] = $post;

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
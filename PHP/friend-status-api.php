<?php

include("db_info.php");

if(isset($_POST["post"])){
    $post = $_POST["post"];
}else{
    die("Post not found");
}

if(isset($_POST["id"])){
    $user_id = $_POST["id"];
}else{
    die("User not found");
}
$blocked = false;

$query = $mysqli->prepare("SELECT posts.post, posts.timestamp, users.id, users.first_name, users.last_name, users.picture 
                            FROM posts JOIN users ON posts.user_id = ?
                            JOIN friendships ON (friendships.sender = ? OR friendships.receiver = ?) 
                            AND friendships.accepted = 1 AND users.id NOT IN (SELECT blocks.sender, blocks.receiver 
                            FROM blocks WHERE blocks.sender = ? OR blocks.receiver = ?)"); 
                           
$query->bind_param("iiibb",$user_id, $user_id, $user_id, $blocked, $blocked);
$query->execute();

$array_response = [];
$array_response["status"] = $post;

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
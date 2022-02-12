<?php

include("db_info.php");

// Edit a status(post)
$post_id= $_POST["post_id"];
$updated_post =$_POST["post"];

$query = $mysqli->prepare("UPDATE posts SET post=? WHERE post_id=?");
$query->bind_param("si", $updated_post, $post_id);
$query->execute();

$array_response = [];
$array_response["status"] = $updated_post;

$json_response = json_encode($array_response);
echo $json_response;

?>
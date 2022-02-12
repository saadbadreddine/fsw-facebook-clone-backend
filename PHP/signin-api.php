<?php

include("db_info.php");

if(isset($_POST["email"])){
    $email = $mysqli->real_escape_string($_POST["email"]);
}else{
    die("Please Sign up");
}

if(isset($_POST["password"])){
    $password = $mysqli->real_escape_string($_POST["password"]);
    $password = hash("sha256", $password);
}else{
    die("Wrong Password");
}

$query = $mysqli->prepare("SELECT id FROM users WHERE email = ? And password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$query->store_result;
$num_rows = $query->num_rows;
$query->bind_result($id);
$query->fetch();

$array_response = [];

if($num_rows ==0){
    $array_response["status"] = "User not found, please sign up.";
}else{
    $array_response["status"] = "Logged In";
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
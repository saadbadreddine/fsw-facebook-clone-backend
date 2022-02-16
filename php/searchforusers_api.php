<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$data = json_decode($json);

if(isset($data -> sender)){
    $sender_id = $data -> sender;
    $decoded_sender = JWT::decode($id, new Key($key, 'HS256'));
    $decoded_sender = $decoded_sender -> id;
}else{
    $userErr = "User not found";
}

if(isset($data->first_name)){
    $first_name = $data->first_name;
}else{
    $fnameErr = "First name not found";
    $array_response = ["status" => $fnameErr];
    echo json_encode($array_response);
}

if(isset($data->last_name)){
    $last_name = $data->last_name;
}else{
    $lnameErr = "Last name not found"; 
    $array_response = ["status" => $lnameErr];
    echo json_encode($array_response);
}

$query = $mysqli->prepare("SELECT id, first_name, last_name FROM users WHERE id != ? AND ($first_name = ? OR $last_name = ?"); 
                           
$query->bind_param("iss",$decoded_sender, $first_name, $last_name);
$query->execute();

$array = $query->get_result();

$array_response = [];
while($data = $array->fetch_assoc()){
    $array_response[] = $data;
}

$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>
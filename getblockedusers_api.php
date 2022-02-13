<?php

include("db_info.php");

$user_id = 2;

$query = $mysqli -> prepare("SELECT id, first_name, last_name, picture
FROM users
INNER JOIN blocks ON users.id = blocks.receiver WHERE blocks.sender = ?");
$query->bind_param("i", $user_id);
$query->execute();

$array = $query->get_result();

$array_response = [];
while($data = $array->fetch_assoc()){
    $array_response[] = $data;
}

$json_response = json_encode($array_response);
echo $json_response;

?>
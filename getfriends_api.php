<?php

include("db_info.php");

$user_id = 1;

$query = $mysqli -> prepare("SELECT id, first_name, last_name, picture
FROM users JOIN friendships ON users.id = friendships.sender OR users.id = friendships.receiver
LEFT JOIN blocks ON  users.id = blocks.receiver OR users.id = blocks.sender
WHERE (friendships.sender = ? OR friendships.receiver = ?) AND friendships.accepted = 1 AND id != ?
AND id NOT IN (SELECT blocks.sender FROM blocks WHERE blocks.receiver = $user_id) ");
$query->bind_param("iii", $user_id, $user_id, $user_id);
$query->execute();

$array = $query->get_result();

$array_response = [];
while($data = $array->fetch_assoc()){
    $array_response[] = $data;
}

$json_response = json_encode($array_response);
echo $json_response;

?>
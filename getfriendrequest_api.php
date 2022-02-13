<?php

include("db_info.php");

$user_id = 1;

$query = $mysqli -> prepare("SELECT id, first_name, last_name, picture
FROM users INNER JOIN friendships ON users.id = friendships.sender OR users.id = friendships.receiver
WHERE friendships.receiver = ? AND friendships.accepted = 0 AND id != ?");
$query->bind_param("ii", $user_id, $user_id);
$query->execute();

$array = $query->get_result();

$array_response = [];
while($data = $array->fetch_assoc()){
    $array_response[] = $data;
}

$json_response = json_encode($array_response);
echo $json_response;

?>
<?php

include("db_info.php");
include("authorization_api.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$data = json_decode($json);


// First Name Validation

if(empty($data->first_name)){
    die("Please Enter a First Name"); 
}elseif(ctype_alpha($data->first_name)){
    $first_name = $mysqli->real_escape_string($data->first_name);
}else{
    die("Please Enter only alphabets");
}  

// Last Name Validation

if(empty($data->last_name)){
    die("Please Enter a Last Name"); 
} elseif(ctype_alpha($data->last_name)){
    $last_name = $mysqli->real_escape_string($data->last_name);
}else{
    die("Please Enter only alphabets");
}

// DOB Validation

function isDate($string) {
    $matches = array();
    $pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
    if (!preg_match($pattern, $string, $matches)) return false;
    if (!checkdate($matches[1], $matches[2], $matches[3])) return false;
    return true;
}
if(!isDate($data->dob)){
    die("Please Enter a Date"); 
}else{
    $dob = $data->dob;
}

// Email Validation

if (empty($data->email)) {
    die("Please enter an Email");
}elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid Email format");
}else{
    $email = $mysqli->real_escape_string($data->email);
}

// Password Validation

$uppercase = preg_match('@[A-Z]@', $data -> password);
$lowercase = preg_match('@[a-z]@', $data -> password);
$number    = preg_match('@[0-9]@', $data -> password);
$specialChars = preg_match('@[^\w]@', $data -> password);

if (empty($data->password)) {
    die("Please enter a Password");
}elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($data -> password) < 8) {
    die("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
}else{
    $password = $mysqli->real_escape_string($data->password);
    $password = hash("sha256", $password);
}

// Profile Picture Validation

$picture = $data->picture;

// Country Validation

if(empty($data -> country)){
    die("Please Enter a Country Name"); 
}elseif(ctype_alpha($data->country)){
    $country = $mysqli->real_escape_string($data->country);
}else{
    die("Country should contain only alphabets");
} 

// City Validation

if(empty($data->city)){
    die("Please Enter a City Name"); 
}elseif(ctype_alpha($data->city)){
    $city = $mysqli->real_escape_string($data->city);
}else{
    die("City should contain only alphabets");
} 

// Street Validation

if(empty($data->street)){
    die("Please Enter a Street Name"); 
}elseif(ctype_alpha($data -> street)){
    $street = $mysqli->real_escape_string($data->street);
}else{
    die("Street should contain only alphabets");
}

$query1 = $mysqli->prepare("INSERT INTO addresses(country, city, street) VALUES (?, ?, ?)"); 
$query1->bind_param("sss", $country, $city, $street);
$query1->execute();
$address_id = $mysqli->insert_id;

$query2 = $mysqli->prepare("INSERT INTO users(first_name, last_name, dob, email, password, picture, address_id) VALUES (?, ?, ?, ?, ?, ?, ?)"); 
$query2->bind_param("ssssssi", $first_name , $last_name, $dob, $email, $password, $picture, $address_id);
$query2->execute();

$array_response = [];
$array_response = ["status" => "Congratulations"];


$json_response = json_encode($array_response);
echo $json_response;

$query1->close();
$query2->close();
$mysqli->close();

?>
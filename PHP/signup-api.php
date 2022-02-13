<?php

include("db_info.php");
include("authorization_api.php");
use Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$data = json_decode($json);


// First Name Validation

if(empty($data -> first_name)){
    die("Please Enter a First Name"); 
}elseif(ctype_alpha($data -> first_name)){
    $first_name = $mysqli->real_escape_string($data -> first_name);
}else{
    die("Please Enter only alphabets");
}  

// Last Name Validation

if(empty($data -> last_name)){
    die("Please Enter a Last Name"); 
} elseif(ctype_alpha($data -> last_name)){
    $last_name = $mysqli->real_escape_string($data -> last_name);
}else{
    die("Please Enter only alphabets");
}

// DOB_D Validation

if(empty($data -> dob_d)){
    die("Please Enter a Day"); 
}elseif(is_int($data -> dob_d) && $data -> dob_d <= 31 && $data -> dob_d >= 1){
    $dob_d = $data -> dob_d;
}else{
    die("Please Enter a valid number");
}

// DOB_M Validation

if(empty($data -> dob_m)){
    die("Please Enter a Month"); 
}elseif(is_int($data -> dob_m) && $data -> dob_m <= 12 && $data -> dob_m >= 1){
    $dob_m = $data -> dob_m;
}else{
    die("Please Enter a valid number");
}

// DOB_Y Validation

if(empty($data -> dob_y)){
    die("Please Enter a Year"); 
}elseif(is_int($data -> dob_y) && $data -> dob_y >= 1922){
    $dob_y = $data -> dob_y;
}else{
    die("Please Enter a valid number");
}

// Email Validation

if (empty($data -> email)) {
    die("Please enter an Email");
}elseif (!filter_var($data -> email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid Email format");
}else{
    $email = $mysqli->real_escape_string($data -> email);
}

// Password Validation

$uppercase = preg_match('@[A-Z]@', $data -> password);
$lowercase = preg_match('@[a-z]@', $data -> password);
$number    = preg_match('@[0-9]@', $data -> password);
$specialChars = preg_match('@[^\w]@', $data -> password);

if (empty($data -> password)) {
    die("Please enter a Password");
}elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    die("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
}else{
    $password = $mysqli->real_escape_string($data -> password);
    $password = hash("sha256", $password);
}

// Profile Picture Validation

$picture = $data -> picture;

// Country Validation

if(empty($data -> country)){
    die("Please Enter a Country Name"); 
}elseif(ctype_alpha($data -> country)){
    $country = $mysqli->real_escape_string($data -> country);
}else{
    die("Please Enter only alphabets");
} 

// City Validation

if(empty($data -> city)){
    die("Please Enter a City Name"); 
}elseif(ctype_alpha($data -> city)){
    $city = $mysqli->real_escape_string($data -> city);
}else{
    die("Please Enter only alphabets");
} 

// Street Validation

if(empty($data -> street)){
    die("Please Enter a Street Name"); 
}elseif(ctype_alpha($data -> street)){
    $street = $mysqli->real_escape_string($data -> street);
}else{
    die("Please Enter only alphabets");
} 

$query1 = $mysqli->prepare("INSERT INTO users(first_name, last_name, dob_d, dob_m, dob_y, email, password, picture, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
$query1->bind_param("ssiiisssi", $first_name , $last_name, $dob_d, $dob_m, $dob_y, $email, $password, $picture, $timestamp);
$query1->execute();

$query2 = $mysqli->prepare("INSERT INTO addresses(country, city, street) VALUES (?, ?, ?)"); 
$query2->bind_param("sss", $country, $city, $street);
$query2->execute();

$query3 = $mysqli->prepare("SELECT address_id FROM addresses WHERE address_id = LAST_INSERT_ID()"); 
$query3->execute();

$address_id = $query3->store_result();

$query4 = $mysqli->prepare("INSERT INTO users(address_id) VALUES (?)");
$query4->bind_param("i", $address_id);
$query4->execute();

$query->store_result;
$query->bind_result($data);
$query->fetch();

$array_response = [];
$array_response = ["status" => "Welcome to Facebook, please Sign In", "data" => $data];

$json_response = json_encode($array_response);
echo $json_response;

$query1->close();
$query2->close();
$query3->close();
$query4->close();
$mysqli->close();

?>
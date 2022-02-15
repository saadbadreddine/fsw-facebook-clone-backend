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



if(empty($data->first_name)){
    $first_nameErr = "Please Enter a First Name"; 
    $array_response = ["status" => $first_nameErr];
    echo json_encode($array_response);
}elseif(ctype_alpha($data->first_name)){
    $first_name = $mysqli->real_escape_string($data->first_name);
}else{
    $first_nameErr = "Please Enter only alphabets";
}  

// Last Name Validation

if(empty($data->last_name)){
    $last_nameErr = "Please Enter a Last Name"; 
    $array_response = ["status" => $last_nameErr];
    echo json_encode($array_response);
} elseif(ctype_alpha($data->last_name)){
    $last_name = $mysqli->real_escape_string($data->last_name);
}else{
    $last_nameErr = "Please Enter only alphabets";
}

// DOB Validation

function isDate($string){
    $matches = array();
    $pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
    if (!preg_match($pattern, $string, $matches)) return false;
    if (!checkdate($matches[1], $matches[2], $matches[3])) return false;
    return true;
}
if(!isDate($data->dob)){
   
    $dobErr = "Please Enter a Date"; 
    $array_response = ["status" => $dobErr];
    echo json_encode($array_response);
}else{
    $dob = $data->dob;
}

// Email Validation

if (empty($data->email)) {
    $emailErr = "Please enter an email";
    $array_response = ["status" => $emailErr];
    echo json_encode($array_response);
}elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid Email format";
}else{
    $email = $mysqli->real_escape_string($data->email);
}

// Password Validation

$uppercase = preg_match('@[A-Z]@', $data -> password);
$lowercase = preg_match('@[a-z]@', $data -> password);
$number    = preg_match('@[0-9]@', $data -> password);
$specialChars = preg_match('@[^\w]@', $data -> password);

if (empty($data->password)) {
    $passswordErr = "Please enter a Password";
    $array_response = ["status" => $passswordErr];
    echo json_encode($array_response);
}elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($data -> password) < 8) {
    $passwordErr = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
    $array_response = ["status" => $passswordErr];
    echo json_encode($array_response);
}else{
    $password = $mysqli->real_escape_string($data->password);
    $password = hash("sha256", $password);
}

// Profile Picture Validation

$picture = $data->picture;

// Country Validation

if(empty($data -> country)){
    $countryErr = "Please Enter a Country Name"; 
    $array_response = ["status" => $countryErr];
    echo json_encode($array_response);
}elseif(ctype_alpha($data->country)){
    $country = $mysqli->real_escape_string($data->country);
}else{
    $countryErr = "Country should contain only alphabets";
} 

// City Validation

if(empty($data->city)){
    $cityErr = "Please Enter a City Name"; 
    $array_response = ["status" => $cityErr];
    echo json_encode($array_response);
}elseif(ctype_alpha($data->city)){
    $city = $mysqli->real_escape_string($data->city);
}else{
    $cityErr = "City should contain only alphabets";
} 

// Street Validation

if(empty($data->street)){
    $streetErr = "Please Enter a Street Name"; 
    $array_response = ["status" => $streetErr];
    echo json_encode($array_response);
}elseif(ctype_alpha($data -> street)){
    $street = $mysqli->real_escape_string($data->street);
}else{
    $streetErr = "Street should contain only alphabets";
    $array_response = ["status" => $streetErr];
    echo json_encode($array_response);
}
$query1 = $mysqli->prepare("SELECT email FROM users WHERE email = ?"); 
$query1->bind_param("s", $email);
$query1->execute();
$result = $query1->get_result();

if($result != null){
    $emailErr = "Email already registered";
    $array_response = ["status" => $emailErr];
    echo json_encode($array_response);
}else{
    $query2 = $mysqli->prepare("INSERT INTO addresses(country, city, street) VALUES (?, ?, ?)"); 
    $query2->bind_param("sss", $country, $city, $street);
    $query2->execute();
    $address_id = $mysqli->insert_id;

    $query3 = $mysqli->prepare("INSERT INTO users(first_name, last_name, dob, email, password, picture, address_id) VALUES (?, ?, ?, ?, ?, ?, ?)"); 
    $query3->bind_param("ssssssi", $first_name , $last_name, $dob, $email, $password, $picture, $address_id);
    $query3->execute();

    $payload = [
        "iss" => "localhost",
        "aud" => "localhost",
        "iat" => 1356999524,
        "nbf" => 1357000000,
        "data" => $key
    ];
    $jwt = JWT::encode($payload, $key, 'HS256');
    $array_response = ["status" => "Welcome to Facebook", "token" => $jwt];

    $json_response = json_encode($array_response);
    echo $json_response;

    $query2->close();
    $query3->close();
}
$query1->close();
$mysqli->close();

?>
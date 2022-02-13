<?php

include("db_info.php");

// First Name Validation

if(empty($_POST["first_name"])){
    die("Please Enter a First Name"); 
}elseif(ctype_alpha($_POST["first_name"])){
    $first_name = $mysqli->real_escape_string($_POST["first_name"]);
}else{
    die("Please Enter only alphabets");
}  

// Last Name Validation

if(empty($_POST["last_name"])){
    die("Please Enter a Last Name"); 
} elseif(ctype_alpha($_POST["last_name"])){
    $last_name = $mysqli->real_escape_string($_POST["last_name"]);
}else{
    die("Please Enter only alphabets");
}
  
// DOB_D Validation

if(empty($_POST["dob_d"])){
    die("Please Enter a Day"); 
}elseif(is_int($_POST["dob_d"]) && $_POST["dob_d"] <= 31 && $_POST["dob_d"] >= 1){
    $dob_d = $_POST["dob_d"];
}else{
    die("Please Enter a valid number");
}

// DOB_M Validation

if(empty($_POST["dob_m"])){
    die("Please Enter a Month"); 
}elseif(is_int($_POST["dob_m"]) && $_POST["dob_m"] <= 12 && $_POST["dob_m"] >= 1){
    $dob_m = $_POST["dob_m"];
}else{
    die("Please Enter a valid number");
}

// DOB_Y Validation

if(empty($_POST["dob_y"])){
    die("Please Enter a Year"); 
}elseif(is_int($_POST["dob_y"]) && $_POST["dob_y"] >= 1922){
    $dob_y = $_POST["dob_y"];
}else{
    die("Please Enter a valid number");
}

// Email Validation

if (empty($_POST["email"])) {
    die("Please enter an Email");
}elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Invalid Email format");
}else{
    $email = $_POST["email"];
}

// Password Validation

$uppercase = preg_match('@[A-Z]@', $_POST["password"]);
$lowercase = preg_match('@[a-z]@', $_POST["password"]);
$number    = preg_match('@[0-9]@', $_POST["password"]);
$specialChars = preg_match('@[^\w]@', $_POST["password"]);

if (empty($_POST["password"])) {
    die("Please enter a Password");
}elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    die("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
}else{
    $password = $mysqli->real_escape_string($_POST["password"]);
    $password = hash("sha256", $password);
}

// Profile Picture Validation

$picture = $_POST["picture"];

// Country Validation

if(empty($_POST["country"])){
    die("Please Enter a Country Name"); 
}elseif(ctype_alpha($_POST["country"])){
    $country = $mysqli->real_escape_string($_POST["country"]);
}else{
    die("Please Enter only alphabets");
} 

// City Validation

if(empty($_POST["city"])){
    die("Please Enter a City Name"); 
}elseif(ctype_alpha($_POST["city"])){
    $city = $mysqli->real_escape_string($_POST["city"]);
}else{
    die("Please Enter only alphabets");
} 

// Street Validation

if(empty($_POST["street"])){
    die("Please Enter a Street Name"); 
}elseif(ctype_alpha($_POST["street"])){
    $city = $mysqli->real_escape_string($_POST["street"]);
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

$array_response = [];
$array_response["status"] = "Welcome to Facebook";

$json_response = json_encode($array_response);
echo $json_response;

?>
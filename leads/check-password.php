<?php include 'inc/trois.php';
$con = conDB();
$pass = $_POST['pass'];
if(strlen($pass) < 8){
	die("Must be at least 8 characters.");
}
if(strlen($pass) > 20){
	die("Must be at less than 20 characters.");
}
if(!preg_match('/.*[0-9].*/',$pass)){
	die("Must contain at least 1 digit");
}
if(!preg_match("/.*[a-z].*/",$pass)){
	die("Must contain at least 1 lowercase character");
}
if(!preg_match("/.*[A-Z].*/",$pass)){
	die("Must contain at least 1 uppercase character");
}
die("OK");
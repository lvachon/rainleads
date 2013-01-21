<?php
	include 'inc/trois.php';
	$email = mysql_escape_string($_POST['email']);
	
	$con = conDB();
	
	$check = mysql_query("SELECT count(*) from users WHERE UCASE(email) = '".strtoupper($email)."'",$con);
    
	$res = mysql_fetch_array($check);
	if($res[0] == 0){
		echo "OK";
	}else{
		echo "That email is already registered!";
	}
?>
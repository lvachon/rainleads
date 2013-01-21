<?php include 'inc/trois.php';
$con = conDB();
$title = $_POST['title'];
$checkDomain = mysql_query("SELECT COUNT(id) FROM accounts WHERE LCASE(subdomain) = LCASE('$title')",$con);
$domains = mysql_fetch_array($checkDomain);
if($domains[0] > 0){
	die("Subdomain is not available.");
}else{
	die("Subdomain available.");
}
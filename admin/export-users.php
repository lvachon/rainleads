<?php 
include_once '../includes/trois.php';
header("Content-type: text/csv");
header("Content-disposition: attachment; filename=result.csv");
if(!in_array(verCookie(),$ADMIN_IDS)){die("You must be an admin to access this area.");}
$con = conDB();
$r = mysql_query("SELECT email, getData('fname',`data`) AS First_Name, getData('lname',`data`) AS Last_Name from users where 1 ORDER BY Last_Name ASC,First_Name ASC",$con);
//echo mysql_error();
while($a = mysql_fetch_array($r)){
	echo "{$a['email']},\"{$a['First_Name']}\",\"{$a['Last_Name']}\"\r\n";	
}
?>
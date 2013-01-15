<?php include '../inc/trois.php';
$viewer = new User(verCookie());
$account = new Account($_GET['account']);
if(!in_array($viewer->id,$ADMIN_IDS)){die("You're no admin!");}
$con = conDB();
$delta = intval($_GET['delta']);
if($delta>0){
	$dtx="add";
}else{
	$dtx="rem";
}
$thing = mysql_escape_string($_GET['thing']);
mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'{$dtx}_{$thing}','admin_adjusted_storage',0,unix_timestamp())",$con);
header("Location: account.php?id={$account->id}");
die();	
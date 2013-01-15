<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
$con = conDB();
//die(var_dump($_POST));
$table = mysql_escape_string($_POST['table']);
$title = mysql_escape_string($_POST['title']);
$account_id = intval($_POST['account_id']);
$color = mysql_escape_string($_POST['color']);
$show = 1;
$admin = intval($_POST['admin']);
$getMaxStatus = mysql_query("SELECT MAX(display) FROM `{$table}` WHERE account_id = $account_id",$con);
$maxStatus = mysql_fetch_array($getMaxStatus);
$display = $maxStatus[0]+1;
mysql_query("INSERT INTO `{$table}`(`title`,`account_id`,`color`,`show`,`admin`,`display`) VALUES('$title',$account_id,'$color',$show,$admin,$display)",$con) or die(mysql_error());
header("Location: {$HOME_URL}account/settings.php");die();
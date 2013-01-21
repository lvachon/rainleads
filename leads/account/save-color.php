<?php include '../inc/trois.php';
$con = conDB();
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
$color = mysql_escape_string($_POST['color']);
$id = $_POST['id'];
$parts = explode('_',$id);
$type = $parts[0];
$content_id = $parts[1];
if($type == 'status'){
	$table = "`statuses`";
}elseif($type == 'milestone'){
	$table = "`milestones`";
}
mysql_query("UPDATE $table SET color = '$color' WHERE id = $content_id",$con) or die(mysql_error());
die(mysql_affected_rows($con));
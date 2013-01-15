<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
$con = conDB();
$val = mysql_escape_string($_POST['val']);
$id = $_POST['id'];
$parts = explode('_',$id);
$type = $parts[0];
$content_id = $parts[1];
if($type == 'status'){
	$table = "`statuses`";
}elseif($type == 'milestone'){
	$table = "`milestones`";
}
mysql_query("UPDATE $table SET `title` = '$val' WHERE id = $content_id",$con) or die(mysql_error()."<hr />UPDATE $table SET `title` = '$val' WHERE id = $content_id");
die();
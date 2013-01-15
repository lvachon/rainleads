<?php include '../inc/trois.php';
$con = conDB();
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
$table = mysql_escape_string($_POST['table']);
if($table == 'statuses'){
	$order = explode('status[]=',$_POST['order']);
}elseif($table == 'milestones'){
	$order = explode('milestone[]=',$_POST['order']);
}
$clean_ord = array();
foreach($order as $o){
	if(strlen($o)){
		$clean_ord[]= trim(str_replace('&','',$o));
	}
}
foreach($clean_ord as $position=>$id){
	mysql_query("UPDATE `{$table}` SET `display` = $position WHERE id = $id",$con) or die(mysql_error());
}
die();
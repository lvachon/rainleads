<?php include 'inc/trois.php';
include 'support/main.php';
$con = conDB();
///this is to automatically archive support threads that have had no activity in over 72 hours//
$thresh = time()-(3*86400);
//this should get all of the conversations that have had no activity in the last 72 hours
$getConvos = mysql_query("SELECT conversation_id,user_id,id FROM mail WHERE datestamp < $thresh GROUP BY conversation_id ORDER BY datestamp DESC",$con);
while($r = mysql_fetch_array($getConvos)){
	$mail = new Mail(32);
	$convo = new Convo($r['conversation_id']);
	$thread = $convo->thread_id;
	$users = explode(',',$thread);
	if($users[0] == 32){
		$other = $users[1];
		$field = '`user2_hide`';
	}elseif($users[1] == 32){
		$other = $users[0];
		$field = '`user1_hide`';
	}
	if($r['user_id'] == 32 || in_array($r['user_id'],$ADMIN_IDS)){
		$update = mysql_query("UPDATE conversations SET $field = 1 WHERE id = {$convo->id} LIMIT 1",$con);
		//this was responded to last by the administration so we need to mark this as resolved maybe we should send them an email letting them know that this was done as well

	}else{
		//maybe we should mark this as new so that the next person 
		$update = mysql_query("UPDATE mail SET new = 1 WHERE id = {$r['id']}",$con);			
	}
}

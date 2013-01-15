<?php include '../inc/trois.php';
loginRequired();
$viewer = new User(verCookie());
$account = new Account(verAccount());
$result_id = intval($_POST['result_id']);
$msid = intval($_POST['msid']);
$con = conDB();
$r = mysql_query("SELECT milestones from form_results where id=$result_id",$con);
$ms = mysql_fetch_array($r);
$ms = explode(",",$ms[0]);
if(in_array($msid,$ms)){
	$newms = array();
	foreach($ms as $m){
		if($m!=$msid){$newms[]=$m;}
	}
	$ms=$newms;
	$return = "0";
}else{
	$ms[]=$msid;
	$return = "1";
}
$getMilestone  = mysql_query("SELECT * FROM milestones WHERE id = $msid",$con);
$milestone = mysql_fetch_array($getMilestone);
mysql_query("update form_results set milestones = '".mysql_escape_string(implode(",",$ms)) ."' where id=$result_id and (SELECT account_id from forms where id=form_id) = {$account->id} LIMIT 1",$con);
if($return == "0"){
	saveAction('milestone_removed',$viewer->id,$result_id,$milestone['title'],$account->id,$result_id);
}else{
	saveAction('milestone_saved',$viewer->id,$result_id,$milestone['title'],$account->id,$result_id);	
}
die($return);

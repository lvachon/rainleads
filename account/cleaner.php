<?php
include '../inc/trois.php';
//This should only ever be run from the command line, so if $_SERVER['HTTP_HOST'] exists, abort
if(strlen($_SERVER['HTTP_HOST'])){errorMsg("This script should not be accessed from the web");die();}
$con = conDB();
$r = mysql_query("SELECT account_id,datestamp from transactions where type='sub_cancel' and datestamp<unix_timestamp()-86400*30",$con);
while($can = mysql_fetch_array($r)){
	$q = mysql_query("SELECT * from transactions where type='sub_create' and account_id={$can['account_id']} and datestamp>{$can['datestamp']}",$con);
	$a = mysql_fetch_array($q);
	if(intval($a['id'])){
		continue;
	}
	//We didn't find a re-activation after this cancelation, so it's time to get destructive
	$account = new Account($can['account_id']);
	echo $account->id."<br/>";
	/*
	$account->data['microsite_url']="";
	$account->save();
	mysql_query("update accounts set subdomain='' where id={$account->id}",$con);
	
	 * 
	 */
	 $fr = mysql_query("SELECT id from proposals where account_id={$account->id}",$con);
	while($f = mysql_fetch_array($fr)){
		echo("rm -f {$HOME_DIR}/proposals/{$f['id']}");	
	}
}
<?php include '../inc/trois.php';
$viewer = new User(verCookie());
$account = new Account(verAccount());

include 'leads.php';


$con = conDB();
$r = mysql_query("SELECT * from statuses where account_id=2 order by display asc limit 1",$con);
$status = mysql_fetch_array($r);
$status = strval(intval($status['id']));

$usermap = array(19=>2,17=>6,12=>7,30=>10);

foreach($leads as $lead){
	$f = new Form(15);
	$e = $f->getElemByName('name');
	$e->value = $lead['name'];
	
	$e = $f->getElemByName('email');
	$e->value = $lead['email'];
	
	$e = $f->getElemByName('text_2');
	$e->value = $lead['service'];
	
	$e = $f->getElemByName('text_3');
	$e->value = $lead['budget'];
	
	$e = $f->getElemByName('text_4');
	$e->value = $lead['url'];
	
	$e = $f->getElemByName('textarea_5');
	$e->value = $lead['idea'];
	
	$name = mysql_escape_string($lead['name']);
	$email = mysql_escape_string($lead['email']);
	$datestamp = intval($lead['datestamp']);
	$uid = intval($usermap[intval($lead['user_id'])]);

	$ccinfo = array('name'=>$lead['name'],'businessname'=>$lead['company'],'mobile'=>$lead['phone'],'email'=>$lead['email']);

	$qry="INSERT INTO form_results(name,email,form_id,datestamp,data,contact_data,status,display_id,tracking_code) VALUES('$name','$email',{$f->id},{$lead['datestamp']},'".mysql_escape_string(serialize($f->elems))."','".mysql_escape_string(serialize($ccinfo))."',{$status},{$lead['id']},'manual_import')";
	mysql_query($qry,$con);
	$rid = intval(mysql_insert_id($con));
	echo $rid.", ";
	mysql_query("INSERT INTO assignments(result_id,user_id,account_id,datestamp)VALUES($rid,$uid,1,unix_timestamp())",$con);
}


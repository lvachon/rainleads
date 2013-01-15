<?php include '../inc/trois.php';
loginRequired();
$con = conDB();
$acct = new Account(verAccount());
$viewer = new User(verCookie());
if(!verAccount()){die();}
$getAssigment = mysql_query("SELECT * FROM assignments WHERE result_id = ".intval($_POST['result_id']),$con) or die(mysql_error());
$assignmentInfo = mysql_fetch_array($getAssigment);
if($assignmentInfo['user_id'] == $_POST['user_id']){
	die('ok not changed');
}
if(intval($acct->data['assign_leads']) || in_array($viewer->id,$acct->admins)){
	$res = intval($_POST['result_id']);
	$user_id = intval($_POST['user_id']);
	$user = new User($user_id);
	mysql_query("INSERT INTO assignments(result_id,user_id,account_id,datestamp) VALUES($res,$user_id,{$acct->id},unix_timestamp()) ON DUPLICATE KEY UPDATE result_id=$res,user_id=$user_id,account_id={$acct->id},datestamp=unix_timestamp()",$con);
	$getFormResult = mysql_query("SELECT * FROM form_results WHERE id = $res",$con);
	$result = mysql_fetch_array($getFormResult);
	$leadInfo = "Lead ID: {$result['id']}<br />
	Lead Name: {$result['name']}<br />
	Lead Email: {$result['email']}<br />
	Lead Recieved: ".date('n/j/Y',$result['datestamp']);
	$mailVariables = array('%homeurl','%sitename','%receiver','%url','%post');
	$mailValues = array($HOME_URL,$SITE_NAME,$user->name(),$HOME_URL."leads/lead.php?id=".$res,$leadInfo);
	$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_lead_assigned'));
	$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_lead_assigned')));
	htmlEmail($user->email,$subject,$message);
	saveAction('assignment',$user->id,$res,'',$acct->id,$res);
	die("ok");
}
<?php include '../inc/trois.php';
loginRequired();
$account = new Account(verAccount());
$user = new User(verCookie());
if($account->membership=="free"){errorMsg("Free accounts cannot create or edit a microsite.  <a href='/account/upgrade.php'>Upgrade</a> your account");die();}
if($account->user_id!=$user->id){errorMsg("Only the account administrator can modify the microsite");}
if(strlen($_POST['url']) && intval($_POST['form'])){
	$url = $_POST['url'];
	$form = intval($_POST['form']);
	if(!preg_match("/[0-9a-zA-Z_\-]+/i",$url)){errorMsg("Your url contains invalid characters, please limit your selection to only numbers, letters, dashes, and underscores.");die();}
	$con = conDB();
	$r=mysql_query("SELECT count(*) from accounts where getData('url',data)='".mysql_escape_string(strtolower($url))."'",$con);
	$dupe = mysql_fetch_array($r);
	if(intval($dupe[0])>0){errorMsg("This url has already been claimed");die();}
	$account->data['microsite_url']=$url;
	$account->data['microsite_form']=strval($form);
	$account->save();
	header("Location: index.php");
	die();
}
if($_POST['services']){
	$services = implode(",",$_POST['services']);
	$account->data['services']=$services;
	$account->data['MDaddress']=$_POST['address'];
	$account->data['MDaddress2']=$_POST['address2'];
	$account->data['MDcity_state']=$_POST['city_state'];
	$account->data['MDtel']=$_POST['tel'];
	$account->data['MDfax']=$_POST['fax'];
	$account->data['MDemail']=$_POST['email'];
	$account->data['MDurl']=$_POST['url'];
	$account->data['MDemail']=$_POST['email'];
	$account->data['MDhours']=$_POST['hours'];
	$account->data['MDcompany']=$_POST['company'];
	$account->data['MDdesc']=$_POST['desc'];
	$account->save();
	header("Location: edit.php");
	die();
}

if($_FILES['file']['tmp_name']){
	exec("convert \"{$_FILES['file']['tmp_name']}\" -geometry \"250x250>\" \"".getcwd()."/{$account->id}.jpg\"");
	?><script>parent.$("#logodiv").css({"background":"url(<?=$account->id;?>.jpg?rnd=<?=rand();?>) center no-repeat"});</script><?php
	
	die();
}


<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$user = new User(verCookie());
$account = new Account(verAccount());
$con = conDB();
$title = mysql_escape_string($_POST['title']);
$amount = mysql_escape_string($_POST['amount']);
$prob = floatval($_POST['prob']);
$result_id = intval($_POST['result_id']);
$id = intval($_POST['id']);
if(!intval($id)){
	mysql_query("INSERT INTO proposals(user_id,account_id,result_id,title,amount,probability,datestamp) VALUES($user->id,$account->id,$result_id,'$title','$amount',$prob,unix_timestamp())",$con);
	$id = mysql_insert_id($con);
}else{
	mysql_query("UPDATE proposals set title='$title', amount='$amount', probability=$prob where id=$id LIMIT 1",$con);
}

if(!intval($id)){errorMsg("There was a problem saving your proposal data: ".mysql_error());die();}
if($_FILES['file']['tmp_name']){
		$a = filesize($_FILES['file']['tmp_name']);
		$b = ($account->storageLimit() - $account->storageUsed());
		if($a>$b){$a=floor($a/1024);$b=floor($b/1024);errorMsg("You do not have enough free storage space to store this file. ({$a}kB needed/{$b}kB left) <a href='/account/upgrade.php'>Please upgrade your account</a>");die();}
		move_uploaded_file($_FILES['file']['tmp_name'],getcwd()."/../proposals/{$id}");
		mysql_query("UPDATE proposals set data='".mysql_escape_string($_FILES['file']['name'])."' WHERE id=$id LIMIT 1",$con);
}
if(intval($_POST['pipe'])){
	mysql_query("UPDATE form_results set pipeline=1 where id={$result_id} LIMIT 1",$con);
}
saveAction('file',$viewer->id,$id,'',$account->id,$result_id);
header("Location: lead.php?id={$result_id}&show=proposals");
die();
<?php include '../inc/trois.php';
	loginRequired();
	$account = $viewer->getAccount();
	$token = $_GET['token'];
	$account->data['fb_token'] = $_GET['token'];
	$account->save();
	header("Location: edit-facebook.php")
	
?>
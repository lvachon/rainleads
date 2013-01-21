<?php ob_start(); require_once('../inc/trois.php');
loginRequired();
accountRequired();
$mem = new User(intval($_GET['id']));
$account = $viewer->getAccount();
if(!in_array($viewer->id,$account->admins) || !in_array($mem->id,$account->members)){
	errorMsg("You don't have permission to remove that user.");
}
$mem->delete();
header("Location: settings.php");die();
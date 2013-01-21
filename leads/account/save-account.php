<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
$id = intval($_POST['id']);
$account = new Account($id);
$account->postSave();
$account->title = $_POST['title'];
$account->save();
//var_dump($_POST);
$file = $_FILES['file']['tmp_name'];
if(strlen($file)){
	exec("convert {$file} -thumbnail '256x256>' " . getcwd() . "/files/{$account->id}.jpg");
}
header("Location: index.php?n=information&m=Account Info Saved");die(); ?>
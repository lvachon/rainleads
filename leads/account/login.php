<?php  include '../inc/trois.php';
$id = intval($_GET['id']);
$u = new User($id);
$u->login();
switch($_GET['plan']){
	case '1':
		$plan = 'lite';
	break;
	case '2':
		$plan = 'basic';
	break;
	case '3':
		$plan = 'pro';
	break;
		
}
if(intval($_GET['plan'])){
	header("Location: upgrade.php?plan={$plan}");	
}else{
	header("Location: index.php"); 
}
die();?>
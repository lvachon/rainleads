<?php include '../includes/trois.php';
loginRequired();
if(!in_array(verCookie(),$ADMIN_IDS)){
	errorMsg("You must be an admin to access this area.");
}
$con = conDB();
$id = intval($_GET['id']);
$user = new User($id);
$user->delete();
header("Location: ".$_SERVER['HTTP_REFERER']);die();?>

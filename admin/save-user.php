<?php
include '../inc/trois.php';
loginRequired();
if(!in_array(verCookie(),$ADMIN_IDS)){ errorMsg("You must be an administrator to view this page.");}
$user = new User($_POST['id']);
$user->postSave();
header("Location: ".$_SERVER['HTTP_REFERER']);
?>

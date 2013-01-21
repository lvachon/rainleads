<?php include '../includes/trois.php'; include 'main.php';
loginRequired();
$viewer = new User(verCookie());
if(intval($_GET['as32']) && in_array($viewer->id,$ADMIN_IDS)){$viewer = new User(32);}
$conv_id = mysql_escape_string(trim($_GET['id']));
$convo = new Convo($conv_id,$viewer->id);
$convo->deleteThread();
header("Location: ".$_SERVER['HTTP_REFERER']);?>
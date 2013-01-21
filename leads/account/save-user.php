<?php include '../inc/trois.php';
loginRequired();
$_POST['onlymyleads']=intval($_POST['onlymyleads']);
$viewer->postSave();
//var_dump($_POST);
header("Location: {$_SERVER['HTTP_REFERER']}");die(); ?>
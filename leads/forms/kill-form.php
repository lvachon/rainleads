<?php include '../inc/trois.php';
loginRequired();
$account = new Account(verAccount());
$con = conDB();
$fid = intval($_GET['id']);
mysql_query("UPDATE forms set deleted=1 where account_id={$account->id} and id=$fid LIMIT 1",$con);
header("Location: index.php");
die();
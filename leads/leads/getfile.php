<?php include '../inc/trois.php';
$con = conDB();
$id = intval($_GET['id']);
$account = new Account(verAccount());
$r = mysql_query("SELECT data from proposals where id={$id} and account_id={$account->id}",$con);
$f = mysql_fetch_array($r);
#header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $f['data']. '"');
$f = fopen(getcwd()."/../proposals/{$id}","r");
fpassthru($f);
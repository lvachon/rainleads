<?php include '../inc/trois.php';
$acct = new Account(verAccount());
$viewer = new User(verCookie());
$con = conDB();
$res = intval($_GET['id']);
mysql_query("UPDATE form_results set deleted=1-deleted where id=$res and (SELECT account_id from forms where id=form_id) = {$acct->id} LIMIT 1",$con);
header("Location: lead.php?id=$res");
die();

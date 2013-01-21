<?php include '../inc/trois.php';
$acct = new Account(verAccount());
$viewer = new User(verCookie());
if(!verAccount()){die();}
$con = conDB();
$res = intval($_POST['result_id']);
$pipe = intval($_POST['pipe']);
mysql_query("UPDATE form_results set pipeline=$pipe where id=$res and (SELECT account_id from forms where id=form_id) = {$acct->id} LIMIT 1",$con);
die("ok");

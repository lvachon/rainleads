<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
$id = intval($_POST['id']);
$status = mysql_escape_string($_POST['status']);
$con=conDB();
$r = mysql_query("UPDATE form_results set status='$status' where id=$id and (SELECT account_id from forms where id=form_id)={$account->id} LIMIT 1",$con);
die($_POST['status']');
?>
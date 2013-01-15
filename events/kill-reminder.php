<?php include '../inc/trois.php';
loginRequired();
$con = conDB();
$id = intval($_POST['id']);
$del = mysql_query("DELETE FROM reminders WHERE id = $id AND user_id = {$viewer->id} LIMIT 1",$con);
die(strval(mysql_affected_rows($con)));
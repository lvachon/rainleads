<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = $viewer->getAccount();

$row = mysql_escape_string($_POST['row']);
$page = mysql_escape_string($_POST['page']);
$id = mysql_escape_string($_POST['id']);

var_dump($_POST);

$con = conDB();
$update = mysql_query("UPDATE facebook_pages SET form_id={$id} WHERE id={$row} AND page_id={$page} AND account_id={$account->id} LIMIT 1",$con);


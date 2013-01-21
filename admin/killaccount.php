<?php include 'head.php'; 

$account = new Account($_GET['id']);
$con = conDB();
mysql_query("DELETE from form_results where form_id in (SELECT id from forms where account_id={$account->id})",$con);
mysql_query("DELETE from forms where account_id={$account->id}",$con);
mysql_query("DELETE from membership where account_id={$account->id}",$con);
mysql_query("DELETE from proposals where account_id={$account->id}",$con);
mysql_query("DELETE from milestones where account_id={$account->id}",$con);
mysql_query("DELETE from statuses where account_id={$account->id}",$con);
mysql_query("DELETE from events where account_id={$account->id}",$con);
mysql_query("DELETE from accounts where id={$account->id}",$con);
header("Location: accounts.php");
die();

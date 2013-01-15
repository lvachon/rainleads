<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$acct = new Account(verAccount());
$con = conDB();
$res = intval($_GET['result_id']);
mysql_query("UPDATE form_results set pipeline=1-pipeline where id=$res and (SELECT account_id from forms where id=form_id) = {$acct->id} LIMIT 1",$con);
$r = mysql_query("SELECT pipeline from form_results where id=$res",$con);
$a = mysql_fetch_array($r);
if($a['pipeline'] == 1){
	//Delete old ones if someone's just toggling it??
	saveAction('pipeline',$viewer->id,$res,'',$viewer->getAccount()->id,$res);
}
echo(strval(intval($a['pipeline'])));


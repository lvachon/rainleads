<?php include '../inc/trois.php';
loginRequired();
accountRequired();

$id = intval($_GET['id']);
$f = new Form();
$con = conDB();
$r = mysql_query("SELECT *,(select account_id from forms where id=form_id) as account_id from form_results where id=$id",$con);
$d = mysql_fetch_array($r);
if(intval($d['account_id'])!=$account->id){errorMsg("This is not your lead to view");die();}
$f->elems = unserialize($d['data']);
for($i=0;$i<count($f->elems);$i++){
	$f->elems[$i]->init();
	
}

echo $f->getResultHTML();
?>
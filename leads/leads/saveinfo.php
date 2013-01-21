<?php error_reporting(E_ERROR);include '../inc/trois.php';
loginRequired();
$viewer = new User(verCookie());
$account = new Account(verAccount());
$result_id = intval($_POST['result_id']);
$data = array();
foreach($_POST as $key=>$val){
	$key = strip_tags($key);
	$val = strip_tags($val);
	$data[$key]=$val;
}
$cdata = mysql_escape_string(serialize($data));
$con = conDB();
mysql_query("UPDATE form_results set contact_data='$cdata' WHERE id=$result_id and (SELECT account_id from forms where id=form_id)={$account->id} LIMIT 1",$con);
echo("<script>parent.$.fancybox(\"Saved\")</script>");

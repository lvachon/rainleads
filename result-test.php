<?php include 'inc/trois.php';
//lead test
$con = conDB();
$lead_id = intval($_GET['id']);
$getLead = mysql_query("SELECT `name`,`email`,`data` FROM form_results WHERE id = $lead_id",$con);
$lead = mysql_fetch_array($getLead);
$f->elems = unserialize($lead['data']);
foreach($f->elems as $el){
	if($el->data['name'] == 'phone'){
		echo $el->value;
	}
	
}
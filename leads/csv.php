<?php include '../inc/trois.php';
loginRequired();
if(!verAccount()){errorMsg("No account associated with login");}
$acct = new Account(verAccount());
if($acct->membership=="free"){errorMsg("Free accounts cannot export leads.  <a href='/account/upgrade.php'>Upgrade</a> your account.");die();}
$viewer = new User(verCookie());
header("Content-type: text/csv");
header("Content-disposition: attachment; filename=leads.csv");
$con = conDB();
	if(intval($_GET['form_id'])){
		$form = new Form($_GET['form_id']);
		$fcon = " and form_id={$form->id} ";
	}
	$sort="datestamp desc";
   	if($_GET['sort']=="new"){
   		$sort = "datestamp desc"; 
   	}
   	if($_GET['sort']=="old"){
   		$sort = "datestamp asc";
   	}
   	if($_GET['sort']=="alpha"){
   		$sort = "name asc";
   	}
   	if($_GET['sort']=="status"){
   		$sort = "(SELECT display from statuses where id=status) asc";
   	}
   	if($_GET['sort']=="code"){
   		$sort = "tracking_code asc";
   	}
   	if($_GET['sort']=="assignment"){
   		$sort = "(SELECT user_id from assignments where result_id=id) asc";
   	}
   	$filter = "";
   	if(intval($_GET['filter'])){
   		$filter = " and status=".intval($_GET['filter'])." ";
   	}
   	$assfilter = "";
   	if(intval($_GET['ass'])){
   		$assfilter = " and (SELECT user_id from assignments where result_id=id)=".strval(intval($_GET['ass']))." ";
   	}else{
   	
	   	if(intval($viewer->data['onlymyleads'])){
	   		$assfilter = " and (SELECT user_id from assignments where result_id=id)={$viewer->id}";
	   	}
   	}
   	
   	if(strlen($_GET['q'])){
   		$q = mysql_escape_string($_GET['q']);
   		$qfilter = " and (name regexp '$q' or email regexp '$q')";
   	}
   	
   	
   	$delcon = " and deleted=0 ";
   	if(intval($_GET['delcon'])){
   		$delcon= " and deleted=1 ";
   	}
		   	
		   	
if(intval($acct->data['see_all_leads']) || in_array($viewer->id,$acct->admins)){
	$qry = "form_results where length(name)>0 and length(email)>0 and form_id IN (SELECT id from forms where account_id={$account_row['id']}) {$fcon} {$filter} {$assfilter} {$qfilter} {$delcon}";
	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort}",$con);
}else{
	$qry = "form_results where length(name)>0 and length(email)>0 and id IN (SELECT result_id from assignments where user_id={$viewer->id}) {$fcon} {$filter} {$qfilter} {$delcon}";
	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort}",$con);
}
$data = array();
$columns = array();
$nodata = array('data','contact_data','deleted');
while($lead = mysql_fetch_array($res)){
	$tuser = new User($lead['assigned_user']);
	$f = new Form();
	$f->elems = unserialize($lead['data']);
	$row = array();	
	foreach($lead as $var=>$val){
		if(in_array($var,$nodata) || is_numeric($var)){continue;}
		if(!in_array($var,$columns)){
			$columns[]=$var;
		}
		$row[$var]=addslashes($val);
	}	
	foreach($f->elems as $e){
		if(!in_array($e->label,$columns)){$columns[]=$e->label;}
		if($e->type=="date"){
			$row[$e->label]=date("M jS Y",$e->value);
		}else{
			if($e->type=="time"){
				$row[$e->label]=date("g:i a",$e->value);
			}else{
				$row[$e->label]=str_replace('"','\"',$e->value);
			}
		}
		
	}
	foreach(unserialize($lead['contact_data']) as $var=>$val){
		if(!in_array($var,$columns)){
			$columns[]=$var;
		}
		$row[$var]=addslashes($val);
	}
	$data[]=$row;
}
echo implode(",",$columns);
echo "\n";


foreach($data as $row){
	$cnt=0;
	foreach($columns as $col){
		if(!strlen($col)){continue;}
		if($cnt){echo ",";}
		echo '"'.$row[$col].'"';
		$cnt++;
	}
	echo "\n";
}


<?php include '../inc/trois.php';
$viewer = new User(verCookie());
$account = new Account(verAccount());

$cc = explode(",","address1,address2,city,state,country,zip,tz,bwtc,businessname,mobile,jobtitle,dept,industry,imhandle,linkedin,facebook,website,twitter");
$ccinfo = array();
$map = array('company name'=>'businessname','position'=>'jobtitle','work address1'=>'address1','work address2'=>'address2','work city'=>'city',
			'work state'=>'state','work postal code'=>'zip','work_country'=>'country','instant message'=>'imhandle','twitter name'=>'twitter',
			'linked in url'=>'linkedin','facebook url'=>'facebook',
			'company'=>'businessname','title'=>'jobtitle','linkedin url'=>'linkedin','address - work street'=>'address1','address - work city'=>'city',
			'address - work state'=>'state','address - work zip'=>'zip','address - work country'=>'united states','phone number - mobile'=>'mobile',
			'web address - work'=>'website','twitter account - business'=>'twitter','instant messenger - work'=>'imhandle',
			'street'=>'address1','zip code'=>'zip',
			'address_1'=>'address1','address_2'=>'address2','postal_code'=>'zip',
			'company / account'=>'businessname',
			'mobile phone'=>'mobile');
$fname="";
$lname="";

if($_FILES['import']['tmp_name']){
	$c = file_get_contents($_FILES['import']['tmp_name']);
	$rows = explode("\n",$c);
	$data = array();
	$cols = str_getcsv($rows[0]);
	$usedcols = array();
	for($i=1;$i<count($rows);$i++){
	   $row = str_getcsv($rows[$i]);
	   $drow = array();
	   for($j=0;$j<count($row);$j++){
			if(strlen($row[$j])){
				$drow[$cols[$j]]=$row[$j];
				if(!in_array($cols[$j],$usedcols)){$usedcols[]=$cols[$j];}
			}
	   }
	   $data[]=$drow;
	}
	
	$title = strip_tags($_POST['title']);
	$f = new Form(array('title'=>$title));
	foreach($usedcols as $col){
		$addthis=true;
		if(in_array(strtolower(cname),$cc)){$addthis=false;continue;}//check to make sure this column isn't supposed to go into the contact info
		foreach($map as $src=>$dst){
			if(strtolower($src)==strtolower($col)){//Check to see if this is in the contact info columns or the contact import map
				$addthis=false;
				break;
			}
		}
		if($addthis){$f->addElem(new FormElement(array('name'=>strtolower($col),"label"=>$col,"type"=>"text",'required'=>'0')));}
	}
	$f->data['orig_user']=strval($viewer->id);
	$f->account_id = $account->id;
	$f->save();
	$id = $f->id;
	if(!intval($f->id)){
		die("NO FORM ID");
	}
	
	
	//Now we have the import form created, time to populate leads
	$con = conDB();
	mysql_query("UPDATE forms set deleted=1 where id={$f->id}",$con);//First we "delete" it for aesthetic/business reasons
	$r = mysql_query("SELECT * from statuses where account_id={$account->id} order by display asc limit 1",$con);
	$status = mysql_fetch_array($r);
	$status = strval(intval($status['id']));
	foreach($data as $lead){
		$f = new Form($id);
		$name = "";
		$fname="";
		$lname="";
		$email = "";
		$ccinfo=array();
		foreach($lead as $cname=>$val){
			if(in_array(strtolower($cname),$cc)){
				$ccinfo[$cname]=$val;
			}else{
				$mapped=false;
				foreach($map as $src=>$dst){
					if(strtolower($src)==strtolower($cname)){//Check to see if this is in the contact info columns or the contact import map
						$ccinfo[$dst]=$val;
						$mapped=true;
						break;
					}
				}
				if(!$mapped){
					$elem = $f->getElemByName(strtolower($cname));
					$elem->value=$val;
					$f->setElem($elem->uid,$elem);
				}
			}
			
			
			if(substr(strtolower($cname),0,4)=="name" && !strlen($name)){
				$name = ($val);
			}
			if(strtolower($cname)=="first name"){
				$fname = ($val);
			}
			if(strtolower($cname)=="last name"){
				$lname = ($val);
			}
			if(substr(strtolower($cname),0,5)=="email" && !strlen($email)){
				$email = ($val);
			}
		}
		if(!strlen($name)){
			$name = $fname." ".$lname;
		}
		if(!strlen($name)||!strlen($email)){
			$invalid_rows[] = array('data'=>$lead,'reason'=>'No name or email could be found');
			continue;
		}
		
		$qry="INSERT INTO form_results(name,email,form_id,datestamp,data,contact_data,status,tracking_code,referer,ip) VALUES('".mysql_escape_string($name)."','".mysql_escape_string($email)."',{$f->id},unix_timestamp(),'".mysql_escape_string(serialize($f->elems))."','".mysql_escape_string(serialize($ccinfo))."',{$status},\"".mysql_escape_string($title)."_import\",\"\",'".mysql_escape_string($_SERVER['REMOTE_ADDR'])."')";
		mysql_query($qry,$con);
		$rid = intval(mysql_insert_id($con));
		if(!$rid){
			$invalid_rows[] = array('data'=>$lead,'reason'=>'Database error: '.mysql_error());
		}
		
	}
	
	if(!count($invalid_rows)){
		//header("Location: index.php?form_id={$id}");
		echo "<script>parent.document.location.href='index.php?form_id={$id}';</script>";
		die();
	}else{
		?>
		<link type="text/css" rel="stylesheet" media="screen" href="<?= $HOME_URL ?>css/styles.css" />
		<h1>Import Leads</h1>
		<div class="clear"></div>
		<hr class="title-line">		
		<br/>
		<div class="strong">
		<?php
		echo count($invalid_rows) . " rows failed.";
		?>
		</div>
		<div class="strong"><?php echo strval(count($data)-count($invalid_rows)) . " rows imported. <a href='index.php?form_id={$id}' target='_parent'>View imported rows</a>";?></div><?php
	}
	
	
}else{
	?>
<html>
<head>
	<link type="text/css" rel="stylesheet" media="screen" href="<?= $HOME_URL ?>css/styles.css" />
	<style>
		.strong {
			color:#444;
		}
		input[type=text]{
			border: 1px solid #c0c0c0;
			min-width: 280px;
			padding: 4px;
		}
	</style>
</head>
	<body style="width:400px;">
		<h1>Import Leads</h1>
		<div class="clear"></div>
		<hr class="title-line">
		<form method='post' enctype='multipart/form-data' action='import.php'>
			<div class="strong">Imported Batch's Title:</div>
			<input type='text' name='title' value="import <?=date("M j Y, H:iT",time());?>"/><br/><br/>
			<div class="strong">Choose CSV File:</div>
			<input type='file' name='import'/><br/><br/>
			<input type='submit' class="button strong blue_button" style="padding:8px 20px;" value='Import Leads'/>
		</form>
	</body>
</html>
<?php } 
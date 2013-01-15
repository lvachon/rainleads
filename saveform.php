<?php include '../inc/trois.php';
$id = $_POST['form_id'];
$data = $_POST['form_data'];
$f = new Form($id);
$f->elems = unserialize(gzuncompress(base64_decode($data)));
//var_dump($xx);
$name = "";
$email = "";
$tc = mysql_escape_string($_POST['tracking_code']);
foreach($_POST as $var=>$val){
	if(!in_array($var,array('form_id','form_data','tracking_code','captcha_code','referer'))){
		$elem = $f->getElemByName($var);
		//if(!$elem->uid){continue;}
		if(is_array($val)){
			$elem->value = implode(";",$val);
		}else{
			$elem->value=$val;
		}
		$f->setElem($elem->uid,$elem);
		if(substr($elem->data['name'],0,4)=="name" && !strlen($name)){$name = mysql_escape_string($val);}
		if(substr($elem->data['name'],0,5)=="email" && !strlen($email)){$email = mysql_escape_string($val);}
	}
}

$missing = array();
$triggerError = false;
foreach($f->elems as $elem){
	if(!intval($elem->data['required'])){continue;}
	if(!strlen($elem->value)){$missing[] = $elem->title;$triggerError=true;}
}

include_once $HOME_DIR . '/captcha/securimage.php';

$securimage = new Securimage();
if(strlen($_POST['captcha_code'])){
	if ($securimage->check($_POST['captcha_code']) == false) {
		$missing[] = "The security code is incorrect.";
		$triggerError=true;
	}
}else{
	if(!verCookie()){
		$missing[] = "The security code is incorrect.";
		$triggerError=true;
	}
	
}

if($triggerError){?>
	The following required fields are missing:<br/>
	<ul>
		<li>
		<?=implode("</li><li>",$missing);?>
		</li>
	</ul><br/>
	Please go back and try again.
<? die();
}
if(!strlen($_POST['referer'])){$referer = mysql_escape_string($_SERVER['HTTP_REFERER']);}else{$referer=mysql_escape_string($_POST['referer']);}
$con = conDB();
$r = mysql_query("SELECT * from statuses where account_id={$f->account_id} order by display asc limit 1",$con);
$status = mysql_fetch_array($r);
mysql_query("INSERT INTO form_results(name,email,form_id,datestamp,data,status,tracking_code,referer) VALUES('$name','$email',{$f->id},unix_timestamp(),'".mysql_escape_string(serialize($f->elems))."',{$status['id']},\"{$tc}\",\"$referer\")",$con);
if(intval(mysql_insert_id($con))){echo nl2br($f->thankyou);}
else{echo mysql_error();}
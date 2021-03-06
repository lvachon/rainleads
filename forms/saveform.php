<?php include '../inc/trois.php'; ob_start();
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
		//if($elem->uid===false){continue;}
		if(is_array($val)){
			$elem->value = implode(";",$val);
		}else{
			$elem->value=$val;
		}
		$f->setElem($elem->uid,$elem);
		if(substr($elem->data['name'],0,4)=="name" && !strlen($name)){$name = ($val);}
		if(substr($elem->data['name'],0,5)=="email" && !strlen($email)){$email = ($val);}
	}
}

$missing = array();
$triggerError = false;
foreach($f->elems as $elem){
	if(!intval($elem->data['required'])){continue;}
	if(!strlen($elem->value)){$missing[] = $elem->title;$triggerError=true;}
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
if(!strlen($_POST['referer'])){
	$referer = mysql_escape_string($_SERVER['HTTP_REFERER']);
}else{
	$referer=mysql_escape_string($_POST['referer']);
}

include_once $HOME_DIR . '/captcha/securimage.php';

$securimage = new Securimage();

if ($securimage->check($_POST['captcha_code']) == false) {
	
	if(!verCookie()){
		?><form method='post' action='saveform.php'>
			<input type='hidden' name='form_id' value='<?=$id?>'/>
			<input type='hidden' name='form_data' value="<?=base64_encode(gzcompress(serialize($f->elems)));?>"/>
			<input type='hidden' name='tracking_code' value="<?=$_POST['tracking_code'];?>"/>
			<input type='hidden' name='referer' value="<?=htmlentities($referer);?>"/>
			<input type='hidden' name='name' value="<?=htmlentities($name);?>"/>
			<input type='hidden' name='email' value="<?=htmlentities($email);?>"/>
			<table>
			<?php $html = "";
			$html .= "<tr><td colspan=\"4\" align=\"center\">\n";
			$html .= " Your information has not been saved yet!  Please fill out the captcha below to help prevent spam. \n";
			$html .= "	<div id=\"divrecaptcha\">\n";
			$html .= "		<img id=\"captcha\" src=\"{$HOME_URL}captcha/securimage_show.php\" alt=\"CAPTCHA Image\" style=\"border:1px solid #000;\" /><!--Important-->\n";
			$html .= "		<div><a href=\"#\" onclick=\"document.getElementById('captcha').src = '{$HOME_URL}captcha/securimage_show.php?' + Math.random(); return false\"><small>Can't Read The Words?</small></a></div>\n";
			$html .= "		<br />\n";
			$html .= "		<div class=\"recaptcha_only_if_image\"><strong>Enter the words shown above</strong></div>\n";
			$html .= "		<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" />\n";
			$html .= "		<object type=\"application/x-shockwave-flash\" data=\"{$HOME_URL}captcha/securimage_play.swf?audio_file=/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000\" width=\"19\" height=\"19\">\n";
			$html .= "			<param name=\"movie\" value=\"{$HOME_URL}captcha/securimage_play.swf?audio_file=/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000\" />\n";
			$html .= "		</object>\n";
			$html .= "	</div>\n";
			$html .= "</td></tr>\n";
			echo $html; //It's in this stupid format because it's from the formclasses code which has to return a string, not echo shit.
			?>
			</table>
			<input type='submit' value='Submit'/>
		</form>
		<?php 
		die();
	}
	
}

$con = conDB();
$r = mysql_query("SELECT * from statuses where account_id={$f->account_id} order by display asc limit 1",$con);
$status = mysql_fetch_array($r);
$status = strval(intval($status['id']));

$r = mysql_query("SELECT max(display_id) from form_results where form_id in (SELECT id from forms where account_id={$f->account_id})",$con);
$did = mysql_fetch_array($r);
$did = intval($did[0])+1;


$qry="INSERT INTO form_results(name,email,form_id,datestamp,data,status,tracking_code,referer,ip,display_id) VALUES('".mysql_escape_string($name)."','".mysql_escape_string($email)."',{$f->id},unix_timestamp(),'".mysql_escape_string(serialize($f->elems))."',{$status},\"{$tc}\",\"".htmlentities($referer)."\",'".mysql_escape_string($_SERVER['REMOTE_ADDR'])."',$did)";
mysql_query($qry,$con);
$id = mysql_insert_id($con);
if(intval($id)){
	if(strlen($f->thankyou)){
		$thanks = nl2br($f->thankyou);
	}else{
		$thanks ="Thank you for your inquiry. We will be in touch shortly!";
	}
	echo "<div id='form_{$f->id}' class='form_label'>".$thanks."</div>";
	echo "<style>{$f->data['styles']}</style>";
	$account = new Account($f->account_id);
	$leadInfo = "Lead ID: {$did}<br />\n	Lead Name: {$name}<br />\n	Lead Email: {$email}<br />\n	Lead Received: ".date('n/j/Y',time());
	$mailVariables = array('%homeurl','%sitename','%receiver','%url','%post');
	$HOME = str_replace('www.',$account->subdomain.".",$HOME_URL);
	if(!verCookie()){//If it's not from someone logged in
		if($account->data['notify_members'] != '0'){//If notifications are enabled
			foreach($account->members as $um){
				$uu = new User($um);
				$mailValues = array($HOME_URL,$SITE_NAME,$uu->name(),$HOME."leads/lead.php?id=".$id,$leadInfo);
				$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_new_lead'));
				$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_new_lead')));
				htmlEmail($uu->email,$subject,$message);
			}
		}else{
			$uu = $account->getUser();
			$mailValues = array($HOME_URL,$SITE_NAME,$uu->name(),$HOME."leads/lead.php?id=".$id,$leadInfo);
			$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_new_lead'));
			$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_new_lead')));
			htmlEmail($uu->email,$subject,$message);
		}
	}
	
	saveAction('lead',$account->user_id,$id,'',$account->id,$id);
	if(verCookie() && strpos($_SERVER['HTTP_REFERER'],'rainleads.com/leads')!==false){header("Location: {$HOME_URL}leads/");die();}//If you just manually entered this, go to the leads page instead of thanking the user
}
else{echo mysql_error();}
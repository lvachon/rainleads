<?php
include 'cfg.php';
$HOME_URL = "https://".$_SERVER['HTTP_HOST']."/";
include_once 'formclasses.php';
include_once 'classes/account.php';
include_once 'classes/event.php';
//conDB()	--		Returns connection handle to MySQL or false on fail
function conDB(){  
	global $DB_HOST;
	global $DB_USER;
	global $DB_PASS;
	global $DB_DB;
	$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	if(!$con){return false;}
	if(mysql_select_db($DB_DB,$con)){return $con;}else{return false;}
}


//verCookie()	--	Returns user id of viewer(int) or false on fail
function verCookie(){
	$con = conDB();
	$r = mysql_query("SELECT id from users where secret='".mysql_escape_string($_COOKIE['secret'])."' or mobile_secret='".mysql_escape_string($_COOKIE['secret'])."' and id=".strval(intval($_COOKIE['id']))." LIMIT 1",$con);
	$u = mysql_fetch_array($r);
	if(intval($u['id'])==intval($_COOKIE['id'])){return intval($u['id']);}//Verified User
	else{//No result clear their cookies if they're trying to be a jerk
		if(strlen($_COOKIE['secret'])||strlen($_COOKIE['id'])){
			setcookie("secret","",1);
			setcookie("id","",1);
		}
	}
	return false;
}


function verAPI(){
	$con = conDB();
	$id = intval($_POST['login_id']);
	$secret = mysql_escape_string($_POST['login_secret']);
	$con = conDB();
	$r = mysql_query("SELECT * from users where id=$id and mobile_secret='$secret' LIMIT 1",$con);
	$u = mysql_fetch_array($r);
	return intval($u['id']);
	
}

function reqAPI(){
	if(!verAPI()){
		die("<xml>\n<response>Login required</response>\n</xml>");
	}
}


//errorMsg					--	Redirects user to error page with $msg displayed, aborts script execution.
function errorMsg($message,$back=true,$facebox=false){
	global $HOME_URL;
	$msg = urlencode(strip_tags($message,"<a>"));
	$ref = $_SERVER['HTTP_REFERER'];
	if($facebox){?>
		<script type="text/javascript">
			$.fancybox('<?=$message?>');
        </script>
	<?php }else{
		header("Location: {$HOME_URL}error.php?msg=".$msg);
		die();
	}
}


//cmsRead					--	Returns the text corresponding to the CMSID passed or false on fail
function cmsRead($id){
	$con = conDB();
	$r = mysql_query("SELECT content from cms where chunk_id='".mysql_escape_string($id)."' order by datestamp desc LIMIT 1",$con);
	$res = mysql_fetch_array($r);
	return $res['content'];	
}

//cmdTitle					--	Returns the title corresponding to the CMSID passed or false on fail
function cmsTitle($id){//Returns the title corresponding to the CMSID passed or false on fail
	$con = conDB();
	$r = mysql_query("SELECT title from cms where chunk_id='".mysql_escape_string($id)."' order by datestamp desc LIMIT 1",$con);
	$res = mysql_fetch_array($r);
	return $res['title'];	
}

//loginRequired()			--	Redirects user to error page if not logged in, used to simplify page locking.	
function loginRequired(){
	global $HOME_URL;
	if(!intval(verCookie())){
		header("Location: {$HOME_URL}index.php?referer=".urlencode($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']));
		die();
	}
}

//mobileLoginRequired()			--	Redirects mobile-web user to error code if not logged in, used to simplify view locking.	
function mobileLoginRequired(){
	global $HOME_URL;
	if(!intval(verCookie())){
		header("Location: {$HOME_URL}webview/error.php?success=false&code=999&msg=".urlencode("Please login!")."&referer=".urlencode($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']));
		die();
	}
}

//trunc($str,$len)			--	Truncates the string if longer than $len and adds an ellipsis as well.
function trunc($str,$len){
	if(strlen($str)>$len){
		return substr($str,0,$len-1)."&hellip;";
	}else{
		return $str;
	}
}

//pageLinks($q,$p,$P,$t)	--	Returns pagination HTML based on the query and page parameters
function pageLinks($qry,$page,$per_page = 20,$target){
	$con = conDB();
	$r = mysql_query("SELECT count(*) FROM $qry",$con);
	$count = mysql_fetch_array($r);
	$count = $count[0];
	
	if ($page>=998) { $diff=3; } else if ($page>=97) { $diff=4; } else { $diff=5; }
	
	for($tp=max(array($page-$diff,1));$tp<=min(array(ceil($count/$per_page),$page+$diff));$tp++)
	{
		if($tp!=$page){$html .= "<a href='{$target}&page={$tp}' class='button'>$tp</a>";}
		
	}
	if($page>1){
		$html = "<a href='{$target}&page=".strval($page-1)."' class='button'>Prev</a> ".$html;
	}
	if($page<ceil($count/$per_page)){
		$html .= "<a href='{$target}&page=".strval($page+1)."' class='button''>Next</a>";
	}
	return $html;	
}



//htmlEmail()		--	Sends an email with the necessary headers, but only if the user enables notifications or it's forced thru
function htmlEmail($to,$subject,$message,$force=false) {
	global $FROM_HEADER;
	global $DOMAIN;
	global $SITE_NAME;
	global $HOME_URL;
	global $HOME_DIR;

	// To send HTML mail, the Content-type header must be set
	$headers = "MIME-Version: 1.0\n"; 
	$headers = 'Content-type: text/html'."\n";
	
	//Check to see if the recipient wants to get this email.
	$con = conDB();
	$r = mysql_query("SELECT getData('noemail',data) from users where lcase(email) = lcase('".mysql_escape_string($to)."') LIMIT 1",$con);
	$noemail = mysql_fetch_array($r);
	$noemail=$noemail[0];
	if(intval($noemail) && !$force){
		//noemail flag is set, and wer're not forcing this message.  do not send an email to this person
		return false;
	}else{
		//No opt-out flag set, and/or this message is being forced through send it	
		// Additional headers
		//$headers .= 'To: '.$to. '\r\n';
		$headers .= "From: '".$SITE_NAME."' <no-reply@".$DOMAIN.">\n"; 
		$headers .= "Reply-To: '".$SITE_NAME."' <admin@".$DOMAIN.">\n"; 
		$headers .= "Return-Path: '".$SITE_NAME."' <admin@".$DOMAIN.">\n"; 
		$headers .= "\n"; 
		$chkUser = mysql_query("SELECT id FROM users WHERE lcase(email) = lcase('".mysql_escape_string($to)."') LIMIT 1",$con);
		$use = mysql_fetch_array($chkUser);
		if(intval($use[0])){
			$user = new User($use[0]);
			$name = $user->name('F');
		}else{
			$name = $to;
		}
		
		include 'mail_head.php';
		include 'mail_footer.php';
		$msg = $header.$message.$footer;
		return mail($to, $subject, $msg, $headers, "-fno-reply@{$DOMAIN}");
	}
}

function htmlEmail2($to,$subject,$message,$force=false) {
	global $FROM_HEADER;
	global $DOMAIN;
	global $SITE_NAME;
	global $HOME_URL;
	global $HOME_DIR;

	// To send HTML mail, the Content-type header must be set
	$headers = "MIME-Version: 1.0\n"; 
	$headers = 'Content-type: text/html'."\n";
	
	//Check to see if the recipient wants to get this email.
	$con = conDB();
	$r = mysql_query("SELECT getData('noemail',data) from users where lcase(email) = lcase('".mysql_escape_string($to)."') LIMIT 1",$con);
	$noemail = mysql_fetch_array($r);
	$noemail=$noemail[0];
	if(intval($noemail) && !$force){
		//noemail flag is set, and wer're not forcing this message.  do not send an email to this person
		return false;
	}else{
		//No opt-out flag set, and/or this message is being forced through send it	
		// Additional headers
		//$headers .= 'To: '.$to. '\r\n';
		$headers .= "From: '".$SITE_NAME."' <no-reply@".$DOMAIN.">\n"; 
		$headers .= "Reply-To: '".$SITE_NAME."' <admin@".$DOMAIN.">\n"; 
		$headers .= "Return-Path: '".$SITE_NAME."' <admin@".$DOMAIN.">\n"; 
		$headers .= "\n"; 
		$chkUser = mysql_query("SELECT id FROM users WHERE lcase(email) = lcase('".mysql_escape_string($to)."') LIMIT 1",$con);
		$use = mysql_fetch_array($chkUser);
		if(intval($use[0])){
			$user = new User($use[0]);
			$name = $user->name('F');
		}else{
			$name = $to;
		}
		
		include 'mail_head.php';
		include 'mail_footer.php';
		$msg = $header.$message.$footer;
		return mail($to, $subject, $msg, $headers, "-fno-reply@{$DOMAIN}");
	}
}


function saveAction($type,$user_id,$content_id=0,$aux_data='',$account_id=0,$lead_id = 0){
	$type = mysql_escape_string(strtolower($type));
	$user_id = intval($user_id);
	if(!$user_id){return false;}
	$content_id = intval($content_id);
	$account_id = intval($account_id);
	$aux_data=mysql_escape_string($aux_data);
	$lead_id = intval($lead_id);
	if(!strlen($aux_data)){$aux_data='';}
	$con = conDB();
	$r = mysql_query("INSERT INTO actions(type,user_id,content_id,aux_data,datestamp,account_id,lead_id) VALUES('$type',$user_id,$content_id,'$aux_data',".time().",$account_id,$lead_id)",$con) ;
	return intval(mysql_insert_id($con));
}

function yearSelect($dir="past",$amt=100,$selected) {
	$html = "";
	if ($dir == "future") {
		$from = date("Y")+$amt;
	} else {
		$from = date("Y")-$amt;	
	}
	foreach(range(date("Y"),$from) as $x){
		$html .= "<option value='{$x}'";
		if($selected==$x) { $html .= " selected='selected'"; }
		$html .= ">{$x}</option>";
	}
	return $html;
}

//timeAgo($time)		--	Returns an english representation of the gap between now and a previous time.
function timeAgo($time)
{
	$int = time() - $time;
	if($int<60){return "" . strval(floor($int)) . " seconds ago";}
	if($int<3600){return "" . strval(floor($int/60)) . " minutes ago";}
	if($int<7200){return "1 hour ago";}
	if($int<86400){return "" . strval(floor($int/3600)) . " hours ago";}
	if($int<172800){return "Yesterday";}
	if($int<604800){return date("l",$time)." at " . date("g:iA",$time);}
	if($int>604800 && date("Y",$time)==date("Y",time()))
	{
		return date("F jS", $time);
		break;
	}else{
		return date("M jS Y",$time);
		break;
	}
	return "A long time ago";
}

/*probably not used
$shown = array();
function advBanner($format=0)
{
	global $shown;
	$con = conDB();
	$format = intval($format);
	$page = mysql_escape_string(basename($_SERVER['SCRIPT_NAME'],'.php'));
	$r = mysql_query("SELECT * from ads where page=\"$page\" and format=$format and active=1 order by RAND() asc",$con);
	if(!mysql_num_rows($r)){
		//We have no ads for this format and page
		$r = mysql_query("SELECT * from ads where page=\"DEFAULT\" and format=$format and active=1 order by RAND() asc",$con);
		if(!mysql_num_rows($r)){return false;}
	}
	$show = array();
	$adRows = array();
	while($ad = mysql_fetch_array($r)){
		//Just check for new ads.
		if(!in_array($ad['id'],$shown)){$show=$ad;}
	}
	//At this point we should have an ad to show, if there are any non dupes to show.
	if(!$show['id']){return false;}//We don't however, so fail everything.
	else{
		//That is true, show the code and add it to the "Seen" list
		echo $show['code'];
		array_push($shown,$show['id']);
		mysql_query("UPDATE ads set views=views+1 where id={$show['id']}",$con);
	}
}*/

function clickme($text) 
{ 
	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text); 
	
	$ret = ' ' . $text; 
	
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret); 
	
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret); 
	
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret); 
	
	$ret = substr($ret, 1); 
	return $ret; 
} 

if(verCookie()){
		$viewer = new User(verCookie());
}

$sd = explode(".",$_SERVER['HTTP_HOST']);
if(count($sd)<3 || !strlen($sd[0])){header("Location: http://www.{$_SERVER['HTTP_HOST']}");die();}

function verAccount(){
	global $viewer,$account_row;
	if(!verCookie()){return false;}
	$con = conDB();
	$sd = explode(".",$_SERVER['HTTP_HOST']);
	$qry="SELECT * from membership where user_id = {$viewer->id} and account_id in (SELECT id from accounts where lcase(subdomain)=lcase('".mysql_escape_string($sd[0])."'))";
	$r = mysql_query($qry,$con);
	$x = mysql_fetch_array($r);
	return intval($x['account_id']);
}

function showAccount(){
	$con = conDB();
	$sd = explode(".",$_SERVER['HTTP_HOST']);
	$qry="SELECT id from accounts where lcase(subdomain)=lcase('".mysql_escape_string($sd[0])."')";
	$r = mysql_query($qry,$con);
	$x = mysql_fetch_array($r);
	$account = new Account($x['id']);
	return $account;
}


if(verAccount()){
	global $account_row;
	$con = conDB();
	$r = mysql_query("SELECT * from accounts where id=".verAccount(),$con);
	$account_row = mysql_fetch_array($r);
}

function accountRequired(){
	if(!verAccount()){
		errorMsg("We cannot verify your account please make sure you are logging in under the correct subdomain.");
	}
}

function accountAdminOnly(){
	global $account_row;
	global $viewer;
	if(!verAccount()){
		errorMsg("We cannot verify your account please make sure you are logging in under the correct subdomain.");
	}
	if($account_row['user_id'] != $viewer->id){
		errorMsg("You must be the administrator of this account to access this information.");
	}
}

if(verAccount()){
	$account = new Account(verAccount());
	if($account->membership=="free" && $account->expiration<time() && !in_array($_SERVER['SCRIPT_NAME'],array("/account/upgrade.php","/error.php","/account/pay.php","/login.php"))){
		errorMsg("You free trial has expired. Please <a href='{$HOME_URL}account/upgrade.php'>upgrade</a> to a paid account.");
		die();
	}
}
date_default_timezone_set('UTC');
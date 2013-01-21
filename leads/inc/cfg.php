<?php
/*
 * ---------------------
 * Configuration Section
 * 
 * Network name config
 * $SITE_NAME  -- 	Display name of the network, for showing to humans
 * $HOME_URL   -- 	Complete URL to the root directory of the network
 * $DOMAIN     --   The domain of the network only (no subdirs)
 * $CONTACT    --   The Primary contact for the network, who will receive feedback flagged content ect...
 *
 * Database Config
 * $DB_HOST		--	MySQL host (usually localhost)  
 * $DB_USER		--  MySQL user (created in plesk or phpmyadmin)
 * $DB_PASS		--  Password of aforementioned user
 * $DB_DB		--  MySQL database to use once connected (ensure sufficient permissions are GRANTed)
 *
 * Database Config
 * $FB_APP		--	Facebook Application ID#
 * $FB_API		--  Facebook API Public Key
 * $FB_SEC		--  Facebook API Secret Key
 * $TW_CON_KEY  --  Twitter App Consumer Key
 * $TW_CON_SEC  --  Twitter App Consumer Secret
 *
 * Function Configuration
 * $NOT_DATA	--	Array of POST variables that shouldn't be saved in the data object
 * 
 *  */

// NETWORK BASIC CONFIG
$SITE_NAME = "RainLeads";
$HOME_URL  = "https://www.rainleads.com/";
$DOMAIN    = "rainleads.com";
$HOME_DIR  = "/var/www/vhosts/mocircles.com/httpdocs";
$SITE_CONTACT = "info@rainleads.com";//"admin@{$DOMAIN}";//this is used for send feedback.php
$PER_PAGE = 20;//this is used for the wall autoscroll
$LOGGED_OUT_PAGES = array('/why.php','/why2.php','/plans.php','/index2.php','/lead-management.php','/index.php','/virtual-business-cards.php','/about.php','/faq.php','/terms.php','/privacy.php','/enterprise.php','/contact.php','/customForms.php','/signup.php','/contact-form-builder.php','/facebook-forms.php','/error.php','/tour.php'); 


if(strlen($_COOKIE['subdomain'])){
	$sub = str_replace('www.',$_COOKIE['subdomain'].".",$HOME_URL);
	//header("Location: {$sub}index.php");
}
// DATABASE CONFIG
$DB_HOST  = "localhost";
$DB_USER  = "rl";
$DB_PASS  = "rldbpass";
$DB_DB    = "new_rainleads";

// FACEBOOK CONNECT CONFIG
$FB_APPID   = "138120169561544";
$FB_API     = "963c452b2c7eb9a138731707ad281c09";
$FB_SECRET  = "3d245df7349ab5eefb097e110c3aefeb";
$TW_CON_KEY = "TfDU3bjusq8ksr2ucNJXrw";
$TW_CON_SEC = "Pz5fFSdY2AopjfVE8Y2WwNqrNJvb9lCxNbzfkhFuBo";
// FUNCTION CONFIG
$NOT_DATA = array("id","fb_id","tw_id","promoEnds","promoUsed");

$ADMIN_IDS = fgetcsv(fopen("admin/adminids","r"));
if(!count($ADMIN_IDS)){$ADMIN_IDS = fgetcsv(fopen("../admin/adminids","r"));}
if(!count($ADMIN_IDS)){$ADMIN_IDS = fgetcsv(fopen("adminids","r"));}
if(!count($ADMIN_IDS)){$ADMIN_IDS = array(0);}


// HTML EMAIL CONFIG
$PRIMARY_CONTACT   = "kvachon@pearsestreet.com";
$FROM_HEADER = "From: no-reply@{$DOMAIN}";

$SUB_PLANS = array(array("name"=>"free","price"=>"0","storage"=>2,"users"=>15,'forms'=>PHP_INT_MAX),array("name"=>"lite","price"=>"9","storage"=>50,'users'=>1,'forms'=>1),array("name"=>"basic","price"=>"29","storage"=>100,'users'=>5,'forms'=>2),array("name"=>"pro","price"=>"49","storage"=>250,'users'=>15,'forms'=>PHP_INT_MAX));



class User
{
	public $id=0;
	public $email = "";
	public $datestamp = "";
	public $data = array('fname'=>'Nobody','lname'=>'Anonymous','username'=>'nobody');
	
	
	function __construct($id=0){
		$id = intval($id);
		if(!$id){return false;}
		return $this->load($id);	
	}
	
	function load($id){
		$con = conDB();
		$r = mysql_query("SELECT * from users where id=".strval(intval($id))." LIMIT 1",$con);
		$u = mysql_fetch_array($r);
		if(!intval($u['id'])){ return false; }
		$this->id=intval($u['id']);
		$this->email = $u['email'];
		$this->datestamp = $u['datestamp'];
		$this->data=unserialize($u['data']);
		return true;
	}
	
	function getAccount($id=0){
		$account = new Account(verAccount());
		return $account;
	}
	
	function accounts(){
		$con = conDB();
		$rtn = array();
		//this is just temporary 
		$q = mysql_query("SELECT account_id FROM membership WHERE user_id = {$this->id}",$con) or die(mysql_error());
		while($r = mysql_fetch_array($q)){
			$rtn[]=$r['account_id'];
		}
		return $rtn;
	}
	
	function save(){
		global $ADMIN_IDS;
		$viewer = new User(verCookie());
		if(!intval($this->id)){return false;}
		//if($this->id!=$viewer->id && !in_array($viewer->id,$ADMIN_IDS)) { return false; }
		if(intval($this->id)){
			$id=$this->id;
		}else{
			return false;
		}
		$gooddata = array();
		foreach($this->data as $key=>$val){//build clean data array, remove empty elements (which break getData SQL function)
			if(strlen($key) && strlen($val) && !is_numeric($key)){
				$gooddata[$key]=strval($val);
			}
		}
		$data = mysql_escape_string(serialize($gooddata));
		$con = conDB();
		mysql_query("UPDATE `users` SET `data` = '$data' WHERE `id` = $this->id LIMIT 1",$con);
		return mysql_affected_rows($con);
	}
	
	function postSave(){
		global $NOT_DATA;
		foreach($_POST as $key=>$val){
			if(!is_numeric($key) && !in_array($key, $NOT_DATA)){
				$this->data[$key]=$val;
			}
		}
		return $this->save();
	}
	
	function modifyData($field,$value){
		if(!intval($this->id)){return false;}
		$this->data[$field]=$value;
		return $this->save();
	}
	
	
	function login($subdomain = ''){
		global $HOME_URL,$DOMAIN;
		if(!intval($this->id)){return false;}
		for($d=0;$d<rand(10,15);$d++){
			$secret = md5(time()*$this->id+0xDEADBEEF+rand(0,65535));
		}
		$con = conDB();
		$r = mysql_query("UPDATE users set secret='".mysql_escape_string($secret)."' WHERE id={$this->id} LIMIT 1",$con);
		setcookie("secret",$secret,time()+86400,"/",".".$DOMAIN);
		setcookie("id",$this->id,time()+86400,"/",".".$DOMAIN);
		setcookie("subdomain",showAccount()->subdomain,time()+(86400*365),"/",".".$DOMAIN);
		$this->data['last_login'] = strval(time());
			$this->save();
		return true;
	}
	
	function logout(){
		global $HOME_URL,$DOMAIN;
		if(!intval($this->id)){return false;}
		$r = mysql_query("UPDATE users set secret='' WHERE id={$this->id} LIMIT 1",$con);
		setcookie("secret",'',time()-86400,'/','.'.$DOMAIN);
		setcookie("id",'',time()-86400,'/','.'.$DOMAIN);
		return true;
	}
	
	function name($format = "F L",$link = false) {
		global $HOME_URL;
		$out = "";
		if($link){ $out.="<a href='{$HOME_URL}profile/?id={$this->id}'>"; }
		for($d=0;$d<strlen($format);$d++)
		{
			$f = substr($format,$d,1);
			if($f=="F"){$out.=ucfirst($this->data['fname']);}
			elseif($f=="L"){$out.=ucfirst($this->data['lname']);}
			elseif($f=="f"){$out.=strtoupper(substr($this->data['fname'],0,1)).".";}
			elseif($f=="l"){$out.=strtoupper(substr($this->data['lname'],0,1)).".";}
			elseif($f=="u"){$out.=strtoupper(substr($this->data['username'],0,1)).".";}
			else{$out.=$f;}
		}
		if($link){ $out .= "</a>"; }
		return $out;		
	}
	
	
	
	
	
	function avatar($maxW,$maxH,$link=false,$cache=false){
		global $HOME_URL;
		global $HOME_DIR;
		if ($maxH==0) { $maxH = $maxW; }
		if(!intval($this->id)){return false;}
		if (file_exists($HOME_DIR."/avatar/{$this->id}.jpg")){
			$src = $HOME_URL."avatar/{$this->id}.jpg";
			$path = $HOME_DIR."/avatar/{$this->id}.jpg";
		} else {
			$src = $HOME_URL."avatar/d.jpg";
			$path = $HOME_DIR."/avatar/d.jpg";
		}
		$size = getimagesize($path);
		if ($size !== false) {
			$w = $size[0];
			$h = $size[1];
			$offsetY=0;
			$offsetX=0;
			$oRatio = $w/$h;
			$wRatio = $maxW/$maxH;
			
			if ($oRatio == $wRatio) {
				$h = round(($h*$maxW) / $w);
				$w = $maxW;
			} elseif ($oRatio < $wRatio) {
				$h = round(($h*$maxW) / $w);
				$w = $maxW;
				$offsetY = floor(($h-$maxH)/2);
			}else{
				$w = round(($w*$maxH) / $h);
				$h = $maxH;
				$offsetX = floor(($w-$maxW)/2);
			}
		}
		$html="";
		if($link){$html.="<a href='{$HOME_URL}profile/?id={$this->id}'>";}
		$html .="<div class='user' title='".$this->name()."' style='width: {$maxW}px; height: {$maxH}px; overflow: hidden;'><img src='{$src}";
		if($cache == false) $html .= "?".time();
		$html .= "' border='0'  style='";
		if (intval($offsetX)) { $html .= "margin-left: -{$offsetX}px; "; } else if (intval($offsetY)) { $html .= "margin-top: -{$offsetY}px; "; }
		$html .= "width: {$w}px; height: ";
		if(intval($force)) { $html .= $force; } else { $html .= $h; }
		$html .= "px;' /></div>";
		if($link){$html .="</a>";}
		return $html;	
	}
	
	function thumbHTML($maxW,$maxH,$link=false){
		return $this->avatar($maxW,$maxH,$link);
	}
	
	function reminders($event_id){
		$con = conDB();
		$rtn = array();
		$getReminders = mysql_query("SELECT * FROM reminders WHERE event_id = $event_id AND user_id = {$this->id} ORDER BY datestamp ASC",$con);
		while($r = mysql_fetch_object($getReminders)){
			$rtn[]=$r;
		}
		return $rtn;
	}
	
	function avatarSrc(){
		global $HOME_URL;	
		global $HOME_DIR;
		if(!intval($this->id)){return false;}
		$html='';
		if(file_exists($HOME_DIR."/avatar/{$this->id}.jpg")){$html .="{$HOME_URL}avatar/{$this->id}.jpg";}		
		else{$html .="{$HOME_URL}avatar/d.jpg";}
		return $html;		
	}
	
	function delete(){
		global $ADMIN_IDS;
		if(!$this->id){return false;}
		if($this->id != verCookie() && !in_array(verCookie(),$ADMIN_IDS)){
			return false;
		}
		$id = $this->id;
		$con = conDB();
		$tables = array('usersa'=>'id','membershipa'=>'user_id','newseventsa'=>'user_id','newseventsb'=>'aux_user');
		foreach($tables as $table => $field){
			$table = substr($table,0,-1);
			mysql_query("DELETE FROM {$table} WHERE {$field} = $id",$con);
		}
		unlink($_SERVER['DOCUMENT_ROOT']."/avatars/{$id}.jpg");
		foreach($this->accounts() as $a){
			$acc = new Account($a);
			$acc->delete();
		}
		mysql_query("DELETE FROM mail WHERE thread_id REGEXP('.*(^|,){$id}($|,).*')",$con);
		//let's check if they were an admin and remove them if needed
		$ids = array();
		foreach($ADMIN_IDS as $i){
			if(intval($i)!=intval($id)){
				$ids[]=intval($i);
			}
		}
		$f = fopen($HOME_DIR."admin/adminids","w");
		fwrite($f,implode(",",$ids));
		fclose($f);
		return true;
	}
	
}
?>

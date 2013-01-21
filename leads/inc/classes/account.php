<?php class Account
{
	public $id=0;
	public $title = "";
	public $user_id = 0;
	public $subdomain = "";
	public $data = array();
	public $membership = "basic";
	public $members = array();
	public $admins = array();
	public $expiration = '';
	public $plandata = array();
	public $sub_id = "";
	public $mo_price=0;
	
	function __construct($id=0){
		$id = intval($id);
		if(!$id){return false;}
		return $this->load($id);	
	}
	
	function storageUsed(){
		global $HOME_DIR;
		$con = conDB();
		$r = mysql_query("SELECT id from proposals where account_id={$this->id}",$con);
		$bytes=0;
		while($f = mysql_fetch_array($r)){
			$bytes += filesize($HOME_DIR."/proposals/{$f['id']}");
		}
		return $bytes;
	}
	
	function formLimit(){
		return $this->plandata['forms'];
	}
	
	function storageLimit(){
		global $SUB_PLANS;
		$limit = 0;
		$limit+= $this->plandata['storage']*1024*1024;//bytes
		$con = conDB();
		$r = mysql_query("SELECT count(*) from transactions where type='add_storage' and account_id={$this->id}",$con);
		$add = mysql_fetch_array($r);
		$add = intval($add[0]);
		$r = mysql_query("SELECT count(*) from transactions where type='rem_storage' and account_id={$this->id}",$con);
		$rem = mysql_fetch_array($r);
		$rem = intval($rem[0]);
		$total = $add-$rem;
		return $limit+$total*100*1024*1024;
	}
	
	function userLimit(){
		global $SUB_PLANS;
		$limit = 0;
		$limit+= $this->plandata['users'];
		$con = conDB();
		$r = mysql_query("SELECT count(*) from transactions where type='add_user' and account_id={$this->id}",$con);
		$add = mysql_fetch_array($r);
		$add = intval($add[0]);
		$r = mysql_query("SELECT count(*) from transactions where type='rem_user' and account_id={$this->id}",$con);
		$rem = mysql_fetch_array($r);
		$rem = intval($rem[0]);
		$total = $add-$rem;
		return $limit+$total;
	}
	
	function load($id){
		global $SUB_PLANS;
		$con = conDB();
		$r = mysql_query("SELECT * from accounts where id=".strval(intval($id))." LIMIT 1",$con);
		$u = mysql_fetch_array($r);
		if(!intval($u['id'])){ return false; }
		$this->id=intval($u['id']);
		$this->title = $u['title'];
		$this->subdomain = $u['subdomain'];
		$this->user_id = intval($u['user_id']);
		$this->data=unserialize($u['data']);
		
		$this->expiration = intval($u['expires']);
		$this->membership = $u['plantype'];
		$this->sub_id = $u['sub_id'];
		$this->mo_price = intval($u['mo_price']);
		foreach($SUB_PLANS as $plan){
			if($plan['name']==$this->membership){
				$this->plandata = $plan;
			}
		}
		
		$getMembers = mysql_query("SELECT * FROM membership WHERE account_id = {$this->id}",$con);
		while($m = mysql_fetch_array($getMembers)){
			$this->members[]=$m['user_id'];	
			if($m['role'] == 'admin'){
				$this->admins[]=$m['user_id'];
			}
		}
		
		
		
		return true;
	}
	
	function getUser(){
		$user = new User($this->user_id);
		return $user;
	}
	
	function save(){
		global $ADMIN_IDS;
		$viewer = new User(verCookie());
		if(!intval($this->id)){return false;}
		if($this->user_id!=$viewer->id && !in_array($viewer->id,$ADMIN_IDS)) { return false; }
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
		$title = mysql_escape_string(strip_tags($this->title));
		$subdomain = mysql_escape_string(strip_tags($this->subdomain));
		$con = conDB();
		$qry="UPDATE `accounts` SET `data` = '$data', `title` = '$title', subdomain = '$subdomain' WHERE `id` = $this->id LIMIT 1";
		mysql_query($qry,$con);
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
	
	function forms(){
		if(!intval($this->id)){return false;}
		$con = conDB();
		$rtn = array();
		$getForms = mysql_query("SELECT id FROM forms WHERE account_id = {$this->id}",$con);
		while($f = mysql_fetch_array($getForms)){
			$rtn[]= new Form($f['id']);
		}
		return $rtn;
	}
	
	function invites(){
		if(!intval($this->id)){return false;}
		$con = conDB();
		$rtn = array();
		$getInvites = mysql_query("SELECT email FROM invites WHERE account_id = {$this->id} ORDER BY email ASC",$con);
		while($r = mysql_fetch_array($getInvites)){
			$rtn[]=$r['email'];
		}
		return $rtn;
	}
	
	function statuses(){
		if(!intval($this->id)){return false;}
		$con = conDB();
		$rtn = array();
		$getInvites = mysql_query("SELECT * FROM statuses WHERE account_id = {$this->id} ORDER BY `display` ASC",$con);
		while($r = mysql_fetch_object($getInvites)){
			$rtn[]=$r;
		}
		return $rtn;
	}
	
	function leads(){
		if(!intval($this->id)){return false;}
		$con = conDB();
		$rtn = array();
		$getLeads = mysql_query("SELECT * FROM form_results WHERE form_id IN(SELECT id FROM forms WHERE account_id = {$this->id}) AND deleted = 0 ORDER BY datestamp DESC",$con);
		while($r = mysql_fetch_object($getLeads)){
			$rtn[]=$r;
		}
		return $rtn;
	}
	
	function milestones(){
		if(!intval($this->id)){return false;}
		$con = conDB();
		$rtn = array();
		$getMilestones = mysql_query("SELECT * FROM milestones WHERE account_id = {$this->id} ORDER BY `display` ASC",$con);
		while($r = mysql_fetch_object($getMilestones)){
			$rtn[]=$r;
		}
		return $rtn;
	}
	
	function thumbHTML($maxW,$maxH){
		global $HOME_URL;
		global $HOME_DIR;
		if ($maxH==0) { $maxH = $maxW; }
		if(!intval($this->id)){return false;}
		if (file_exists($HOME_DIR."/account/files/{$this->id}.jpg")){
			$src = $HOME_URL."account/files/{$this->id}.jpg";
			$path = $HOME_DIR."/account/files/{$this->id}.jpg";
		} else {
			$src = $HOME_URL."img/sm-logo.png";
			$path = $HOME_DIR."/img/sm-logo.png";
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
		$html .="<div class='user' title='".$this->title."' style='width: {$maxW}px; height: {$maxH}px; overflow: hidden;'><img src='{$src}";
		if($cache == false) $html .= "?".time();
		$html .= "' border='0'  style='";
		if (intval($offsetX)) { $html .= "margin-left: -{$offsetX}px; "; } else if (intval($offsetY)) { $html .= "margin-top: -{$offsetY}px; "; }
		$html .= "width: {$w}px; height: ";
		if(intval($force)) { $html .= $force; } else { $html .= $h; }
		$html .= "px;' /></div>";
		return $html;	
	}
	
	
	function delete(){
		global $ADMIN_IDS;
		if(!$this->id){return false;}
		if($this->user_id != verCookie() && !in_array(verCookie(),$ADMIN_IDS)){
			return false;
		}
		$id = $this->id;
		$con = conDB();
		$tables = array('membershipa'=>'account_id','assignmentsa'=>'account_id','transactionsa'=>'account_id','invitesa'=>'account_id','accountsa'=>'id');
		foreach($tables as $table => $field){
			$table = substr($table,0,-1);
			mysql_query("DELETE FROM {$table} WHERE {$field} = $id",$con);
		}
		foreach($this->members as $i){
			$m = new User($i);
			if(!count($m->accounts())){
				$m->delete();
			}
		}
		foreach($this->forms() as $f){
			$f->delete();
		}
		return true;
	}
}?>
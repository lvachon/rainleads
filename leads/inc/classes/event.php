<?php
class Event{
	public $id=0;
	public $title="";
	public $description="";
	public $datestamp = 0;
	public $user_id = 0;
	public $start_time=0;
	public $end_time=0;
	public $data = array();
	public $account_id = 0;
	public $lead_id = 0;
		
	function __construct($id){
		$id = intval($id);
		if(!intval($id)){return false;}
		return $this->load($id);
	}
	
	function load($id){
		global $HOME_DIR;
		global $HOME_URL;
		$id = intval($id);
		if(!$id){return false;}
		$con = conDB();
		$r = mysql_query("SELECT * FROM `events` WHERE `id` = $id",$con);
		$g = mysql_fetch_array($r);
		if(!intval($g['id'])){return false;}
		$this->id = intval($g['id']);
		$this->title = $g['title'];
		$this->description = $g['description'];
		$this->datestamp = intval($g['datestamp']);
		$this->user_id = intval($g['user_id']);
		$this->start_time = intval($g['start_time']);
		$this->end_time = intval($g['end_time']);
		$this->data = unserialize($g['aux_data']);
		$this->lead_id = intval($g['lead_id']);
		$this->account_id = intval($g['account_id']);
	}
	
	function getLead(){
		$con = conDB();
		if(!intval($this->lead_id)){
			return false;
		}
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$this->lead_id}",$con);
		$lead = mysql_fetch_object($getLead);
		return $lead;
	}
	
	function getUser(){
		$u = new User($this->user_id);
		return $u;
	}
	
	function save(){
		global $ADMIN_IDS;
		global $viewer;
		if(!intval($this->id)){return false;}
		if($this->user_id!=$viewer->id && !in_array($viewer->id,$ADMIN_IDS)) { return false; }
		$id = intval($this->id);
		$title = mysql_escape_string(strip_tags($this->title));
		$description = mysql_escape_string(strip_tags($this->description));
		$tags = mysql_escape_string(strip_tags(implode(",",$this->tags)));
		$aux_data = mysql_escape_string(strip_tags(serialize($this->data)));
		$start_time = intval($this->start_time);
		$end_time = intval($this->end_time);
		$con = conDB();
		$r = mysql_query("UPDATE events SET `title` = '$title', `description` = '$description', `aux_data` = '$aux_data', `start_time` = $start_time, `end_time` = $end_time WHERE `id` = $id LIMIT 1",$con);
		return intval(mysql_affected_rows($r));
	}
	
	
    function when() {
		session_start();
		$timezone = $_SESSION['time'];
		date_default_timezone_set($timezone);
		$html= date('n/j/y g:iA',$this->start_time);
        return $html;
     }
	 
	 function delete(){
	 	$viewer = new User(verCookie());
		global $ADMIN_IDS;
		if($this->user_id != $viewer->id && !in_array($viewer->id,$ADMIN_IDS)){
			return false;
		}
		$con = conDB();
		//tables associated with events are featured, comments, news_events, RSVP, and events
		mysql_query("DELETE FROM `events` WHERE `id` = {$this->id} LIMIT 1",$con);
		mysql_query("DELETE FROM `comments` WHERE `type` = 'event' AND `thread_id` = {$this->id}",$con);
		mysql_query("DELETE FROM `news_events` WHERE `type` = 'event' AND `content_id` = {$this->id}",$con);
		mysql_query("DELETE FROM `reminders` WHERE event_id = {$this->id}",$con);
		//now delete the photo files
		return true;
	 }
	
}
?>
<?php include_once '../inc/trois.php';
/*GLOBAL SHIZ HERE*/
$table = "mail";

define("DEF_MAIL_LIMIT",20);
define("DEF_MAIL_NEW_ON_TOP",false);
Class Mail{
	
	private $user_id=0;
	
	function __construct($user_id){
		return $this->init($user_id);
	}
	
	public function init($user_id){
		if(!intval($user_id)){return false;}
		$this->user_id = $user_id;
		return true;
	}

	public function getConvos($archived = false,$offset=0,$limit=DEF_MAIL_LIMIT){
		global $table;
		$con = conDB();
		if($archived === true){
			$r = mysql_query("SELECT DISTINCT(id) FROM conversations WHERE ((thread_id REGEXP '.*,{$this->user_id}$' AND user2_hide = 1 AND user1_hide = 0) OR (thread_id REGEXP '^{$this->user_id},.*' AND user1_hide = 1 AND user2_hide = 0)) ORDER BY datestamp DESC LIMIT $offset,$limit",$con);
			//echo "SELECT DISTINCT(id) FROM conversations WHERE ((thread_id REGEXP '.*,{$this->user_id}$' AND user2_hide = 1 AND user1_hide = 0) OR (thread_id REGEXP '^{$this->user_id},.*' AND user1_hide = 1 AND user2_hide = 0)) ORDER BY datestamp DESC LIMIT $offset,$limit";
		}elseif($archived == 'all'){
			$r = mysql_query("SELECT DISTINCT(id) FROM conversations WHERE 1 ORDER BY datestamp DESC LIMIT $offset,$limit",$con);
			//echo "SELECT DISTINCT(id) FROM conversations WHERE 1 ORDER BY datestamp DESC LIMIT $offset,$limit";
		}else{
			$r = mysql_query("SELECT DISTINCT(id) FROM conversations WHERE ((thread_id REGEXP '.*,{$this->user_id}$' AND user2_hide = 0 AND user1_hide = 0) OR (thread_id REGEXP '^{$this->user_id},.*' AND user2_hide = 0 AND user1_hide = 0)) ORDER BY datestamp DESC LIMIT $offset,$limit",$con);
			//echo "SELECT DISTINCT(id) FROM conversations WHERE ((thread_id REGEXP '.*,{$this->user_id}$' AND user2_hide = 0 AND user1_hide = 0) OR (thread_id REGEXP '^{$this->user_id},.*' AND user2_hide = 0 AND user1_hide = 0)) ORDER BY datestamp DESC LIMIT $offset,$limit";
		}
		//echo mysql_num_rows($r);
		$convos = array();
		while($c = mysql_fetch_array($r)){
			//var_dump($c);
			$convo = new Convo($c['id'],$this->user_id);
			//var_dump($convo);
			if($archived || count($convo->getMessages())){
				$convos[]=$convo;
			}
		}
		return $convos;
	}
}

Class Convo{
	//public $user_id;
	public $id;
	public $subject;
	public $thread_id;
	public $datestamp;
	public $user1_hide;
	public $user2_hide;
	public $message_count;
	public $new_message_count;
	public $otherUser;
	public $firstMsg, $lastMsg;
	public $preview;
	function __construct($id,$user_id){
		$this->user_id = intval($user_id);
		return $this->init($id);
	}
	public function init($id){
		$con = conDB();
		global $viewer;
		$getConversation = mysql_query("SELECT * FROM `conversations` WHERE id = $id",$con) or die(mysql_error());
		$convo = mysql_fetch_array($getConversation);
		//var_dump($convo);
		$this->user_id = $viewer->id;
		$this->id = intval($convo['id']);
		$this->subject = $convo['subject'];
		$this->thread_id = $convo['thread_id'];
		$this->datestamp = intval($convo['datestamp']);
		$this->user1_hide = intval($convo['user1_hide']);
		$this->user2_hide = intval($convo['user2_hide']);
		//echo $this->id;
		if(!intval($this->id)){ return false; }
		$t = explode(",",$this->thread_id);
		//var_dump($t);
		if(count($t)!=2 || !intval($t[0]) || !intval($t[1])){return false;}
		//message count
		$r = mysql_query("SELECT COUNT(`id`) from `mail` where conversation_id ='$this->id'",$con);
		$message_count = mysql_fetch_array($r);
		$this->message_count = intval($message_count[0]);
		//new message count
		$r = mysql_query("SELECT COUNT(id) FROM `mail` WHERE `new`=1 AND conversation_id='{$this->id}' AND user_id != {$this->user_id} AND `new` = 1",$con);
		$this->new_message_count = mysql_fetch_array($r);
		$this->new_message_count = intval($this->new_message_count[0]);
		
		$r = mysql_query("SELECT MAX(datestamp),MIN(datestamp) from `mail` where conversation_id='{$this->id}'",$con);
		$dates = mysql_fetch_array($r);
		$this->firstMsg = intval($dates[1]);
		$this->lastMsg = intval($dates[0]);
		
		$getPreview = mysql_query("SELECT `message` FROM `mail` WHERE `conversation_id`='{$this->id}' ORDER BY `datestamp` DESC LIMIT 1",$con);
		
		$preview = mysql_fetch_array($getPreview);
		$this->preview = str_replace("\n\r"," ",strip_tags($preview[0]));
		
		$a = explode(",",$this->thread_id);
		if($a[0]==$this->user_id){$this->otherUser = new User($a[1]);}else{$this->otherUser = new User($a[0]);}
	}
	
	public function getMessages($offset=0,$limit=DEF_MAIL_LIMIT){
		global $table;
		$con = conDB();
		$r = mysql_query("SELECT * from mail where conversation_id = '$this->id' ORDER BY datestamp DESC",$con);
		//$r = mysql_query("SELECT * from {$table} where thread_id = '{$this->thread_id}' order by datestamp desc LIMIT $offset,$limit",$con);
		$messages = array();
		while($m = mysql_fetch_array($r)){
			$messages[] = new MailMessage($m);
		}
		if(!DEF_MAIL_NEW_ON_TOP){$messages=array_reverse($messages,true);}
		return $messages;
	}
	
	public function deleteThread(){
		global $table;
		$con = conDB();
		$thread = $this->thread_id;
		$users = explode(',',$thread);
		if($users[0] == $this->user_id){
			$field = 'user1_hide';
		}elseif($users[1] == $this->user_id){
			$field = 'user2_hide';
		}else{
			//return $field."/".var_dump($users);
			return false;
		}
		mysql_query("UPDATE conversations SET {$field} = 1 WHERE id = '$this->id'",$con);
		echo mysql_error();
		return true;		
	}
}

Class MailMessage{
	public $id=0;
	public $user_id=0;
	public $other_user=0;
	public $message="";
	public $datestamp=0;
	public $new = false;
	public $thread_id = "0,0";
	
	function __construct($row){
		if(!$row){return false;}
		$this->id = intval($row['id']);
		$this->user_id = intval($row['user_id']);
		$a = explode($this->thread_id);
		if(intval($a[0])==$user_id){$other_user=$a[1];}else{$other_user=$a[0];}
		$this->message = $row['message'];
		$this->datestamp = intval($row['datestamp']);
		$this->new = intval($row['new']);
	}
	
	function getHTML(){
		global $viewer;
		global $HOME_URL;
		if (strlen($viewer->data['timezone'])) { date_default_timezone_set($viewer->data['timezone']); }
		$html = "<div class='message";
		if($this->new && $this->user_id!=$viewer->id){ $html .= " newMail"; }
		$html .= "' id='mail{$this->id}' rel='conv".str_replace(",","_",$this->id)."'>\n";
		$html .= "<table width='100%' cellspacing='0' cellpadding='2'><tr valign='top'><td width='68' align='right' style='padding: 0 8px 0 0;'>";
		$user = new User($this->user_id); 
		$html .= $user->avatar(50,50,true);
		$html .= "</td><td align='left' valign='top'><div class='left'><strong>".$user->name()."</strong><br /><br />{$this->message}</div>";
		$html .= "<small class='right light' style='margin: 2px 0 4px 0;'>".date("M j Y g:ia",$this->datestamp)."</small><div class='clear'></div>\n";
		$html .= "</td></tr>\n";
		$html .= "</table>\n</div>\n";
		return $html;
	}
	
}
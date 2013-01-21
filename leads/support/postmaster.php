<?php include_once '../inc/trois.php';include 'main.php';
loginRequired();
$viewer = new User(verCookie());
$datestamp = time();
//Accept a message from ?to= or ?re= and save it to database
if(strlen($_POST['message'])){
	$toUser = new User($_POST['to']);
	if(!intval($toUser->id)){?>
		<script type="text/javascript">parent.$.fancybox("<h1>Send Message</h1><div id='interior'>Message not sent! No recipient specified</div>");setTimeout("parent.window.location.reload();",500);</script>
		<?php die();
	}
	$message = mysql_escape_string(nl2br(strip_tags($_POST['message'])));
	$con = conDB();
	if($viewer->id<$toUser->id){$thread_id=$viewer->id.",".$toUser->id;}else{$thread_id=$toUser->id.",".$viewer->id;}
	//this is where a new conversation is created
	if($toUser->id != 32){
		$accts = $toUser->accounts();
		$acct = $accts[0];
		$accnt = new Account($acct);
		$subdomain = $accnt->subdomain;
		$parts = explode('.',$HOME_URL);
		$HOME_URL = str_replace($parts[0],$subdomain,$HOME_URL);
	}
	$subject = mysql_escape_string(strip_tags($_POST['subject']));
	$datestamp = time();
	mysql_query("INSERT INTO `conversations`(`thread_id`,`subject`,`datestamp`) VALUES('$thread_id','$subject',$datestamp)",$con) or die(mysql_error());
	$convo_id = mysql_insert_id($con);
	mysql_query("INSERT INTO `mail`(user_id,datestamp,`new`,message,conversation_id) VALUES($viewer->id,$datestamp,1,'$message',$convo_id)",$con) or die(mysql_error());
	$id = mysql_insert_id($con);
	//saveNote($toUser->id,$viewer->id,'message',$id);
	if($toUser->data['message_email']!='0'){
		$body = nl2br(strip_tags("<strong>".$conv->subject."</strong>\n\r".$_POST['message'],"<strong><br />"));
		$mailVariables = array('%homeurl','%url','%sitename','%sender','%receiver','%post','%type');
		$mailValues = array($HOME_URL,$HOME_URL."support/index.php",$SITE_NAME,$viewer->name(),$toUser->name(),$body,'');
		$title = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_message'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_message')));
		htmlEmail($toUser->email,$title,$message);
	}?>
	<script type="text/javascript">
		//parent.$.<?php if (strpos($_SERVER['HTTP_REFERER'],"admin/")) { echo 'facebox'; } else { echo 'fancybox'; } ?>("<div style='width: 250px;'><h2><?=('Send Message');?></h2><hr /><?= ('Message sent successfully');?></div>");
    	parent.window.location.href=parent.window.location.href;
    </script>
	<?php die();
}
//Accept Ajax message post and respond with HTML of new message
if(strlen($_POST['ajax_message'])){
	$toUser = new User($_POST['to']);
	$convo_id = intval($_POST['conversation_id']);
	if($_POST['as32']=="true" && in_array($viewer->id,$ADMIN_IDS)){
		$viewer = new User(32);
	}
	$conv = new Convo($convo_id,$viewer->id);
	if(($conv->user1_hide == 1 || $conv->user2_hide == 1 )&& $viewer->id != 32){
		$update = mysql_query("UPDATE conversations SET user1_hide = 0, user2_hide = 0 WHERE id = {$conv->id} LIMIT 1",$con);
	}
	
	if(!intval($toUser->id)){die("Your message failed to be sent, no recipient ID found.");}
	$message = mysql_escape_string(nl2br(strip_tags($_POST['ajax_message'])));
	$con = conDB();
	if($viewer->id<$toUser->id){$thread_id=$viewer->id.",".$toUser->id;}else{$thread_id=$toUser->id.",".$viewer->id;}
	mysql_query("INSERT INTO mail(user_id,datestamp,`new`,message,conversation_id) VALUES({$viewer->id},$datestamp,1,'{$message}',$convo_id)",$con) or die(mysql_error());

	$newmsg = intval(mysql_insert_id($con));
	if($toUser->data['message_email']!='0'){
		if($toUser->id != 32){
			$accts = $toUser->accounts();
			$acct = $accts[0];
			$accnt = new Account($acct);
			$subdomain = $accnt->subdomain;
			$parts = explode('.',$HOME_URL);
			$HOME_URL = str_replace($parts[0],$subdomain,$HOME_URL);
		}
		$body = nl2br(strip_tags("<strong>".$conv->subject."</strong>\n\r".$_POST['ajax_message'],"<strong><br />"));
		$mailVariables = array('%homeurl','%url','%sitename','%sender','%receiver','%post','%type');
		$mailValues = array($HOME_URL,$HOME_URL."support/index.php",$SITE_NAME,$viewer->name(),$toUser->name(),$body,'');
		$title = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_message'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_message')));
		htmlEmail($toUser->email,$title,$message);
	}
	if(!$newmsg){die("Your message failed to save:".mysql_error());}
	$r = mysql_query("SELECT * from {$table} where id={$newmsg}",$con);
	$m = mysql_fetch_array($r);
	$msg = new MailMessage($m);
	echo $msg->getHTML();
	die();
} 

//Accept ajax call to mark message as read
if(intval($_POST['read'])){
	$con = conDB();
	if($_POST['as32']=="true" && in_array($viewer->id,$ADMIN_IDS)){
		$viewer = new User(32);
	}
	$thread = mysql_escape_string($_POST['read']);
	$r = mysql_query("UPDATE mail SET `new`=0 WHERE conversation_id='{$thread}' AND user_id != {$viewer->id}",$con);
	$getSender = mysql_query("SELECT user_id FROM {$table} WHERE thread_id='{$thread}' AND user_id != {$viewer->id} LIMIT 1",$con);
	$sender = mysql_fetch_array($getSender);
	$getCount = mysql_query("SELECT COUNT(*) FROM {$table} WHERE `new`=1 AND ((thread_id regexp '^{$viewer->id},.*$' and user1_hide=0) or ( thread_id regexp '^.*,{$viewer->id}$' and user2_hide=0)) and user_id = ".$sender[0],$con);
	$count = mysql_fetch_array($getCount);
	$getTotal = mysql_query("SELECT COUNT(id) FROM {$table} WHERE `new`=1 AND ((thread_id regexp '^{$viewer->id},.*$' and user1_hide=0) or ( thread_id regexp '^.*,{$viewer->id}$' and user2_hide=0)) AND `user_id`!={$viewer->id}",$con);
	$total = mysql_fetch_array($getTotal);
	die(strval($count[0])."_".strval($total[0]));
}
/*not used
//Accept ajax call to delete message
if(intval($_POST['kill'])){
	$con = conDB();
	mysql_query("DELETE from {$table} where id=".strval(intval($_POST['kill']))." and thread_id regexp '.*(^|,){$viewer->id}($|,).*' LIMIT 1",$con);
	die("1");
}
*/
//Load ?to= or ?re= in a facebox compose form
if(intval($_GET['to'])){
	$toUser = new User($_GET['to']);
}
/*
if(intval($_GET['re'])){
	$con = conDB();
	$r = mysql_query("SELECT user_id from {$table} where id=".strval(intval($_GET['re'])),$con);
	$m = mysql_fetch_array($r);
	$toUser = new User($m['user_id']);
}*/
?>
<h2>Start A New Ticket</h2>
<hr />
<div id="interior">
	<iframe id='hidn' name='hidn' width='0' height='0' frameborder='0'></iframe>
	<form method='post' action='<?=$HOME_URL?>support/postmaster.php' target='hidn'>
		<input type='hidden' name='to' id='to_user'<?php if(intval($toUser->id)){echo " value='{$toUser->id}'";}?> />
        <table cellpadding="0" cellspacing="2">
            <tr>
            	<td>
                	Subject:<br />
                	<input type="text" style="width:380px;" name="subject" />
                </td>
            </tr>
            <tr>
            	<td style="padding: 4px 0px;"><textarea name='message' style="min-width:380px; max-width: 380px; min-height: 74px; max-height: 400px;"></textarea></td>
            </tr><tr>
            	<td align='right'><input type='submit' value="Send" class="right button"/></td>
            </tr>
		</table>
	</form>
</div>

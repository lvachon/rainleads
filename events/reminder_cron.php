<?php include '../inc/trois.php';
$con = conDB();
$low = time()-60;
$high = time();
$getEvents = mysql_query("SELECT * FROM reminders WHERE datestamp >= $low AND datestamp <= $high",$con) or die(mysql_error());
while($r = mysql_fetch_array($getEvents)){
	$user = new User($r['user_id']);
	if(strlen($user->data['timezone'])){
		date_default_timezone_set($user->data['timezone']);
	}
	
	$event = new Event($r['event_id']);
	$acc = new Account($event->account_id);
	$HOST = str_replace("https://www.","https://".$acc->subdomain.".",$HOME_URL);
	
	
	$month = date('n',$event->start_time);
	$day = date('j',$event->start_time);
	$year = date('Y',$event->start_time);
	$url = $HOST."events/calendar.php?month={$month}&day={$day}&year={$year}#dayactivity";
	$post = "<table width='100%' cellpadding='3'>
        	<tr valign='top'>
            	<td colspan='2'><strong><a href='{$url}'>{$event->title}</a></strong></td>
        	</tr>";
	if(intval($event->lead_id)){
		$lead = $event->getLead();
		$post.="<tr>
				<td colspan='2'><strong>Lead:</strong> <a href='{$HOST}/leads/lead.php?id={$lead->id}'><strong>{$lead->name}</strong></a></td>
				</tr>";
	}
	$post .="<tr>
			<td colspan='2'>".$event->when()."<br />".nl2br($event->description)."</td>
			</tr>
			</table>";
	if(strlen($r['message'])){
		$post .= "<br /><br />You've included the following message:<br />".nl2br($r['message']);
	}
	
	$mailVariables = array('%homeurl','%sitename','%receiver','%sender','%post','%url','%title');
	$mailValues = array($HOME_URL,$SITE_NAME,$user->name(),$SITE_NAME,$post,$url,$event->title);
	$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_event_reminder'));
	$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_event_reminder')));
	htmlEmail($user->email,$subject,$message);
}
?>
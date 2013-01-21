<?php include '../inc/trois.php';
$con = conDB();
$request_id = mysql_escape_string($_GET['request']);
$getRequest = mysql_query("SELECT * FROM `requests` WHERE `request` = '$request_id' LIMIT 1",$con);
$request_info = mysql_fetch_array($getRequest);
$user = new User(intval($request_info['user_id']));
$account = new Account(intval($request_info['account_id']));
$ical = "BEGIN:VCALENDAR\r\n
VERSION:2.0\r\n
PRODID:-//{$SITE_NAME}/handcal//NONSGML {$SITE_NAME} EVENTS v1.0//EN\r\n
X-WR-CALNAME:{$SITE_NAME} Events\r\n
X-PUBLISHED-TTL:PT1H\r\n
X-ORIGINAL-URL:{$HOME_URL}/events/calendar.php\r\n
VERSION:2.0\r\n
CALSCALE:GREGORIAN\r\n
METHOD:PUBLISH \r\n";
//echo $account->id;
$getEvents = mysql_query("SELECT id FROM events WHERE account_id = {$account->id} ORDER BY start_time DESC",$con) or die(mysql_error());
//die(strval(mysql_num_rows($getEvents)));
//date_default_timezone_set($user->data['timezone']);

while($e = mysql_fetch_array($getEvents)){
	$event = new Event($e['id']);
	$start_ical = date('Ymd',$event->start_time).'T'.date('His',$event->start_time);
	$end_ical = date('Ymd',$event->end_time).'T'.date('His',$event->end_time);
	$ical .= "BEGIN:VEVENT\r\n
	UID:" . md5(uniqid(mt_rand(), true)) . "@".$DOMAIN."\r\n
	DTSTAMP:" . gmdate('Ymd',$event->start_time).'T'. gmdate('His',$event->start_time) . "Z\r\n
	DTSTART:{$start_ical}\r\n
	DTEND:{$end_ical}\r\n
	SUMMARY:{$event->title}\r\n";
	if(intval($event->lead_id)){
		$lead_id = intval($event->lead_id);
		$getLead = mysql_query("SELECT `name`,`email`,`data`,`id` FROM form_results WHERE id = $lead_id",$con);
		$lead = mysql_fetch_array($getLead);
		$HOME = str_replace('https://www.','https://'.$account->subdomain.".",$HOME_URL);
		$ical .="DESCRIPTION:Lead Name: <a href='{$HOME}leads/lead.php?id={$lead['id']}'>{$lead['name']}</a> | Email: {$lead['email']}";
		$f->elems = unserialize($lead['data']);
		foreach($f->elems as  $el){
			if($el->data['name'] == 'phone'){
				$ical .= " | Phone: {$el->value}";
			}
		}
	}
	$ical .= "\r\nEND:VEVENT\r\n";
} 
$ical .= "\n\rEND:VCALENDAR";
//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="calendar.ics"');
echo $ical;
exit;
?>
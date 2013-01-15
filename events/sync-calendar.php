<?php include '../inc/trois.php';
$con = conDB();
$request_id = mysql_escape_string($_GET['request']);
$getRequest = mysql_query("SELECT * FROM `requests` WHERE `request` = '$request_id' LIMIT 1",$con);
$request_info = mysql_fetch_array($getRequest);
$user = new User(intval($request_info['user_id']));
$account = new Account(intval($request_info['account_id']));
if($account->membership!="basic" && $account->membership!="pro"){die();}//Your account is unable to create or export events.  Please <a href='/account/upgrade.php'>upgrade</a> your account.");}
$ical = "BEGIN:VCALENDAR\n
VERSION:2.0\n
PRODID:-//{$SITE_NAME}/handcal//NONSGML {$SITE_NAME} EVENTS v1.0//EN\n\r
X-WR-CALNAME:{$SITE_NAME} EVENTS\n\r
X-PUBLISHED-TTL:PT12H\n\r
X-ORIGINAL-URL:{$HOME_URL}/events/calendar.php\n\r
VERSION:2.0\n\r
CALSCALE:GREGORIAN\n\r
METHOD:PUBLISH \n\r ";
//echo $account->id;
$getEvents = mysql_query("SELECT id FROM events WHERE account_id = {$account->id} ORDER BY start_time DESC",$con) or die(mysql_error());
//die(strval(mysql_num_rows($getEvents)));
//date_default_timezone_set($user->data['timezone']);

while($e = mysql_fetch_array($getEvents)){
	$event = new Event($e['id']);
	$start_ical = date('Ymd',$event->start_time).'T'.date('His',$event->start_time);
	$end_ical = date('Ymd',$event->end_time).'T'.date('His',$event->end_time);
	$ical .= "\n\rBEGIN:VEVENT\n\r
	UID:" . md5(uniqid(mt_rand(), true)) . "@".$DOMAIN."\n\r
	DTSTAMP:" . gmdate('Ymd',$event->start_time).'T'. gmdate('His',$event->start_time) . "Z\n\r
	DTSTART:{$start_ical}\n\r
	DTEND:{$end_ical}\n\r
	SUMMARY:{$event->title}\n\r
	END:VEVENT\n\r ";
}
$ical .= "\n\rEND:VCALENDAR";
//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="calendar.ics"');
echo $ical;
exit;
?>
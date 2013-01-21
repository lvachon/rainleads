<?php include '../inc/trois.php';
loginRequired();
session_start();
$timezone = $_SESSION['time'];
date_default_timezone_set($timezone);
$con = conDB();
$ical = "BEGIN:VCALENDAR\n
VERSION:2.0\n
PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";
$getEvents = mysql_query("SELECT id FROM events WHERE account_id = {$viewer->getAccount()->id} ORDER BY start_time DESC",$con);
while($e = mysql_fetch_array($getEvents)){
	$event = new Event($e['id']);
	$start_ical = date('Ymd',$event->start_time).'T'.date('His',$event->start_time);
	$end_ical = date('Ymd',$event->end_time).'T'.date('His',$event->end_time);
	$ical .= "BEGIN:VEVENT\n
	UID:" . md5(uniqid(mt_rand(), true)) . "@".$DOMAIN."\n
	DTSTAMP:" . gmdate('Ymd',$event->start_time).'T'. gmdate('His',$event->start_time) . "Z\n
	DTSTART:{$start_ical}\n
	DTEND:{$end_ical}\n
	SUMMARY:{$event->title}\n
	END:VEVENT\n";
}
$ical .= "END:VCALENDAR";
//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="calendar.ics"');
echo $ical;
exit;
?>
<?php include '../inc/trois.php';
loginRequired();
$con = conDB();
$event_id = intval($_POST['event_id']);
$qty = intval($_POST['qty']);
$unit = $_POST['unit'];
$message = mysql_escape_string($_POST['message']);
switch($unit){
	case 'minutes':
		$multiplier = 60;
	break;
	case 'hours':
		$multiplier = 3600;
	break;
	case 'days':
		$multiplier = 86400;
	break;
	case 'weeks':
		$multiplier = 604800;
	break;	
}
$event = new Event($event_id);
$remind_me = $event->start_time - ($qty*$multiplier);
mysql_query("INSERT INTO reminders(event_id,user_id,datestamp,message) VALUES({$event->id},{$viewer->id},$remind_me,'$message')",$con);
session_start();
$timezone = $_SESSION['time'];
date_default_timezone_set($timezone);?>
<script>parent.$.fancybox("Your reminder has been saved. You will receive this reminder:<br /><?=date('n/j/Y \a\t g:i A',$remind_me)?>");</script>
<?php die(); ?>


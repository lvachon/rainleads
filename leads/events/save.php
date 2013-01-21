<?php
	include '../inc/trois.php';
	loginRequired();
	session_start();
	$offset = $_SESSION['offset'];
	//die(strval(intval($offset)));
	$user = new User(verCookie());
	$con = conDB();
	$title = mysql_escape_string($_POST['title']);
	$desc = mysql_escape_string($_POST['description']);
	
	$start_month = intval($_POST['start_month']);
	$start_day = intval($_POST['start_day']) ;
	$start_year = intval($_POST['start_year']);
	$start_hour = intval($_POST['start_hour']) ;
	$start_minute = intval($_POST['start_minute']) ;
	if($_POST['start_ampm']=="PM" ){
		if($start_hour < 12){$start_hour+=12;}//12PM is 12Z not 24Z
	}else{
		if($start_hour==12){$start_hour=0;}//12AM is midnight
	}
	
	$start_time = mktime($start_hour,$start_minute,0,$start_month,$start_day,$start_year);
	$start_time = $start_time - ($offset*3600);
	//die(strval($start_time)." ". date_default_timezone_get());	
	$end_time = $start_time;
	$account_id = intval($viewer->getAccount()->id);
	$lead_id = intval($_POST['lead_id']);
	if(intval($_POST['id'])){
		$id = intval($_POST['id']);
		mysql_query("UPDATE events SET title = '$title', description = '$desc', start_time = $start_time, end_time = $end_time, lead_id = $lead_id WHERE id = $id LIMIT 1",$con) or die(mysql_error());
	}else{
		mysql_query("INSERT INTO events(title,description,user_id,datestamp,start_time,end_time,account_id,lead_id) VALUES('$title','$desc',{$user->id},".time().",$start_time,$end_time,$account_id,$lead_id)",$con);
		$id = mysql_insert_id($con);
		if(!$id){echo mysql_error();}
		saveAction('event',$viewer->id,$id,'',$account_id,$lead_id);
	}
	$timezone = $_SESSION['time'];
	date_default_timezone_set($timezone);
	$month = date('n',$start_time);
	$day = date('j',$start_time);
	$year = date('Y',$start_time);
	$url = $HOME_URL."events/calendar.php?month={$month}&day={$day}&year={$year}#dayactivity";
	
	header("Location: {$url}");
	
?>
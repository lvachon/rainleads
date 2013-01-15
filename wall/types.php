<?php
switch($act['type']){
	case 'milestone_removed':
		$action = "removed a milestone.";
		$detail = "&quot;".$act['aux_data']."&quot;";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['content_id']}",$con) or die(mysql_error());
		$lead = mysql_fetch_object($getLead);
		$link = "- Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
	break;
	case 'milestone_saved':
		$action = "completed a milestone.";
		$detail = "&quot;".$act['aux_data']."&quot;";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['content_id']}",$con);
		$lead = mysql_fetch_object($getLead);
		$link = "- Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
	break;
	case 'note':
		$action = "added a note.";
		$detail = "&quot;".$act['aux_data']."&quot;";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['lead_id']}",$con);
		$lead = mysql_fetch_object($getLead);
		$link = "- Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
	break;
	case 'assignment':
		$action = "has been assigned a lead.";
		$detail = "";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['lead_id']}",$con);
		$lead = mysql_fetch_object($getLead);
		$link = "Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
	break;
	case 'lead':
		$action = " A new lead has been received.";
		$detail = "";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['lead_id']}",$con);
		$lead = mysql_fetch_object($getLead);
		$link = "Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
	break;
	case 'pipeline':
		$action = " A new lead has been added to the pipeline.";
		$detail = "";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['lead_id']}",$con);
		$lead = mysql_fetch_object($getLead);
		$link = "Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
	break;
	case 'file':
		$action = " added a file.";
		$getFile = mysql_query("SELECT `title` FROM proposals WHERE id = {$act['content_id']} LIMIT 1",$con);
		$file = mysql_fetch_array($getFile);
		$detail = "<a href='{$HOME_URL}leads/getfile.php?id=".$act['content_id']."'>{$file['title']}</a>";
		$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$act['lead_id']}",$con);
		$lead = mysql_fetch_object($getLead);
		$link = "Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}&show=proposals'>{$lead->name}</a>";
	break;
	case 'event':
		$action = " has added a new event to the calendar.";
		$event = new Event($act['content_id']);
		$evUrl = "{$HOME_URL}events/calendar.php?month=".date('n',$event->start_time)."&day=".date('j',$event->start_time)."year=".date('Y',$event->start_time)."#dayActivity";
		$detail = "&quot;<a href='{$evUrl}'>".$event->title."</a>&quot;";
		if(intval($event->lead_id)){
			$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$event->lead_id}",$con);
			$lead = mysql_fetch_object($getLead);
			$link = "Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";
		}
	break;
	default:
		$action = $act['type'].".";
		$detail = $act['aux_data'];
		$link = "";
	break;	
}?>
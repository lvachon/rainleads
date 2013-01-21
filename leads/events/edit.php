<?php
include '../includes/trois.php';
loginRequired();
$user = new User(verCookie());
$con = conDB();
if(intval($_POST['kill'])){
	$event = new Event($_POST['kill']);
	mysql_query("DELETE FROM events where id={$event->id} and user_id={$user->id} LIMIT 1",$con);
	if(intval(mysql_affected_rows($con))){
		mysql_query("DELETE FROM RSVP where event_id={$event->id}",$con);
		mysql_query("DELETE FROM news_events where content_id={$event->id} AND type='event'",$con);
		unlink(getcwd()."/files/{$event->id}.jpg");
		die(strval($event->id));
	}else{
		die("0");
	}
}
if(intval($_POST['id'])){
	$event = new Event(intval($_POST['id']));
	if(!intval($event->id)){errorMsg("This event was not found");die();}
	if(verCookie()!=$event->user_id){errorMsg("This is not your event to edit");die();}
	$event->title = $_POST['title'];
	$event->description = $_POST['description'];
	
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
	
	
	$end_month = intval($_POST['end_month']);
	$end_day = intval($_POST['end_day']);
	$end_year = intval($_POST['end_year']);
	$end_hour = intval($_POST['end_hour']);
	$end_minute = intval($_POST['end_minute']);
	if($_POST['end_ampm']=="PM" ){
		if($end_hour < 12){$end_hour+=12;}//12PM is noon not midnight
	}else{
		if($end_hour==12){$end_hour=0;}//12AM is midnight
	}
	$end_time = mktime($end_hour,$end_minute,0,$end_month,$end_day,$end_year);
	
	$event->start_time=$start_time;
	$event->end_time = $end_time;
	
	$event->save();
	if($_FILES['file']['tmp_name']){
		exec("convert {$_FILES['file']['tmp_name']} -thumbnail '250x250>' ".getcwd()."/files/{$event->id}.jpg",$ou);
	}
	header("Location: view.php?id={$event->id}");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<?php include '../includes/meta.php'; ?>
    <?php include '../includes/links.php'; ?>
	<script type="text/javascript">
        function kill(id){
            if(confirm("Do you really want to delete this event?  This cannot be undone.")){
                $("#kill"+id).val("Deleting...");
                $.post("edit.php",{
                    'kill':id
                    },
                    function(data){
                        if(Number(data)>0){
                            $("#kill"+data).val("Deleted");
                            $("#multiForm"+data).fadeOut("slow");
                        }
                    });
            }
        }
    </script>
</head>
<body>
    <div id="wrap">
        <?php include '../includes/header.php'; ?>
        <?php include '../includes/nav.php'; ?>
        <div id="content">
        	<div id="main" class="left">
            	<h2>Edit Event</h2>
                <hr />
                <?php if(intval($_GET['id'])){
					$event=new Event($_GET['id']); ?>
                	<form method='post' action='edit.php' enctype='multipart/form-data'>
                		<input type='hidden' name='id' value='<?=$event->id;?>' />
	                	<table cellpadding="5">
	                    	<tr valign="top">
                                <td rowspan='5' width="140">
                                    <br /><?=$event->thumbHTML(128,128,true)?>
                                </td>
	                        	<td><strong><?=tl('Change_Picture')?>:</strong><br /><input type='file' name='file' /></td>
	                        </tr>
	                        <tr>
	                        	<td><strong><?=tl('Title')?>:</strong><br /><input type="text" value="<?=htmlentities($event->title);?>" style="width:500px;" name="title" /></td>
	                        </tr>
	                        <tr>
	                        	<td>
                                	<strong><?=tl('Description')?>:</strong><br />
                                	<textarea rows="5" style="min-width:500px; max-width: 500px; min-height: 74px; max-height: 400px;" name="description"><?=htmlentities($event->description);?></textarea>
                                </td>
	                        </tr>
                        	<tr>
	                        	<td>
                                	<strong><?=tl('Starts')?>:</strong><br />
	                        		<select name='start_month' style="width:120px">
	                        			<?php $selected=date("n",$event->start_time);include '../includes/month_select.php'; ?>
	                        		</select>
	                        		<select name='start_day' style="width: 54px;">
	                        			<?php $selected=date("j",$event->start_time);include '../includes/day_select.php'; ?>
	                        		</select>
	                        		<select name='start_year' style="width: 64px;">
			                            <?php $selected=date("Y",$event->start_time); echo yearSelect('future',10,$selected); ?>
	                        		</select>
                                    &nbsp;@&nbsp;
                                    <select name='start_hour' style="width: 54px;">
                                        <?php $selected=date("g",$event->start_time);include '../includes/hour_select.php'; ?>
                                    </select>
                                    <select name='start_minute' style="width: 54px;">
                                        <?php $selected=date("i",$event->start_time);include '../includes/minute_select.php'; ?>
                                    </select>
                                    <select name='start_ampm'>
                                        <option value='AM'><?=tl('AM')?></option>
                                        <option value='PM' <?php if(date("G",$event->start_time)>=12){echo "selected='selected'";}?>><?=tl('PM')?></option>
                                    </select>
	                        	</td>
	                        </tr>
	                        <tr>
	                        	<td>
                                	<strong><?=tl('Ends')?>:</strong><br />
	                        		<select name='end_month' style="width:120px">
	                        			<?php $selected=date("n",$event->end_time);include '../includes/month_select.php'; ?>
	                        		</select>
	                        		<select name='end_day' style="width: 54px;">
	                        			<?php $selected=date("j",$event->end_time);include '../includes/day_select.php'; ?>
	                        		</select>
	                        		<select name='end_year' style="width: 64px;">
			                            <?php $selected=date("Y",$event->end_time); echo yearSelect('future',10,$selected); ?>
	                        		</select>
                                    &nbsp;@&nbsp;
                                    <select name='end_hour' style="width: 54px;">
                                        <?php $selected=date("g",$event->end_time);include '../includes/hour_select.php'; ?>
                                    </select>
                                    <select name='end_minute' style="width: 54px;">
                                        <?php $selected=date("i",$event->end_time);include '../includes/minute_select.php'; ?>
                                    </select>
                                    <select name='end_ampm'>
                                        <option value='AM'><?=tl('AM')?></option>
                                        <option value='PM' <?php if(date("G",$event->end_time)>=12){echo "selected='selected'";}?>><?=tl('PM')?></option>
                                    </select>
	                        	</td>
	                        </tr>
	                        <tr>
                                <td align='center'><a href="delete.php?id=<?=$event->id?>" rel="fancybox" class="button"><?= tl('Delete') ?></a></td>
                                <td align='right'><input type='submit' value='<?= tl('Save') ?> <?=tl('Event')?>' id='button<?=$event->id;?>' /></td>
	                        </tr>
	                    </table>
	                </form>
				<?php } ?>
                <div class="clear"></div>
            </div>
            <div id="side" class="right">
            	<?php include '../includes/side.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
		<?php include('../includes/footer.php') ?>
    </div>    
</body>
</html>

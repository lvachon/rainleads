<?php include_once '../inc/trois.php';
loginRequired();
session_start();
$timezone = $_SESSION['time'];
date_default_timezone_set($timezone);
if($viewer->id == $account->user_id){
	$link_msg = "<a href='../account/upgrade.php'>upgrade</a>";
}else{
	$link_msg = "upgrade";
}
if(intval($_GET['id'])){
	$event = new Event(intval($_GET['id'])); 
}?>
<h3><?php if(intval($_GET['id'])){?>Edit<?php }else{?>Create<?php } ?> An Event</h3>
<hr />
<form method="post" action="<?=$HOME_URL?>events/save.php" enctype="multipart/form-data">
	<?php if(intval($event->id)){?>
		<input type="hidden" name="id" value="<?=$event->id?>" />
	<?php } ?>
    <div id='interior' >
        <div id='multiForm'>
            <table cellpadding="5" align="center" class='multiTable' id="settings_table">
                
                <tr>
                    <td>
                        <strong>Title:</strong><br />
                        <input type="text" name="title" style="width:570px;" value="<?=$event->title?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Description:</strong><br />
                        <textarea name="description" rows="5" style="min-width:570px; max-width: 570px; min-height: 74px; max-height: 400px;"><?=$event->description?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Date &amp; Time:</strong><br />
                        <select name='start_month' style="width:120px">
                            <?php if(intval($_GET['id'])){ $selected = date('n',$event->start_time); }elseif(strlen($_GET['month'])){ $selected = intval($_GET['month']);   }else{$selected=date("n");} include '../inc/month_select.php'; ?>
                        </select>
                        <select name='start_day' style="width: 54px;">
                            <?php  if(intval($_GET['id'])){ $selected = date('j',$event->start_time); }elseif(strlen($_GET['day'])){ $selected = intval($_GET['day']);   }else{$selected=date("j");} include '../inc/day_select.php'; ?>
                        </select>
                        <select name='start_year' style="width: 64px;">
                            <?php  if(intval($_GET['id'])){ $selected = date('Y',$event->start_time); }elseif(strlen($_GET['year'])){ $selected = intval($_GET['year']);   }else{$selected=date("Y");} echo yearSelect('future',10,$selected); ?>
                        </select>
                        &nbsp;@&nbsp;
                        <select name='start_hour' style="width: 54px;">
                            <?php if(intval($_GET['id'])){ $selected=date("g",$event->start_time); }else{$selected=date("g");} include '../inc/hour_select.php'; ?>
                        </select>
                        <select name='start_minute' style="width: 54px;">
                            <?php if(intval($_GET['id'])){ $selected=date("i",$event->start_time); }else{ $selected=date("i"); } include '../inc/minute_select.php'; ?>
                        </select>
                        <select name='start_ampm' style="width: 58px;">
                            <option value='AM'>AM</option>
                            <option value='PM' 
							<?php if(intval($_GET['id'])){ 
								if(date("G",$event->start_time) >= 12){ echo "selected = 'selected'"; } 
							}else{ 
								if(date("G")>=12){echo "selected='selected'";}
							}?>>PM</option>
                        </select>
                    </td>
                </tr>
               
                <tr>
                    <td>
                        <strong>Lead:</strong><br />
                        <select name="lead_id" style="width:441px;">
                        	<option value="0">No Lead</option>
                            <?php $acc = $viewer->getAccount();
                             $leads = $acc->leads();
                            foreach($leads as $l){
                                if(intval($_GET['id'])){
									$selected = intval($event->lead_id);
								}
								if(strlen($_GET['lead_id'])){
                                    $selected = intval($_GET['lead_id']);
                                }?>
                                <option value="<?=$l->id?>" <?php if($selected == $l->id){?> selected="selected"<?php } ?>><?=$l->name?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><input type='submit' class="button" value='Save <?php if(intval($_GET['id'])){?>Changes<?php }else{?>Event<?php } ?>' /></td>
                </tr>
            </table>
        </div>
    </div>
 </form>

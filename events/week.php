<?php include_once '../includes/trois.php';
loginRequired();
$con = conDB();
if (intval($_GET['id'])) { $user = new User(intval($_GET['id'])); } else { $user = new User($viewer->id); }
$date = mktime(0,0,0,$month,$day,$year);
if (date("l",$date) == 'Sunday') {
	$start = $date;
} else {
	$start = strtotime('last sunday, 12am', $date);
}
$end = $start+86400*7;
$lastHour=array();
// change day links
$lessWeek = $date-(86400*7);
$less = 'calendar.php?view=week&year='.date("Y",$lessWeek).'&month='.date("n",$lessWeek).'&day='.date("j",$lessWeek);
$moreWeek = $date+(86400*7);
$more = 'calendar.php?view=week&year='.date("Y",$moreWeek).'&month='.date("n",$moreWeek).'&day='.date("j",$moreWeek); ?>
<table width="100%" cellpadding="4" cellspacing="0" class="upper">
    <tr style='font-size:18px; text-transform: uppercase; height:32px; text-align: center;'>
        <td width='65' class="adjuster" onClick="document.location = '<?=$less?>#caltop';">&lt;</td>
        <td colspan='5' align='center' class="no-adjust">
        Week of <?=date("F j, Y",$start);?>
        </td><td width='65' class="adjuster" onClick="document.location = '<?=$more?>#caltop';">&gt;
        </td>
    </tr>
</table>
<?php $r = mysql_query("SELECT * from events where user_id={$user->id} and ((start_time < $end and start_time>$start) or  (end_time<$end and end_time>$start))",$con);
//UI OPTIONS
$width=686;
$height=647;
$cnt=0; ?>
<table width="100%" id="week" cellpadding="0" cellspacing="0" style="border: 1px solid #d7d7d7; border-top: none;">
	<tr class="labeled">
		<td></td>
		<?php for($i=$start;$i<$end;$i+=86400){?>
			<td height='32' width="<?=round($width/7)+1?>" class="dayofweek<?php if (date("D",$date)==date("D",$i)) { echo ' blueit'; } ?>" id="<?=date("D",$i)?>"><?=date("D",$i)?> <?=date("n/j",$i);?></td>
		<?php } ?>
	</tr>
	<tr valign='top'>
    	<td>
			<?php foreach(range(7,30) as $h){
                if ($h>=24) { $h = $h-24; } ?>
                <div class="hour-label"><div class="hour"><?php if ($h==7) { echo '&nbsp;'; } else if ($h==0) { echo 'midnight'; } else if ($h>0 && $h<12) { echo $h.' am'; } else if ($h==12) { echo 'noon'; } else if ($h>12) { echo ($h-12).' pm'; } ?></div></div>
            <?php } ?>
        </td>
		<td colspan='7'>
			<div style='padding:0px;width:<?=$width;?>px;height:<?=$height;?>px;position:relative;background:url(../img/weekgrid.png) no-repeat;'>
				<?php $cnt=0;
                while($e = mysql_fetch_array($r)){
                    $someevent = new Event($e['id']);
                    if(intval($e['start_time'])>intval($e['end_time'])){continue;}//wtf!
                    $day = intval(date("w",$e['start_time']));
                    $left = floor($day * $width/7);
                    $hour = intval(date("G",$e['start_time']));
                    $top = $hour * $height/24 - 7*($height/24);
					if ($top<0) { $top += $height; }
					//if ($top<0) { echo "<b>".$top; }
                    $eday = intval(date("w",$e['end_time']));
                    if($eday!=$day){//If this event overlaps the end of the day
                            $evs[$cnt]['end_time']=$start+($day+1)*86400; //Trim it to the end of the day(write the actual array, not the copy)
                            $e['end_time']=$evs[$cnt]['end_time'];//Set the copy too so the rest of the loop works as expected
                            $ee = $e;//make a copy of this event
                            $ee['start_time']=$e['end_time'];//Set the start to the end of the one we trimmed
                            $evs[]=$ee;//add it to the event stack. if this event also overlaps this code should keep splitting until it doesn't.
                    }
                    $ehour = intval(date("G",$e['end_time']));
                    $eheight = ($height)/24.0*($ehour-$hour);
                    if($eheight<($height)/24.0){$eheight=($height)/24.0;}
                    $cnt++;
					if(intval($e['id'])){ ?>
                    	<div class="event" style='overflow:hidden; position:absolute; width:<?=floor($width/7)-13;?>px; height:<?=floor($eheight);?>px; left:<?=floor($left)+1;?>px; top:<?=floor($top);?>px;'
                        	 onclick="$('.dayofweek').removeClass('blueit'); $('#<?=date("D",$e['start_time']);?>').addClass('blueit'); $('#daychosen').val('<?=date("j",$e['start_time']);?>'); $.get('showDays.php?month=<?=date("n",$e['start_time']);?>&day=<?=date("j",$e['start_time']);?>&year=<?=date("Y",$e['start_time']);?><?=$target?>', function(data) { $('#dayActivity').html(data); window.location = '#<?=$someevent->id?>'; });">
							<?php if (intval($someevent->open)) { ?>
                                <em>Available</em>
                            <?php } else {
                                if ($someevent->d_email==$viewer->email) { ?>
                                    <span class="regular"><?php if (strlen($someevent->data['p_lname'])) { ?><?=trunc($someevent->data['p_lname'].", ".substr($someevent->data['p_fname'],0,1),20);?><?php } ?></span>
                                <?php } else {
                                    echo 'Booked';
                                }
                            } ?>
							<?php if ($eheight>27) { ?>
                            	<br />
                                <div style="font-size: 11px;" class="light left"><?=date("g:ia",$someevent->start_time);?></div>
                            <?php } ?>
                            <?php if ($eheight>54) { ?>
                                <div style="font-size: 11px;" class="light right"> &ndash; <?=date("g:ia",$someevent->end_time);?></div>
                            <?php } ?>
                            <div class="clear"></div>
						<?php } ?>
                    </div>
				<?php } ?>
			</div>
		</td>
	</tr>
</table>

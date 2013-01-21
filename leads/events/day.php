<?php $viewer = new User(verCookie());
if (intval($_GET['id'])) { $user = new User(intval($_GET['id'])); } else { $user = new User($viewer->id); }
$date = mktime(0,0,0,$month,$day,$year);
// change day links
$less = 'calendar.php?view=day'; $more = 'calendar.php?view=day';
if ($day>1) { $less .= '&year='.$year.'&month='.$month.'&day='.strval($day-1); } else {
	if ($month>1) { $less .= '&year='.$year.'&month='.strval($month-1).'&day='.$molength[$month-1]; } else { $less .= '&year='.strval($year-1).'&month=12&day='.$molength[12]; }
}
if ($day<$molength[$month]) { $more .= '&year='.$year.'&month='.$month.'&day='.strval($day+1); } else {
	if ($month<12) { $more .= '&year='.$year.'&month='.strval($month+1).'&day=1'; } else { $more .= '&year='.strval($year+1).'&month=1&day=1'; }
}?>
<table width="100%" cellpadding="4" cellspacing="0" class="upper">
    <tr style='font-size:18px; text-transform: uppercase; height:32px; text-align: center;'>
        <td width='65' class="adjuster" onClick="document.location = '<?=$less?>#caltop';">&lt;</td>
        <td colspan='5' align='center' class="no-adjust">
        <?=date("l, F j, Y",$date);?>
        </td><td width='65' class="adjuster" onClick="document.location = '<?=$more?>#caltop';">&gt;
        </td>
    </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" id="day" class="hours" style="border-left: 1px solid #d7d7d7; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;">
	<?php foreach(range(7,30) as $h){
		if ($h>=24) { $h = $h-24; } ?>
    	<tr><td class="hour-label"><div style="position: relative; top: -16px;"><?php if ($h==7) { echo '&nbsp;'; } else if ($h==0) { echo 'midnight'; } else if ($h>0 && $h<12) { echo $h.' am'; } else if ($h==12) { echo 'noon'; } else if ($h>12) { echo ($h-12).' pm'; } ?></div></td>
        <?php $start = mktime($h,0,0,$month,$day,$year);
		$half = $start+1800;
		$end = $start+3600;
		$t = mysql_query("SELECT id FROM `events` WHERE `user_id`={$user->id} AND (`start_time` < {$half} AND `end_time` >= {$half}) LIMIT 1", $con);
		$cnt = intval(mysql_num_rows($t));
		if ($cnt>0) {
			while ($e = mysql_fetch_array($t)) {
				$someevent = new Event($e['id']);
				if ($start <= $someevent->start_time && $someevent->start_time < $half) {
					$rows = ceil(($someevent->end_time - $someevent->start_time)/1800);
					include 'day-format.php';
					$cnt--;
				} else if ($someevent->start_time < $date && $h==0) {
					$rows = ceil(($someevent->end_time - $start)/1800);
					include 'day-format.php';
					$cnt--;
				}
			}
		} else { echo '<td class="empty"></td>'; } ?>
        </tr><tr><td class="hour-label">&nbsp;</td>
		<?php $t = mysql_query("SELECT id FROM `events` WHERE `user_id`={$user->id} AND (`start_time` < {$end} AND `end_time` >= {$end}) LIMIT 1", $con);
		$cnt = intval(mysql_num_rows($t));
		if ($cnt>0) {
			while ($e = mysql_fetch_array($t)) {
				$event = new Event($e['id']);
				if ($half <= $event->start_time && $event->start_time < $end) {
					$rows = ceil(($event->end_time - $event->start_time)/1800);
					include 'day-format.php';
					$cnt--;
				}
			} 
		} else { echo '<td class="empty"></td>'; } ?>
        </tr>
    <?php } ?>
    </tr>
</table>
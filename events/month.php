<?php include_once '../includes/trois.php';
loginRequired();
$con = conDB();
if (intval($_GET['id'])) { $user = new User(intval($_GET['id'])); } else { $user = new User($viewer->id); }
$start = mktime(0,0,0,$month,1,$year); 
$end = mktime(23,59,59,$month,$molength[$month],$year);
$date = mktime(0,0,0,$month,$day,$year); ?>
<table width="100%" cellpadding="4" cellspacing="0" class="upper">
    <tr style='font-size:18px; text-transform: uppercase; height:32px; text-align: center;'>
        <td width='65' class="adjuster" 
        <?php if($month>1) {
        echo "onClick='parent.location=\"calendar.php?month=".strval($month-1)."&year=$year{$target}#caltop\"'";
        } else {
        echo "onClick='parent.location=\"calendar.php?month=12&year=".strval($year-1)."{$target}#caltop\"'";
        }?>>&lt;
        </td><td colspan='5' class="no-adjust">
        <?=date("F Y",$start);?>
        </td><td width='65' class="adjuster" 
        <?php if($month<12) {
        echo "onClick='parent.location=\"calendar.php?month=".strval($month+1)."&year=$year{$target}#caltop\"'";
        } else {
        echo "onClick='parent.location=\"calendar.php?month=1&year=".strval($year+1)."{$target}#caltop\"'";
        }?>>&gt;
        </td>
    </tr>
</table>
<table width="100%" cellpadding="4" cellspacing="0" style="border-left: 1px solid #d7d7d7; border-right:1px solid #d7d7d7; border-top:1px solid #d7d7d7;">
    <tr class='labeled'>
        <td width='65' height='32'>Sunday</td><td width='65'>Monday</td><td width='65'>Tuesday</td><td width='65'>Wednesday</td><td width='65'>Thursday</td><td width='65'>Friday</td><td width='65'>Saturday</td>
    </tr>
</table>
<table class='calendar' width="100%" cellpadding="4" style="background: #e9e9e9; border-left: 1px solid #d7d7d7; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;">
    <tr valign='top' class='days'>
    <?php $dow=0;
    if(date("w",$start)>0){
        foreach(range(0,date("w",$start)-1) as $x){?>
            <td>&nbsp;</td>
        <?php $dow++;}
    }
    foreach(range(1,$molength[$month]) as $x){
        $daystart = mktime(0,0,0,$month,$x,$year);
		if(intval($_GET['user'])){
			$user_id = intval($_GET['user']);
			$useradd = "AND user_id = $user_id";
		}else{
			$useradd = "";
		}
        $t = mysql_query("SELECT id from events WHERE account_id = {$viewer->getAccount()->id} AND end_time>{$daystart} AND start_time<={$daystart}+86400 $useradd ", $con);
        $c = intval(mysql_num_rows($t));
        if($date>=$daystart and $date<$daystart+86400){$today=" today";}else{$today="";}
        $numEvents++; ?>
        <td width='65' height='100' class='<?php if ($c==0) { echo 'no-'; } ?>events<?=$today?>' onclick='<?php if($c > 0){?>$("#daychosen").val("<?=$x?>"); $(".days td").removeClass("today"); $(this).addClass("today"); $.get("showDays.php?month=<?=$month?>&day=<?=$x?>&start=<?=$daystart?>&year=<?=$year?><?=$target?>", function(data) { $("#dayActivity").html(data); window.location = "#dayactivity"; });<?php }elseif($viewer->id == $user->id){ ?>$.post("<?=$HOME_URL?>events/upload-pop.php?month=<?=$month?>&day=<?=$x?>&year=<?=$year?>",function(data){$.fancybox(data);});<?php } ?>'>
        	<div class='daylabel'><?=$x?></div>
            <?php if ($c>0) { ?>
            	<div style="text-align: center; margin-top: -4px;">
					<?=$c?><br />event<?php if ($c!=1) { echo 's'; } ?>
            	</div>
			<?php } ?>
        </td>
        <?php 
		$dow++;
		if($dow>=7 && $x<$molength[$month]){$dow=0;echo "</tr>\n<tr valign='top' class='days'>\n";}
    }
    ?>
    </tr>
</table>
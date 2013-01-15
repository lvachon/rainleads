<?php ob_start(); include_once '../inc/trois.php';
loginRequired();
session_start();
$timezone = $_SESSION['time'];
date_default_timezone_set($timezone);
if (intval($_GET['id'])) { $user = new User(intval($_GET['id'])); } else { $user = new User($viewer->id); }
$con = conDB();
$x = intval($_GET['day']);
if(!$x){$x=date("j",time());}
$month = intval($_GET['month']);
if(!$month){$month=date("n",time());}
$year = intval($_GET['year']);
if(!$year){$year=date("Y",time());}
$start = mktime(0,0,0,$month,$x,$year);
$end = mktime(23,59,59,$month,$x,$year);

$qry = "events where account_id = {$viewer->getAccount()->id} AND end_time>={$start} AND start_time<{$start}+86400";	
$cnt=0;
$holdover = array(); ?>
<a name="dayactivity"></a>
<div class="list" style="padding: 2px 10px; text-transform: uppercase;">
    <h2 class="left"><?=date("l F j, Y",mktime(0,0,0,$month,$x,$year))?></h2>
    <div class="right">
        <?php $getTaken = mysql_query("SELECT COUNT(id) FROM `events` WHERE account_id = {$viewer->getAccount()->id} AND `start_time`>={$start} AND `start_time`<{$end} ",$con);
        $taken = mysql_fetch_array($getTaken); $taken = number_format(intval($taken[0]));
        echo $taken; ?> Event<?php if ($taken!=1) { echo 's'; } ?>
 
    </div>
    <div class="clear"></div>
</div>
<?php $r = mysql_query("SELECT id from $qry ORDER BY `start_time` ASC", $con);
while($e = mysql_fetch_array($r)) {
	$event = new Event($e['id']);
	if (date("G",$event->start_time)<7) {
		$holdover[]=$event;
	} else { ?>
       <?php include 'include.php';
        $cnt++;
	}
}
foreach ($holdover as $event) { ?>
    <a name="<?=$event->id?>"></a>
    <?php  include 'include.php';
    $cnt++;
}
if ($cnt==0) { echo '<div class="light family-georgia font-14" style="padding: 2px 15px;">no events scheduled</div>'; } ?>

<br /><a href="javascript:void(0);" onclick="$.post('<?=$HOME_URL?>events/upload-pop.php?month=<?=$month?>&day=<?=$x?>&year=<?=$year?>',function(data){$.fancybox(data);});" class="button right">Add Event</a><div class="clear"></div>

<?php include '../inc/trois.php';
loginRequired();
$con = conDB();
$datestamp = time();
$makeRequest = mysql_query("INSERT INTO requests(user_id,account_id,datestamp) VALUES({$viewer->id},{$viewer->getAccount()->id},$datestamp)",$con) or die(mysql_error());
$id = intval(mysql_insert_id($con));
$code = md5($id.$viewer->id.$viewer->getAccount()->id);
$request_code = mysql_escape_string($code);
mysql_query("UPDATE requests SET request = '$request_code' WHERE id = $id LIMIT 1",$con) or die(mysql_error());?>
<h3><?=cmsTitle('RTFcalendar_export_blurb')?></h3>
<div id="interior" style="width:410px;word-wrap:break-word; padding:5px;">
    <p><?=str_replace(array('%url','%post'),array($HOME_URL."events/sync-calendar.php?request=".$code,"<img src='{$HOME_URL}img/addcal.png' style='width:380px;' />"),nl2br(cmsRead('RTFcalendar_export_blurb')))?>:</p><br />
</div>

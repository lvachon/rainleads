<?php include_once '../inc/trois.php';
loginRequired();
$con = conDB();
$account = $viewer->getAccount();
$result_id = $_POST['id'];
$comment = $_POST['post'];	
$id = saveAction('note',$viewer->id,$result_id,$comment,$account->id,$result_id);
$getLead = mysql_query("SELECT * FROM form_results WHERE id = {$result_id}",$con) or die(mysql_error());
$lead = mysql_fetch_object($getLead);
$link = "- Lead: <a class='lead-link' href='{$HOME_URL}leads/lead.php?id={$lead->id}'>{$lead->name}</a>";?>
<div id="activity<?=$id?>" class="activity new">
    <table cellpadding="2" width="100%">
        <tr>
            <td rowspan="3" width="30"><?=$viewer->avatar(24,24)?></td>
            <td valign="top"><span class="name"><?=$viewer->name()?></span> has posted a note.</td>
        </tr>
        <tr>
            <td class="small">"<?=$comment?>" <?=$link?></td>
        </tr>
        <tr>
            <td>
                <div class="left"><small class="timestamp"><?=timeAgo(time())?></small></div>
                <div class="clear"></div>
            </td>
        </tr>
    </table>
</div>
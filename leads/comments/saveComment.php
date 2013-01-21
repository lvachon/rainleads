<?php
include '../inc/trois.php';
loginRequired();
$con = conDB();
$type = mysql_escape_string(strtolower($_POST['type']));
$user = new User(verCookie());
$thread = intval($_POST['id']);
$seperator = "&middot;";
$allowed_tags = '<b><span><i><p><em><strong><hr /><a><img><ul><li><ol>';
$comment = mysql_escape_string(strip_tags($_POST['comment'],$allowed_tags));
if(!intval($user) || !intval($thread) || !strlen($type) || !strlen($comment)){errorMsg("An error occurred saving your message; Not enough data.");}
mysql_query("INSERT INTO comments(thread_id,type,user_id,comment,datestamp) VALUES($thread,'$type',{$user->id},'$comment',".time().")",$con);
$id = mysql_insert_id($con);
$comment = $_POST['comment']; 
$user = new User(verCookie());?>
    <div class="news_comments comment_<?=$id?>">
        <table width='90%' cellpadding="2" cellspacing="0" class="comment_<?=$id?> " >
            <tr  valign="top">
              <td width="26"><?=$user->avatar(25,25);?></td>
              <td valign="top">
                <span class="name"><?=$user->name();?></span>
                <?=nl2br(clickme($comment));?><br />
                <div class="list-meta">
                    <small class="timestamp">
                        <?=timeAgo(time())?> 
                        <?=$seperator?>&nbsp; 
                        <a href="javascript:void(0)" onclick="deleteComment(<?=$id?>,<?=$thread?>);">Delete</a> 
                    </small> 
                </div>
              </td>
            </tr>
        </table>
    </div>
<?php die();?>
<?php
include '../inc/trois.php';
loginRequired();
$con = conDB();
$user = new User(verCookie());
$viewer = new User(verCookie());
$id = intval($_POST['id']);
$del = mysql_query("DELETE FROM comments WHERE id=".$id." AND user_id=".$user->id." LIMIT 1",$con);
$newsid  = intval($_POST['news_id']);
$newstype = mysql_escape_string($_POST['type']);
$direction = mysql_escape_string($_POST['direction']);
$seperator = '&middot;';
//die("SELECT * FROM comments WHERE type='{$newstype}' AND thread_id={$newsid} ORDER BY datestamp {$direction} LIMIT 3");

$q = mysql_query("SELECT * FROM comments WHERE type='{$newstype}' AND thread_id={$newsid} ORDER BY datestamp {$direction} LIMIT 3",$con);
while($c = mysql_fetch_array($q)){
	$cuser = new User($c['user_id']); ?>
	<table width='100%' cellpadding="0" cellspacing="0" class="news_comments" id="comment_<?=$c['id']?>" >
		<tr  valign="top">
		  <td width="48"><?=$cuser->avatar(40,0,true);?></td>
		  <td>
			<a href='<?=$HOME_URL;?>profile/?id=<?=$cuser->id;?>'><?=$cuser->name();?></a>
			<?=nl2br($c['comment']);?><br />
			<div class="list-meta">
				<?=timeAgo($c['datestamp'])?> 
				<?=$seperator?>&nbsp; 
                <a href="javascript:void(0);" onClick="like('comment',<?=$c['id']?>);"><span id='like_comment_<?=$c['id']?>'><?=count(getLikes('comment',$c['id']))?></span> <?php if(count(getLikes('comment',$c['id']))!= 1){ echo tl('Likes');}else{echo tl('Like'); }?></a> 
                <?php if($cuser->id == $viewer->id || in_array($viewer->id,$ADMIN_IDS)){?> 
                    <?=$seperator?>&nbsp;
                    <a href="javascript:void(0)" onclick="delCom(<?=$c['id']?>,<?=$newsid?>,'<?=$newstype?>','<?=$direction?>');"><?=tl('Delete');?></a> 
                <?php } ?>
			</div>
		  </td>
		</tr>
	</table>
<?php }//close while ?>

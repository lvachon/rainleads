<?php function getActivity($type,$limit,$content_id = 0){ 
	//echo "hello";
	
	$con = conDB();
	global $HOME_URL,$viewer,$HOME_DIR;
	$account = $viewer->getAccount();?>
	<script type="text/javascript">
		function postComment(id){
			$.post('<?=$HOME_URL?>comments/saveComment.php',{'id':id,'type':'action','comment':$('#com'+id).val()},function(data){$('#comments'+id).prepend(data);$('#com'+id).val('');});
			comCount = parseInt($('#comCount_'+id).html())+1;
			$('#comCount_'+id).html(comCount);
		}
			
		function deleteComment(comId,actId){
			$.post('<?= $HOME_URL ?>comments/deleteComment.php',{id:comId});
			$('.comment_'+comId).fadeOut();
			comCount = parseInt($('#comCount_'+actId).html())-1;
			$('#comCount_'+actId).html(comCount);
		}
	</script>
	<?php $qry = "actions WHERE 1";
	switch($type){
		case 'all':
		$qry .= " AND account_id = {$account->id} ";
		break;
		case 'lead':
		$qry .= " AND lead_id = {$content_id}";
		break;
	}
	
	if(intval($limit)){
		$limit_clause = "LIMIT $limit";
	}else{
		$limit_clause = "";
	}
	$getActivity = mysql_query("SELECT * FROM {$qry} ORDER BY datestamp DESC $limit_clause",$con) or die(mysql_error());?>
    <?php if($type == 'lead'){?>
       <a href="javascript:void(0);" onClick="$.post('<?=$HOME_URL?>wall/upload.php?id=<?=$_GET['id']?>',function(data){$.fancybox(data);});" class="button blue_button right">Add Note</a>
       
    <?php } ?>
    <div class="clear"></div>
    <div id="activity_feed">
       <?php while($act = mysql_fetch_array($getActivity)){
            $use = new User($act['user_id']);
            $getComments = mysql_query("SELECT * FROM comments WHERE `type` = 'action' AND thread_id = {$act['id']} ORDER BY datestamp DESC",$con);
            $comCount = mysql_num_rows($getComments);
			//var_dump($act);
            include $HOME_DIR."/wall/types.php";	
            ?>
            <div id="activity<?=$act['id']?>" class="activity <?php if($act['datestamp'] > time()-86400){?>new<?php } ?>">
                <table cellpadding="2" width="100%">
                    <tr>
                        <td rowspan="3" width="30"><?=$use->avatar(24,24)?></td>
                        <td valign="top"><span class="name"><?=$use->name()?></span> <?=$action?></td>
                    </tr>
                    <tr>
                        <td class="small"><?=$detail?> <?=$link?></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="left"><small class="timestamp"><?=timeAgo($act['datestamp'])?></small></div>
                            <!--<div class="right" onClick="$('.comments_<?=$act['id']?>').fadeToggle();"><img src="<?=$HOME_URL?>img/comment-icon.png"  /><span id="comCount_<?=$act['id']?>" class="comment_count"><?=number_format($comCount)?></span></div>-->
                            <div class="clear"></div>
                        </td>
                    </tr>
                </table>
            </div>
            <!--<div style="display:none;" class="comments_<?=$act['id']?>">
                <div class="comForm">
                <div class="left"><textarea name="comment" id="com<?=$act['id']?>" style="width:256px;"></textarea></div>
                <div class="right"><input type="button" onClick="postComment(<?=$act['id']?>)" class="button" value="Submit" /></div>
                <div class="clear"></div>
                </div>
                <div id="comments<?=$act['id']?>">
                    <?php while($com = mysql_fetch_array($getComments)){ $comUser = new User($com['user_id']);?>
                        <div class="news_comments comment_<?=$com['id']?>">
                            <table width='90%' cellpadding="2" class="comment_<?=$com['id']?>" >
                                <tr valign="top">
                                  <td width="26"><?=$comUser->avatar(24,24);?></td>
                                  <td valign="top">
                                    <span class="name"><?=$comUser->name();?></span>
                                    <?=nl2br(clickme($com['comment']));?><br />
                                    <div class="list-meta">
                                        <small class="timestamp">
                                            <?=timeAgo($com['datestamp'])?> 
                                            <?php if($viewer->id == $com['user_id'] || $viewer->id ==$account->user_id){?> 
                                                |&nbsp; 
                                                <a href="javascript:void(0)" onclick="deleteComment(<?=$com['id']?>,<?=$act['id']?>);">Delete</a> 
                                            <?php } ?>
                                        </small>
                                    </div>
                                  </td>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>-->
        <?php } ?>
     </div>
<?php }?>
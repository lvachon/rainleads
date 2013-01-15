<?php include 'head.php'; include '../support/main.php';
$suser = new User(32);
$viewer = new User(verCookie());
$viewer = $suser;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		function read(id) {
			$("#convoHidnContainer"+id).slideToggle();
			if ($("#convo"+id).hasClass('new')) {
				$("#convo"+id).removeClass('new');
				$.post("../support/postmaster.php",{"read":id,"as32":true},function(data){
					var tots = data.split("_");
					var newnum = tots[0];
					var grandtotal = tots[1];
					if (newnum == "0") {
						$('#new'+id).hide();
					} else {
						$('#new'+id+' span').html(newnum);
					}
					if (grandtotal == "0") {
						$('#msgs .indicate').hide();
					} else {
						$('#msgs .counter').html(grandtotal);
					}
				});
			}
		}
		
	
		function postReply(id,user_id){
			var msg = $("#reply"+id).val();
			$.post("../support/postmaster.php",{"to":user_id,"ajax_message":msg,"as32":$("#as32_"+id).is(':checked'),"conversation_id":id},function(data){
				<?php if(DEF_MAIL_NEW_ON_TOP){?>$("#convoContainer"+id).prepend(data);<?php
						}else{?>
						$("#convoContainer"+id).append(data);
				<?php } ?>
				$("#reply"+id).val("");
			});
		}
		
		
    </script>


</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;All Users</h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is your Support Ticket Panel. Here you can manage the support messages from members of your social network.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
            	<?php if(isset($_GET['archived'])){
					$archived = true;
					$type = "Open";
				}elseif(isset($_GET['all'])){
					$archived = 'all';
					$type = "All";
					//echo "YOU GOT THIS";
				}else{
					$archived = false;
					$type = "Archived";
				} ?>
				<h4><?=$type?> Threads <?php if($archived != 'all'){?><a class="right button" href="?all">View All Tickets</a><?php }else{ ?><a class="right button" href="support_tickets.php">View Open Tickets</a><?php } ?><div class="clear"></div></h4>
                <div class="ui-widget-content">
                	<div id='mailContainer'>
		                    <?php $mail = new Mail(32);
							
		                    if(!count($mail->getConvos($archived))){?>
		                    	<div id="nf" class="light">There are no support tickets yet.</div>
							<?php }
							
							//echo $archived;
		                    foreach($mail->getConvos($archived) as $convo){ ?>
		                        <div class='convo<?php if ($convo->new_message_count>0) { echo ' new'; } ?>' id='convo<?=$convo->id?>'>
		                        	<div class="click" onclick="read('<?=$convo->id?>');">
		                                <table cellpadding="2" cellspacing="0" width='100%'>
		                                    <tr valign="top">
		                                        <td rowspan="3" width='76' style="padding: 0px;" valign="top"><?=$convo->otherUser->avatar(64,64,false);?></td>
		                                        <td style="padding-top: 0px;" valign="top"><h3><?=$convo->otherUser->name();?></h3></td>
		                                    	<td align="right" valign="top">STATUS: <?php if($convo->user1_hide == 1 || $convo->user2_hide == 2){?>Resolved<?php }else{ ?>Open<?php } ?></td>
		                                    </tr><tr>
		                                        <td valign="top">
		                                            <?=$convo->message_count;?> message<?php if ($convo->message_count!=1) { echo 's'; } ?> 
		                                            <?php if ($convo->new_message_count>0) { ?><strong id="new<?=$convo->id?>">(<span><?=$convo->new_message_count;?></span> new)</strong><?php } ?>
		                                        </td>
		                                        <td align="right" valign="bottom">
		                                            <div class="light"><?= date("M jS Y",$convo->firstMsg)?>
		                                                <?php if ($convo->message_count>1 && date("Ymd",$convo->firstMsg)!=date("Ymd",$convo->lastMsg)) { ?>&ndash; <?=date("M jS Y",$convo->lastMsg)?><?php } ?>
		                                            </div>
		                                        </td>
		                                    </tr><tr valign="bottom">
		                                    	<td colspan="2"><div style="margin-top: 4px;"><?=$convo->subject?></div></td>
		                                    </tr>
		                                </table>
		                            </div>
		                            <div id='convoHidnContainer<?=$convo->id?>' style='display:none;'>
		                                <div class='mailConvoContainer' id='convoContainer<?=$convo->id?>'>
		                                    <?php foreach($convo->getMessages() as $msg){
		                                        echo $msg->getHTML();
		                                    }?>
		                                </div>
		                                <div class="message">
		                                    <table width="100%" cellpadding="2" cellspacing="0">
		                                        <tr valign="top">
		                                            <td width="68" align="right" style="padding: 0 8px 0 0;" rowspan="2"><?= $viewer->avatar(50,50,true); ?></td>
		                                            <td style="padding-top: 0px;"><textarea id='reply<?=$convo->id?>' style="width:600px; height: 40px;" ></textarea></td>
		                                        </tr><tr>
		                                            <td>
		                                            	<label><input type='checkbox' value='1' id='as32_<?=$convo->id?>'/>Reply as <?=$suser->name();?></label>
		                                            	<div class="button right" onClick="postReply('<?=$convo->id?>',<?=$convo->otherUser->id;?>);">reply</div>
		                                                <div class="clear"></div>
		                                            </td>
		                                        </tr>
		                                    </table>
		                                </div>
		                            </div>
		                        </div>
			                <?php } ?>
		                </div>
                   <div id='paginatinon' class='pagination'/><?=$pgl;?></div>
                </div>
                
            </div>
        </div>
        
</div>
</body>
</html>

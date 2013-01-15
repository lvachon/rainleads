<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include '../inc/trois.php'; include 'main.php';
loginRequired(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php include '../inc/head.php'; ?>
	<script type="text/javascript">
		function read(id) {
			$("#convoHidnContainer"+id).slideToggle();
			if ($("#convo"+id).hasClass('new')) {
				$("#convo"+id).removeClass('new');
				$.post("postmaster.php",{"read":id},function(data){
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
			$.post("postmaster.php",{"to":user_id,"ajax_message":msg,"conversation_id":id},function(data){
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
	<?php include('../inc/header.php'); ?>
	<div id="content" class="inner">
		<div id="side" class="left">
	    	<?php include('../inc/sidenav.php'); ?>
	        
	    </div>
            <div class="left" id="main">
            	<h2 class="left"><?php if(isset($_GET['archived'])){?>Resolved<?php } ?> Support Tickets</h2>
                <a href='javascript:void(0);' onclick="$.get('<?=$HOME_URL?>support/postmaster.php?to=32',function(data){$.fancybox(data);});" class="button right">Open Support Ticket</a>
                <?php if(isset($_GET['archived'])){?>
                    <a href='index.php' class="button right" style="margin-right:5px;">Show Open Tickets</a>
                <?php }else{ ?>
                    <a href='?archived' class="button right" style="margin-right:5px;">Show Resolved Tickets</a>
				<?php } ?>
                <div class="clear"></div>
            	<hr />
            
                <div id='mailContainer'>
                    <?php $mail = new Mail($viewer->id);
					if(isset($_GET['archived'])){
						$archived = true;
					}else{
						$archived = false;
					}
					//var_dump($mail->getConvos($archived));
                    if(!count($mail->getConvos($archived))){?>
                    	<div id="nf" class="light">You have not sent or received any support messages yet. <a href="javascript:void(0);" onclick="$.get('<?=$HOME_URL?>support/postmaster.php?to=32',function(data) { $.fancybox(data); });" class="bold">Send one now!</a></div>
					<?php }
					
                    foreach($mail->getConvos($archived) as $convo){ ?>
                        <div class='convo<?php if ($convo->new_message_count>0) { echo ' new'; } ?>' id='convo<?=$convo->id;?>'>
                        	<div class="click" onclick="read('<?=$convo->id?>');">
                                <table cellpadding="2" cellspacing="0" width='100%'>
                                    <tr valign="top">
                                        <td rowspan="3" width='76' style="padding: 0px;"><?=$convo->otherUser->avatar(64,64,false);?></td>
                                        <td style="padding-top: 0px;" valign="top"><h3 class="left"><?=$convo->otherUser->name();?></h3></td>
                                    	<td align="right" valign="top"><?php if(!isset($_GET['archived'])){?><a href="../support/delete.php?id=<?=$convo->id?>">Mark as Resolved</a><?php } ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?=$convo->message_count;?> message<?php if ($convo->message_count!=1) { echo 's'; } ?> 
                                            <?php if ($convo->new_message_count>0) { ?><strong id="new<?=$convo->id;?>">(<span><?=$convo->new_message_count;?></span> new)</strong><?php } ?>
                                        </td>
                                        <td align="right">
                                            <div class="light"><?= date("M jS Y",$convo->firstMsg)?>
                                                <?php if ($convo->message_count>1 && date("Ymd",$convo->firstMsg)!=date("Ymd",$convo->lastMsg)) { ?>&ndash; <?=date("M jS Y",$convo->lastMsg)?><?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                    	<td colspan="2"><div style="margin-top: 4px;"><?=$convo->subject;?></div></td>
                                    </tr>
                                </table>
                            </div>
                            <div id='convoHidnContainer<?=$convo->id;?>' style='display:none;'>
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
                                            	<div class="button right" style="margin-top:5px;margin-right:23px;" onClick="postReply('<?=$convo->id?>',<?=$convo->otherUser->id;?>);">reply</div>
                                                <div class="clear"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
	                <?php } ?>
                </div>
            </div>
          
            <div class="clear"></div>
        </div>
    	<?php include '../inc/footer.php'; ?>
    </div>    
</body>
</html>                	
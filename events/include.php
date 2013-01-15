<div class="list" style="border-bottom:1px solid #ccc;padding:5px;">
    <table width="100%" cellpadding="3">
        <tr valign="top">
            <td colspan="2"><strong><?=$event->title; ?></strong></td>
        </tr>
        <?php if(intval($event->lead_id)){
			$lead = $event->getLead();?>
            <tr>
            	<td colspan="2"><strong>Lead:</strong> <a href="../leads/lead.php?id=<?=$lead->id?>"><strong><?=$lead->name?></strong></a></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2"><?=$event->when()?><br /><?=nl2br($event->description);?></td>
        </tr>
        <tr valign="bottom">
           <td>
           		<?php if(intval($viewer->id)){?>
                 <a href="javascript:void(0);" onclick="$.post('<?=$HOME_URL?>events/setup-reminder.php?id=<?=$event->id?>',function(data){$.fancybox(data);});">Set Reminder</a>
                <?php }?>
                <?php if(intval($viewer->id)){
                    if($event->user_id == $viewer->id){?>
                        | <a href='javascript:void(0);' onclick="$.post('<?=$HOME_URL?>events/upload-pop.php?id=<?=$event->id;?>',function(data){$.fancybox(data);});">Edit</a> | <a href="javascript:void(0);" onclick="$.post('<?=$HOME_URL?>events/delete.php?id=<?=$event->id?>',function(data){$.fancybox(data);});">Delete</a>
                    <?php }
					
				}?>
            </td>
        </tr>
    </table>
    <?php if(count($viewer->reminders($event->id))){?>
        <div id="reminders">
            <strong>Reminders Set</strong><br />
            <?php $rcnt = 0; 
			foreach($viewer->reminders($event->id) as $remind){?>
                <div class="container left" id="remind_<?=$remind->id?>"><span class="left"><?=date('n/j/y g:iA',$remind->datestamp)?></span><a href="javascript:void(0);" onclick="killReminder(<?=$remind->id?>)">x</a><div class="clear"></div></div> 
            	<?php $rcnt++; if(!intval($rcnt%5)){?><div class="clear"></div>
				<?php } 
			} ?>
            <div class="clear"></div>
        </div>
    <?php } ?>
</div>
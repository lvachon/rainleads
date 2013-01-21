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
        
    </table>
</div>
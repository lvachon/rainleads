<div class="plan-<?= $account->membership ?> membership-plan" style="margin-top:45px;">
	<center>
		<div class="small">Account Type:</div>
		<img src="/img/plan-<?= $account->membership ?>.png" ?>
	</center>
	Rain Leads<br/><span class="strong big"><?= ucwords($account->membership) ?></span>
</div>
<?php
	$con = conDB();
	
	$storage = floor($account->storageUsed()/10485.76) / floor($account->storageLimit()/10485.76);
	$storageP = $storage*100;
	
	$users = count($account->members) / $account->userLimit();
	$usersP = $users*100;
	
	$r = mysql_query("SELECT count(*) from forms where account_id={$account->id} and deleted=0",$con);
	$x= mysql_fetch_array($r);
	
	$formLimit = $x[0] / intval($account->formLimit());
	$formLimitP = floor($formLimit*100);
?>
<table class="membership-details">	
	
	<tr>
		
		<td style="line-height:16px;" align="left"><span class="strong">Storage:</span><br/><div class="progress_bar"><div class="fill" style="width:<?= $storageP ?>%;"></div></div><span class="strong"><?=floor($account->storageUsed()/10485.76)/100;?>MB</span> out of <span class="strong"><?=floor($account->storageLimit()/10485.76)/100;?>MB</span> used
		<?php if($account->membership!="free" && $account->membership!="lite"){?>
				 - <a href="<?=str_replace('http://','https://',$HOME_URL)?>account/alacarte.php">Get More!</a>		
			<?php } ?>
		</td>
    </tr>
    <tr>
		
		<td style="line-height:16px;" align="left">
			<span class="strong">Users:</span><br/>
			<div class="progress_bar"><div class="fill" style="width:<?= $usersP ?>%;"></div></div>
			<span class="strong"><?=count($account->members);?> </span> out of <span class="strong"><?=$account->userLimit();?> Users</span> registered 
			<?php if($account->membership!="free" && $account->membership!="lite"){?>
				 - <a href="<?=str_replace('http://','https://',$HOME_URL)?>account/alacarte.php">Get More!</a>		
			<?php } ?>
			<div class="clear"></div>
		</td>
    </tr>
    <!--<tr>
		
		<td style="line-height:16px;">
		<span class="strong">Forms:</span><br/>
		<div class="progress_bar"><div class="fill" style="width:<?= $formLimitP ?>%;"></div></div>
		<span class="strong"><?=$x[0];?> </span> out of <span class="strong"><?php if(intval($account->formLimit())>1000){echo "Unlimited";}else{echo $account->formLimit();}?> Forms</span> created
			<?php if($account->membership!="free" && $account->membership!="lite"){?>
				 - <a href="<?=str_replace('http://','https://',$HOME_URL)?>account/alacarte.php">Get More!</a>		
			<?php } ?>
		<div class="clear"></div>
		</td>
  </tr>-->
    <?php if($account->membership=="free"){?>
    <tr>
		
		<td align="left"><em><span class="strong">Trial Expires:</span> <?= date("F jS Y",$account->expiration); ?></em></td>
	</tr>
	<?php } ?>
</table>
<div class="clear"></div>
<div style='height:30px;clear:both;'></div>

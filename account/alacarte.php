<!DOCTYPE html>
<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
if(intval($account->data['promoEnds'])>time()){
	errorMsg("A-la-carte additions are not available during free/promotional billing periods.  Your account's promotional period ends: ".date("M j, Y",$account->data['promoEnds']));
	die();
}
if($account->membership=="free" || $account->membership=="lite"){
	errorMsg("Your account type is not able to add a la carte items, please <a href='{$HOME_URL}account/upgrage.php'>upgrade</a> your account to Basic or Pro.");
	die();
}
$things = array(array("name"=>"+100MB storage","price"=>5,"tx"=>"storage"),array("name"=>"+1 User account","price"=>5,"tx"=>"user"));
?>
<html>
<?php include('../inc/head.php'); ?>
<body>
<?php include('../inc/header.php'); ?>
<style>
	.empty{
		background:#D7F0FF;
		border-color:#659ABC !important;;
	}
</style>
<div id="content" class="inner">
	<div id="side" class="left">
		<?php include('../inc/sidenav.php') ?>
	</div>
	<div id="main" class="left">
		<div id='current_account'>
			<h2 class="left">Account Information</h2>
			<hr class="title_line" />
			
			<div class="plan-<?= $account->membership ?> membership-plan">
				<center>
					<div class="small">Account Type:</div>
					<img src="../img/plan-<?= $account->membership ?>.png" ?>
				</center>
				Rain Leads<br/><span class="strong big"><?= ucwords($account->membership) ?></span>
			</div>
			<table class="membership-details">
				<tr>
					<td><span class="strong">Plan: </span></td>
					<td>Rain Leads <?= ucwords($account->membership) ?></td>
				</tr>
				<tr>
					<td><span class="strong">Expires: </span></td>
					<td><?= ucwords($account->expiration) ?></td>
				</tr>
				<tr>
					<td><span class="strong">Storage:</span></td>
					<td><?=floor($account->storageUsed()/10485.76)/100;?>/<?=floor($account->storageLimit()/10485.76)/100;?>MB</td>
				</tr>
				<tr>
					<td><span class="strong">Forms:</span></td>
					<td><?php 	
							$con = conDB();
							$r = mysql_query("SELECT count(*) from forms where account_id={$account->id} and deleted=0",$con);
							$cnt = mysql_fetch_array($r);
							echo intval($cnt[0]);
						?>/<?php $f=intval($account->plandata['forms']);if($f<100){echo $f;}else{echo "Unlimited";}?>
					</td>
				</tr>
				<tr>
					<td><span class="strong">Users:</span></td>
					<td><?=count($account->members)-1;?>/<?=$account->userLimit();?></td>
				</tr>
			</table>
		</div>
		<div id='chooseclass'>
			<h2>Current Items</h2>
			<hr class='title_line'/>
			<form method='post' action='buy.php' id='buyform'>
				<input type='hidden' name='thing'id='thing' value='0'/>
				<input type='hidden' name='delta' id='delta' value='0'/>
				<table width='100%'>
					<tr><th>Item</th><th>Cost</th><th>Actions</th></tr>
					<?php 
						$r = mysql_query("SELECT count(*) from transactions where type='add_storage' and account_id={$account->id}",$con);
						$add = mysql_fetch_array($r);
						$add = intval($add[0]);
						$r = mysql_query("SELECT count(*) from transactions where type='rem_storage' and account_id={$account->id}",$con);
						$rem = mysql_fetch_array($r);
						$rem = intval($rem[0]);
						$total = $add-$rem;
						for($i=0;$i<$total;$i++){
						?>
							<tr>
								<td>Storage (100MB)</td>
								<td>$<?=$things[0]['price'];?>.00</td>
								<td>
									<input type='button' value='Remove' onclick='if(confirm("Do you really want to remove this from your subscription?")){$("#delta").val("-1");$("#thing").val("0");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php } ?>
							<tr>
								<td>Storage (+100MB)</td>
								<td>$<?=$things[0]['price'];?>.00</td>
								<td>
									<input type='button' value='Subscribe' onclick='if(confirm("Do you really want to add this to your subscription?")){$("#delta").val("1");$("#thing").val("0");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php
						$r = mysql_query("SELECT count(*) from transactions where type='add_user' and account_id={$account->id}",$con);
						$add = mysql_fetch_array($r);
						$add = intval($add[0]);
						$r = mysql_query("SELECT count(*) from transactions where type='rem_user' and account_id={$account->id}",$con);
						$rem = mysql_fetch_array($r);
						$rem = intval($rem[0]);
						$total = $add-$rem;
						for($i=0;$i<$total;$i++){
							?>
							<tr>
								<td>Additional User</td>
								<td>$<?=$things[1]['price'];?>.00</td>
								<td>
									<input type='button' value='Remove' onclick='if(confirm("Do you really want to remove this from your subscription?")){$("#delta").val("-1");$("#thing").val("1");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php } ?>
							<tr>
								<td>Add another user</td>
								<td>$<?=$things[1]['price'];?>.00</td>
								<td>
									<input type='button' value='Subscribe' onclick='if(confirm("Do you really want to add this to your subscription?")){$("#delta").val("1");$("#thing").val("1");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php
					?>
				</table>
			</form>
		</div>
	 </div>
	<div class="clear"></div>
</div>
<script>
$(function() {
  $('form input.suggested').each(function(data){
  		if($(this).val() == ''){
  			if($(this).attr('type') !='file'){
	  			$(this).addClass('empty');
	  		}
  		}
  });
});
</script>
<?php include('../inc/footer.php'); ?>
</body>
</html>

<!DOCTYPE html>
<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}

$things = array(array("name"=>"+100MB storage","price"=>5,"tx"=>"storage"),array("name"=>"+1 User account","price"=>12,"tx"=>"user"));
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
		
		<div id='chooseclass'>
			<h2>Current Items</h2>
			<hr class='title_line'/>
			<form method='post' action='buy.php' id='buyform'>
				<input type='hidden' name='thing'id='thing' value='0'/>
				<input type='hidden' name='delta' id='delta' value='0'/>
				<table width='100%'>
					<tr><th>Item</th><th>Cost</th><th></th></tr>
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
								<td>$<?=$things[0]['price'];?>.00/mo</td>
								<td>
									<input type='button' value='Remove' onclick='if(confirm("Do you really want to remove this from your subscription?")){$("#delta").val("-1");$("#thing").val("0");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php } ?>
							<tr>
								<td>Storage (+100MB)</td>
								<td>$<?=$things[0]['price'];?>.00/mo</td>
								<td>
									<input type='button' value='Add' onclick='if(confirm("Do you really want to add this to your subscription?")){$("#delta").val("1");$("#thing").val("0");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php
						$r = mysql_query("SELECT count(*) from transactions where (type='add_user') and account_id={$account->id}",$con);
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
								<td>$<?=$things[1]['price'];?>.00/mo</td>
								<td>
									<input type='button' value='Remove' onclick='if(confirm("Do you really want to remove this from your subscription?")){$("#delta").val("-1");$("#thing").val("1");$("#buyform")[0].submit();}'/>
								</td>
							</tr>
						<?php }
						 if($account->membership=="paid"){
							$r = mysql_query("SELECT * from transactions where account_id={$account->id} and type='sub_create'",$con);
							$autx = mysql_fetch_array($r);?>
							<tr>
								<td>Admin User</td>
								<td>$<?=$autx['amount'];?>.00/mo</td>
								<td>&nbsp;</td>
							</tr>
						<?php } ?>
						<tr>
							<td>Add <?php if($account->membership!="paid"){echo "first";}else{echo "another";}?> user</td>
							<td>$<?=$things[1]['price'];?>.00/mo <?php if($account->membership=="paid"){?>x <input type='text' id='qtyu' value='1'/><?php } ?></td>
							<td>
								<input type='button' value='Add' <?php if($account->membership=="paid"){?>onclick='if(confirm("Do you really want to add this to your subscription?")){$("#delta").val($("#qtyu").val());$("#thing").val("1");$("#buyform")[0].submit();}'<?php }else{ ?> onclick='$("#delta").val("1");$("#thing").val("1");$("#buyform")[0].submit();'<?php }?>/>
							</td>
						</tr>
				</table>
			</form>
			<span id='disclaimer'>Changes to your account will happen immediately, changes to your billing will occur on the next billing date.</span>
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

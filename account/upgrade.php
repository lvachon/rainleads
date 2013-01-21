<?php include '../inc/trois.php';
if(!$_SERVER['HTTPS']){header("Location: ".str_replace("http://","https://",$HOME_URL)."account/upgrade.php");die();}
loginRequired();
accountRequired();
$account = $viewer->getAccount();
if($viewer->id!=$account->user_id){errorMsg("Only the account owner can change the billing information");die();}
?>
<!DOCTYPE html>
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
<script>
	function changeTotal(name){
		newprice = $('#price_'+name).html();
		console.log(newprice);
		$('#total').show();
		$('#price').html(newprice);
		$('#planname').html(capit(name));
	}
	function capit(string){
		return string.charAt(0).toUpperCase() + string.slice(1);
	}
</script>
<script type="text/javascript" >
	function myOnComplete(){};
	$(document).ready(function() {
		$("#payform").RSV({
			onCompleteHandler: myOnComplete,
			displayType: "display-html",
			errorFieldClass: "text-error",
				rules: [
					"required,plan,Choose a plan.",
					"required,bfname,Enter your First Name.",
					"required,blname,Enter your Last Name.",					
					"required,bzip,Enter your Postal Code.",
					"required,ccnum,Enter your Credit Card number.",					
					"required,expmo,Enter your Expiration month",
					"required,expyr,Enter your Expiration year",
					
					"required,ccv,X,Enter your CC Security Code."
				]
			});
		
	});	
</script>
<style>
#rsvErrors {
	  display: none;
	  background-color: #ffffcc;
	  border: 1px solid red;
	  padding: 5px;
	  text-align: left;
	  font-size:12px;
	  margin: 10px;
	}
	.red {
		color:#D13C29;
		position: relative;
		top:-1px;
		left:2px;
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
			<div class="clear"></div>
            <div style="margin-left:40px;">
			<?php include '../inc/accountdetails.php';?>
			</div>
        </div>
		<div id='chooseclass'>
        	<br/>
			<h2 class="left">Choose Subscription<span class="red"><strong>*</strong></span></h2>
			<hr class='title_line'/>
            <div class="clear"></div>
			<form method='post' action='pay.php' id='payform'>
				<table width='100%' class="striped-table" cellpadding="4">
					<tr style="font-weight:bold;"><th></th><th>Plan</th><th>Forms</th><th>Users</th><th>Storage</th><th>Leads</th><th>Price</th></tr>
					<?php foreach($SUB_PLANS as $p){
						if($p['name']=="free" || $p['name']==$account->plandata['name']){continue;}
						$norad=false;
						if($p['price']<$account->plandata['price'] || true){
							$con = conDB();
							$r = mysql_query("SELECT count(*) from forms where account_id={$account->id} and deleted = 0",$con);
							$fc = mysql_fetch_array($r);
							$fc = intval($fc[0]);
							if($p['forms']<$fc || $p['users']<count($account->members) || $p['storage']<$account->storageUsed()){
								$norad=true;
								$onclick = "$.fancybox(\"You cannot downgrade to this plan.  Your current usage exceeds this plan&apos;s limits.  Please archive forms, remove users, or delete files to bring your account under the limits shown for this plan.\");";
							}else{
								$onclick = "$.fancybox(\"Downgrading your subscription will affect the price of next billing cycle. Your level of access will be affected IMMEDIATELY.\");";
							}
						}else{
							$onclick="";
						}?>
                        <tr align='center' valign='middle' onclick='<?=$onclick;?>'>
                        	<td width="20" valign='middle'><?php if(!$norad){?><input name='plan' type='radio' <?php if($p['name']=='basic'){?> checked="checked"<?php } ?> value='<?=$p['name'];?>' id="<?=$p['name']?>" onChange="changeTotal('<?=$p['name']?>');"/><?php } ?></td>
							<td class="name" align="left" valign='middle' >
                            	<label for="<?=$p['name']?>">
								<div class="plan-<?= $p['name']; ?> " style='margin:auto;float:none;'>
									
									<img src="<?= $HOME_URL ?>img/plan-<?= $p['name'] ?>.png" height="16" style="position:relative; top:2px;" />&nbsp;<span class="strong blue big"><?= ucwords($p['name']); ?></span>
								</div>
                                </label>
							</td>
							<td width="80" valign='middle'><label for="<?=$p['name']?>"><?php $f=intval($p['forms']);if($f<100){echo $f;}else{echo "Unlimited";}?></label></td>
							<td valign='middle'><label for="<?=$p['name']?>"><?php $f=intval($p['users']);if($f<100){echo $f;}else{echo "Unlimited";}?></label></td>
							<td valign='middle'><label for="<?=$p['name']?>"><?=intval($p['storage']);?>MB</label></td>
							<td valign='middle'><label for="<?=$p['name']?>">Unlimited</label></td>
							<td valign='middle'><label for="<?=$p['name']?>"><div id="price_<?=$p['name']?>">$<?=number_format($p['price'],2);?> a month</div></label></td>
							
						</tr>
                        
					<?php }?>
				</table>
                <br /><br/>
                <h2 class="left">Provide Payment Details</h2>
                <hr class='title_line'/>
                <div class="clear"></div>
                <div id="rsvErrors"></div>
				<?php include '../inc/billing_form.php'; ?>
				
                <div class="right" style="margin-right:40px;width:89px;text-align:center;font-weight:bold; display:none;" id="total">
                	30 Days <span id="planname"></span> Access<br />
                    Total<br />
                	<span id="price"></span>
                </div>
                <div class="clear"></div>
                <?php if($account->membership == "free"){?><p>Please note that when upgrading from a free trial you will not be billed until the trial period has expired.</p> <?php } ?>
				<input class="right button" type='submit' value='Complete Checkout' style="margin-right:40px;"/><br clear="all" />
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

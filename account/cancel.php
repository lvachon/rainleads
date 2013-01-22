<?php include '../inc/trois.php';
include 'authnetfunction.php';
loginRequired();
accountRequired();
$con = conDB();
$account = $viewer->getAccount();
$AUTHNET_API_LOGIN_ID="7tZv74PcN7";
$AUTHNET_API_TX_KEY="6WM9t39tQ9R9Us9Y";

 if(intval($_GET['go'])){
	$xml = "<?xml version='1.0' encoding='utf-8'?>\n";
	$xml .= "	<ARBCancelSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">\n";
	$xml .= "		<merchantAuthentication>\n";
	$xml .= "			<name>$AUTHNET_API_LOGIN_ID</name>";
	$xml .= "			<transactionKey>$AUTHNET_API_TX_KEY</transactionKey>";
	$xml .= "		</merchantAuthentication>\n";
	$xml .= "		<subscriptionId>{$account->sub_id}</subscriptionId>\n";
	$xml .= "	</ARBCancelSubscriptionRequest>";
	$response = send_request_via_curl("api.authorize.net","/xml/v1/request.api",$xml);
	if ($response)
	{
		/*
		 a number of xml functions exist to parse xml results, but they may or may not be avilable on your system
		please explore using SimpleXML in php 5 or xml parsing functions using the expat library
		in php 4
		parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
		*/
		list ($refId, $resultCode, $code, $text, $subscriptionId) =parse_return($response);
	
		if($text!="Successful."){
			errorMsg($text);
			die();
		}
	
	
		$xml = str_replace($_POST['ccnum'],"xxxx xxxx xxxx ".substr($_POST['ccnum'],strlen($_POST['ccnum'])-4),$xml);
	
		mysql_query("INSERT INTO transactions(user_id,account_id,type,data) VALUES({$viewer->id},{$account->id},'sub_cancel','".mysql_escape_string(serialize(array('sent'=>$xml,'read'=>$response)))."')",$con);
	
	
		$fp = fopen('data.log', "a");
		fwrite($fp, "$xml\r\n");
		fwrite($fp, "$subscriptionId\r\n");
		fwrite($fp,"------------\r\n\r\n");
		fclose($fp);
		
		
		mysql_query("UPDATE accounts set sub_id='', plantype='free',mo_price=0,expires=0 where id={$account->id} LIMIT 1",$con);
		//remove storage additions
		$r = mysql_query("SELECT count(*) from transactions where type='add_storage' and account_id={$account->id}",$con);
		$add = mysql_fetch_array($r);
		$add = intval($add[0]);
		$r = mysql_query("SELECT count(*) from transactions where type='rem_storage' and account_id={$account->id}",$con);
		$rem = mysql_fetch_array($r);
		$rem = intval($rem[0]);
		$total = $add-$rem;
		for($i=0;$i<$total;$i++){
			mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'rem_storage','".mysql_escape_string(serialize("Automatic cancel upon subscription"))."',0,unix_timestamp())",$con);
		}
		//remove user additions
		$r = mysql_query("SELECT count(*) from transactions where type='add_user' and account_id={$account->id}",$con);
		$add = mysql_fetch_array($r);
		$add = intval($add[0]);
		$r = mysql_query("SELECT count(*) from transactions where type='rem_user' and account_id={$account->id}",$con);
		$rem = mysql_fetch_array($r);
		$rem = intval($rem[0]);
		$total = $add-$rem;
		for($i=0;$i<$total;$i++){
			mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'rem_user','".mysql_escape_string(serialize("Automatic cancel upon subscription"))."',0,unix_timestamp())",$con);
		}
		header("Location: index.php");
		die();
	}else{
		echo "Transaction Failed. <br>";
	}
 }
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
<div id="content" class="inner">
	<div id="side" class="left">
		<?php include('../inc/sidenav.php') ?>
	</div>
	<div id="main" class="left">
		<div id='current_account'>
			<h2 class="left">Cancel Account</h2>
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
						?>/Unlimited
					</td>
				</tr>
				<tr>
					<td><span class="strong">Users:</span></td>
					<td><?=count($account->members)-1;?>/<?=$account->userLimit();?></td>
				</tr>
			</table>
		</div>
		<div class="clear"></div>
		<div id='chooseclass' style="margin-top:25px;">
			Canceling your subscription will immediately downgrade your account to the free package.  
			Any features that are part of the paid packages will me unavailable.  
			Are you sure you wish to do this?
			
			<input type='button' value='Cancel Subscription' onclick='document.location.href="cancel.php?go=1";'/> <input type='button' value='Return to Account' onclick='document.location.href="index.php"';/>
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

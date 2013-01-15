<?php include 'inc/trois.php';
$account = showAccount();
if(intval($account->id) && !strlen($_GET['key'])){
	//errorMsg("Invalid invitation key.");
}?>
<!DOCTYPE html>
<html>
<?php include('inc/head.php'); ?>
<style>
	h1{
		display:block;
		float:none;	
	}
	#full_width{
		font-size:14px;
		line-height:20px;	
	}
</style>
<style>
	#full_width .inner {
		width:780px;
		margin-top:12px;
	}
	strong.blue {
		font-weight: bold;
		color:#0C7EB9;
	}
	#content input[type=text], #content textarea{
		padding:10px;
		border:1px solid #c8c8c8;
	}
	table tr td {
		padding: 5px;
		font-size: 13px;
		font-weight: bold;
		color:#666;
	}
	h1{
		font-size:24px;
		color:#333;
		display:block;	
	}
</style>
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:auto" class="inner">
    <div id="full_width" >
    <div class="inner" style="font-size:16px; line-height:20px;">
        <h1><?=cmsTitle("RTFenterprise"); ?></h1>
        <br />
        <p style=" padding:0 15px; line-height:25px; color:#333;"><?=nl2br(cmsRead("RTFenterprise")); ?></p>
        <div class="clear"></div>
        <br/>
         <form method="post" action="http://www.pearsestreet.com/quote-request.php">
	<input type="hidden" name="from" value="RainLeads">
		<table width="100%" style="font-weight:bold !important; color:#6B6455; line-height:10px; font-size:11px; font-family:Arial" cellpadding="5" cellspacing="5">
			<tbody><tr>
				<td align="left">Full Name</td>
			</tr>
			<tr>
				<td><input type="text" name="name" value="<?= $_GET['fullname'] ?>" class="full" value="" style="width:97%;"></td>
			</tr>
			<tr>
				<td align="left">Company Name</td>
			</tr>
			<tr>
				<td><input type="text" name="company" value="<?= $_GET['company'] ?>" class="full" value="" style="width:97%;"></td>
			</tr>
			<tr>
				<td><br>Email Address</td>
			</tr>
			<tr>
				<td><input type="text" name="email" value="<?= $_GET['email'] ?>" class="full" value="" style="width:97%;"></td>
			</tr>			
			<tr>
				<td colspan="2"><br>Optional Comments</td>
			</tr>
			<tr>
				<td colspan="2"><textarea name="comments" style="width:97%;" rows="6"><?= $_GET['comments'] ?></textarea>
			</td></tr>
			
			<tr>
				<td colspan="2" align="center">
				<script type="text/javascript"
   src="https://www.google.com/recaptcha/api/challenge?k=6LcBDrsSAAAAAH-_skXyVnfNV1lan1UzqL9zPP9a">
</script>

<noscript>
   <iframe src="https://www.google.com/recaptcha/api/noscript?k=6LcBDrsSAAAAAH-_skXyVnfNV1lan1UzqL9zPP9a"
       height="300" width="500" frameborder="0"></iframe><br>
   <textarea name="recaptcha_challenge_field" rows="3" cols="40">
   </textarea>
   <input type="hidden" name="recaptcha_response_field"
       value="manual_challenge">
</noscript>
				<br><input type="submit" value="Send Message" class="button green_button" style="font-size:13px; padding:8px 14px;"></td>
			</tr>
		</tbody></table>
	</form>
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

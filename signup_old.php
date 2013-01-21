<?php include 'inc/trois.php';
$account = showAccount();
if(intval($account->id) && !strlen($_GET['key'])){
	//errorMsg("Invalid invitation key.");
}?>
<!DOCTYPE html>
<html>
<?php include('inc/head.php'); ?>
<script type="text/javascript" >
	function myOnComplete(){};
	$(document).ready(function() {
		$("#signup").RSV({
			onCompleteHandler: myOnComplete,
			displayType: "display-html",
			errorFieldClass: "text-error",
				rules: [
					"required,fname,Enter your First Name.",
					"required,lname,Enter your Last Name.",
					"required,email1,Enter your Email Address.",
					"valid_email,email1,Enter a valid email address.",
					"required,captcha_code,Enter the secret words.",
					"required,co_name,Enter a company name.",
					"required,pass1,Enter a password.",
					"required,pass2,Confirm your password.",
					"same_as,pass1,pass2,Ensure the passwords you enter are the same.",
					"custom_alpha,valid_pass,X,Password must be validated."
				]
			});
		<?php if(isset($_GET['msg'])){?>
			$.fancybox('<h1><?= $_GET['title'] ?></h1><p><?= $_GET['msg'] ?></p>');
		<?php } ?>
		<?php if(isset($_GET['captcha'])){?>
			$.fancybox('<h1>Error</h1><p>You did not enter the secret words correctly! Please, try again.</p>');
		<?php } ?>
	});
	function checkPassword(){
		pass = $('#pass1').val();
			$.post('check-password.php',{'pass':pass},function(data){$('#errors').html(data); 
				if(data == 'Password OK'){ 
					$('#valid_pass').val('1');
				}else{
					$('#valid_pass').val('0');
				}
			});
	}
	
</script>
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
<div id="content" style="height:auto;">
    <div id="full_width" style="border:none;">
    <div class="inner" style="font-size:15px; line-height:24px; color:#333; padding:10px 0; margin:0px auto; border:none; ">
        <h1>Keep Updated</h1>
        
        <br clear="all" />
        Thanks for checking out RainLeads.com. We are in the process of revamping our software as we speak! We plan to make the service available to you in December 2012. If you'd like to receive an update when we officially launch, or if you'd like to speak with someone about enterprise solutions, please fill out the form below and you'll be the first to know!
        <br/><br/>
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
				<td colspan="2" align="center"><script type="text/javascript"
   src="https://www.google.com/recaptcha/api/challenge?k=6LcBDrsSAAAAAH-_skXyVnfNV1lan1UzqL9zPP9a">
</script>

<noscript>
   <iframe src="https://www.google.com/recaptcha/api/noscript?k=6LcBDrsSAAAAAH-_skXyVnfNV1lan1UzqL9zPP9a"
       height="300" width="500" frameborder="0"></iframe><br>
   <textarea name="recaptcha_challenge_field" rows="3" cols="40">
   </textarea>
   <input type="hidden" name="recaptcha_response_field"
       value="manual_challenge">
</noscript><br><input type="submit" value="Send Message" class="button green_button" style="font-size:13px; padding:8px 14px;"></td>
			</tr>
		</tbody></table>
	</form>
    </div>
    
    <div class="clear"></div>
    </div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

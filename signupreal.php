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
					"required,subdomain,Enter a subdomain",
					"required,terms,You must agree to the Terms & Conditions",
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
	function checkSubdomain(){
		title = $('#subdomain').val();
		$.post('check-subdomain.php',{'title':title},function(data){$('#domain_err').html(data); });
	}
		
	
</script>
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" class="inner">
    <div id="full_width">
        <h1>REGISTER FOR AN ACCOUNT</h1>
        <p style="margin-top:5px;display:none;color:#FF0000;" id="passmsg"><small>Password must be 8 characters, contain one upper case letter and at least one number.</small></p>
        <form action="<?=str_replace("http://","https://",$HOME_URL);?>register.php" method="post" id="signup">
            <input type="hidden" id="valid_pass" name="valid_pass" value="0" />
            <?php if(strlen($_GET['key'])){?>
            	<input type="hidden" name="key" value="<?=$_GET['key']?>" />
			<?php } ?>
				
            <table style="width:100%; margin-top:20px;">
                <tr>
                    <td><label>First Name</label></td>
                    <td><input type="text" name="fname" value="<?=$_GET['fname']?>" style="width:280px;" /></td>
                    <td><label>Last Name</label></td>
                    <td><input type="text" name="lname" value="<?=$_GET['lname']?>" style="width:280px;" /></td>
                </tr>
                <tr>
                    <td><label>Email</label></td>
                    <td><input type="text" name="email1" value="<?=$_GET['email1']?>" style="width:280px;" /></td>
                    <td><label>Company Name</label></td>
                    <td><input type="text" name="co_name" <?php if(intval($account->id)){?> disabled="disabled"<?php }else{ ?> <?php } ?> value="<?=$_GET['co_name']?><?=$account->title?>" style="width:280px;" /></td>
                </tr>
                <tr>
                    <td><label>Password</label></td>
                    <td><input type="password" name="pass1" id="pass1" style="width:280px;" onKeyUp="checkPassword();" onFocus="$('#passmsg').fadeIn('fast');" onBlur="$('#passmsg').fadeOut('fast');"/><br /><div id="errors" style="color:#FF0000; font-size:11px"></div></td>
                    <td><label>Verify Password</label></td>
                    <td><input type="password" name="pass2" style="width:280px;"  /></td>
                </tr>
                <tr>
                    <td>Subdomain</td>
                    <td><input type="text"  style="width:280px;" name="subdomain" id="subdomain" onKeyUp="checkSubdomain();" value="<?=$_GET['subdomain']?><?=$account->subdomain?>" <?php if(intval($account->id)){?>disabled="disabled"<?php }?> />
                    <div id="domain_err" style="color:#F00;font-size:12px;"></div></td>
                    <td colspan="2" ><h3>Help Stop Spam!</h3><br /><?php include 'inc/custCap.php' ?></td>
                </tr>
                <tr>
                    <td colspan="4">
						<div class="right" style="margin-left:15px;"><input class="button" type="submit" value="Submit" /></div>
						<div class="right" style="font-size:12px;color:#333;margin-top:5px;"><label><input type="checkbox" value="1" name="terms" /> I agree to the site's <a href="<?=$HOME_URL?>terms.php" target="_blank">Terms & Conditions</a></label></div>
						<div class="clear"></div>
					</td>
                </tr>
            </table>
        </form> 
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

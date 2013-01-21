<?php include 'inc/trois.php';
if(verCookie()){
	$user = new User(verCookie());
	$user->logout();
	header("Location: signup.php?key={$_GET['key']}");
	die();
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
				if(data == 'OK'){ 
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
	
	function showHelp(){
		$.fancybox("<h2>What's A Domain</h2><div id='interior' style='width:300px;'>Your domain will be URL that you access your RainLeads account from. e.g. http://business.rainleads.com</div>");
	}
	$(document).ready(function(){
		$("select[name='plan']").onchange();
	});	
	
</script>
<style>
	#signup_ribbon{
		background: #ECF6FC;
		padding: 20px;
		height: auto;
		margin-top:15px;
		border:1px dashed #A0B5C0;
		color:#327192;
		font-size: 14px;
		line-height: 17px;
		float: left;
		position: relative;
		text-shadow:#ffffff 0 1px 0;
		text-align: center;
		outline: 5px solid #D2DDE4;
		
	}
	#signup_ribbon a {
		color:#327192;
	}
	#showcase .tagline {
		color:#e5f0f6;
		margin-left: 16px;
		font-size: 16px;
		letter-spacing: .06em;
	}
	label {
		display: block;
		text-align: left;
		font-weight: bold;
		
	}
	table tr td input[type=text], table tr td input[type=password]{
		width:240px;
		margin-bottom:5px;
		border: 1px solid #327192;
		padding: 5px 3px;
		height: 18px;
		color:#327192;
		outline: 1px solid #fff !Important;
	}
	#showcase {
		height: auto;
		padding-bottom:20px;
	}
	.step2{
		display: none;
	}
	#rsvErrors {
	  display: none;
	  background-color: #ffffcc;
	  border: 1px solid red;
	  padding: 5px;
	  text-align: left;
	  font-size:12px;
	}
</style>
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" class="inner" style="">
	
	<br/>
	<div class="clear"></div>
    <div id="signup_ribbon" style="width:500px;">    
    	<center><h1 style="float:none; color:#396787">Create an Account Today!</h1></center>    
        
        <div id="rsvErrors"></div>
        <form action="<?=str_replace("http://","https://",$HOME_URL);?>register.php" method="post" id="signup">
            <input type="hidden" id="valid_pass" name="valid_pass" value="0" />
            <input type="hidden" name="plan" value="<?=$_GET['plan']?>" />
            <?php if(strlen($_GET['key'])){
            	$con = conDB();
				$r = mysql_query("SELECT * from invites where lcase(invite_key)=lcase('".mysql_escape_string($_GET['key'])."')",$con);
				$i=mysql_fetch_array($r);
				$account = new Account($i['account_id']);
            	?>
            	<input type="hidden" name="key" value="<?=$i['invite_key'];?>" />
            	<input type="hidden" name="email1" value="<?=$i['email'];?>" />
			<?php } ?>
			<br/>
            <table cellpadding="5">
                <tr>
                    <td><label>First Name</label></td>
                    <td><input type="text" name="fname" value="<?=$_GET['fname']?>" style="width:360px;" /></td>
                </tr>
                <tr>
                    <td><label>Last Name</label></td>
                    <td><input type="text" name="lname" value="<?=$_GET['lname']?>" style="width:360px;" /></td>
                </tr>
                <tr>
                    <td><label>Email</label></td>
                    <td>
                    	<?php if(!intval($i['id'])){?>
                    		<input type="text" name="email1" value="<?=$_GET['email1']?>" style="width:360px;" />
                    	<?php }else{?>
                    		<input type='text' value="<?=$i['email'];?>" style='width:360px;' disabled="disabled" />
                    	<?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Company Name</label></td>
                    <td><input type="text" name="co_name" <?php if(intval($account->id)){?> disabled="disabled"<?php }else{ ?> <?php } ?> value="<?=$_GET['co_name']?><?=$account->title?>" style="width:360px;" /></td>
                </tr>
                <tr>
                    <td><label>Password</label></td>
                    <td><input type="password" name="pass1" id="pass1" style="width:360px;" onKeyUp="checkPassword();" onFocus="$('#passmsg').fadeIn('fast');" onBlur="$('#passmsg').fadeOut('fast');"/><br /><div id="errors" style="color:#FF0000; font-size:11px"></div></td>
                </tr>
                <tr>
                    <td><label>Verify Password</label></td>
                    <td><input type="password" name="pass2" style="width:360px;"  /></td>
                </tr>
                <tr>
                    <td><label class="left">Domain</label><a href="javascript:void(0);" onclick="showHelp();"><img src="img/help.png" class="left" style="margin-left:5px;height:16px;" title="What's a Domain" /></a></td>
                    <td>http:// <input type="text"  style="width:210px;" name="subdomain" id="subdomain"autocomplete="off" onKeyUp="checkSubdomain();" value="<?=$_GET['subdomain']?><?=$account->subdomain?>" <?php if(intval($account->id)){?>disabled="disabled"<?php }?> /> .rainleads.com
                    	<div id="domain_err" style="color:#F00;font-size:12px;"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" ><div style="position:relative; left:15px; margin-top:10px;"><?php include 'inc/custCap.php' ?></div></td>
                </tr>
                <!
                <tr>
                    <td colspan="4" align="center">
						
						<div class=""><label style="font-size:12px;color:#333;margin-top:5px; text-align:center;"><input type="checkbox" value="1" name="terms" /> I agree to the site's <a href="<?=$HOME_URL?>terms.php" target="_blank">Terms & Conditions</a></label></div>
						<div class="clear"></div>
						<div class="" style="margin-top:10px;"><input class="button blue_button" type="submit" value="Submit Registration" /></div>
					</td>
                </tr>
                
            </table>
            <style>
            	object{
            		left:-27px !important;
            		top:5px !Important;
            	}
            </style>
            <br/>
            
            <div class="clear"></div>
        </form> 
    </div>
    
    <div class="left" style="margin:30px 0px 0 25px; width:280px;">
    	<h1 style="color:#396787;">30 Day Free Trial!</h1>
    	<div class="clear"></div>
    	<p style="line-height:18px; color:#555;">Feel free to try out our software for Free for 30 Full Days! You can upgrade at any time to take full advantage of our full feature set.</p>
    	<br/>
    	<div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="f86f50cf-83f8-48c5-9cbb-839d0b4a008b";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Merchant Services</a><br/><br/>This website is protected by 256-bit SSL security. </div>
    	<br/>
    	<div class="box">
    		<h3>Feature Preview</h3>
    		<div class="feature">
    			<img src="img/tick.png" align="left" width="16" />
    			<div>Team & Lead Statistics</div>
    		</div>
    		<div class="feature">
    			<img src="img/tick.png" align="left" width="16" />
    			<div>Email Notifications</div>
    		</div>
    		<div class="feature">
    			<img src="img/tick.png" align="left" width="16" />
    			<div>Lead Assignments</div>
    		</div>
    		<div class="feature">
    			<img src="img/tick.png" align="left" width="16" />
    			<div>Custom Microsite</div>
    		</div>
    		<div class="feature">
    			<img src="img/tick.png" align="left" width="16" />
    			<div>Team Management</div>
    		</div>
    		<div class="feature">
    			<img src="img/tick.png" align="left" width="16" />
    			<div>Custom Lead Statuses</div>
    		</div>
    	</div>
    </div>
    <div class="clear"></div>
    
</div>
<div class="clear"></div>
<br/>
<?php include('inc/footer.php') ?>  
</body>
</html>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include_once 'inc/trois.php'; 
$account = showAccount();?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('inc/head.php'); ?>
<script>
	$(function() {
	 $('#center-box').css({'top':$(document).height()/2-240+'px'});
	});
</script>
<style>
	.message{
		background:#EFDC93;
		color:#7F744E;
		
		margin:0 5px;
		padding: 15px;
		text-align: center;
		border-radius: 5px;
		margin-bottom: 15px;
	}
	.big-input {
font-size: 16px;
padding: 6px !important;
min-width: 305px;
margin: 2px 0px;
border-radius: 4px !Important;
border: 1px solid #C8C8C8;
}
</style>	
</head>

<body class="dark">
	<center style="color:#B6CEDB;"><br/><a href="http://<?=$account->subdomain?>.rainleads.com" style="font-size:12px;color:#ffffff;text-decoration:none; border:1px solid #002031; padding:5px 10px; font-weight:bold; background:#F1F1F1; color:#333; border-radius:4px;" title="RainLeads">RainLeads</a> &larr; Drag this to your bookmarks bar.</center>
	<div id="center-box">  
    	<div class="left">
        	<h2><?=$account->title?></h2>
	        <em>Please login to your account.<br />
            <a href="javascript:void(0)" onclick="$.post('<?=$HOME_URL?>reset-password.php',function(data){$.fancybox(data);});" style="font-size:10px;color:#ffffff;text-decoration:none;">Forgot password?</a></em>
    	</div>
        <div class="right" style="font-size:10px; padding-top:20px; opacity:.5;">
        	<img src="img/lock.png" style="position:relative; top:2px;" /> This website is protected by 256-bit SSL security.
        </div>
        <div class="clear"></div>        
        <div class="white">
        	<?php if($_GET['msg']){?>
        		<div class="message">
        			<?= $_GET['msg'] ?>
        		</div>
        	<?php } ?>	
            <div class="left" style="margin-right:20px;"><?=$account->thumbHTML(175,0);?></div>
            <form method='post' action='login.php' class="left">
                <table width="100%" cellpadding="5">
                    <tr>
                    	<td valign="top" class="strong">Email Address:<br/><input type="text" name='email' class="big-input" /></td>
                    </tr>                     
                    <tr>
                        <td class="strong"><br/>Password:<br/><input type="password" name='password' class="big-input" /></td>
                    </tr>
                    <tr>
                    	<td align="center"><br/><input type="submit" value="Login" class="big-input"/></td>
                    </tr>
                </table>
            </form>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>

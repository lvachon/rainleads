<?php if (strlen($_GET['msg'])) { 
	echo '<h3 class="error">'.$_GET['msg'].'</h3>'; 
} else { 
	echo '<h2>'.tl('Sign_In').'</h2>'; 
} ?>
<hr/>
<form method="post" action="<?= str_replace("http://","https://",$HOME_URL);?>login.php">
	<?php if(strlen($_GET['referer'])){?>
        <input type="hidden" name="referer" value="<?=urldecode($_GET['referer']);?>" />
    <?php }?>
    <table width="400" cellpadding="3" align="center">
        <tr>
            <td colspan="2"><strong>Email Address:</strong><br/><input type="text" name="email" tabindex="1" style="width:250px" /></td>
            <td><br /><a href="<?=$HOME_URL?>signup.php">Signup Today!</a></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Password:</strong><br/><input type="password" name="password" tabindex="2" style="width:250px" /></td>
            <td><br /><a href="javascript:void(0);" onclick="<?php if (strlen($_GET['msg'])) { ?>$.post('<?=$HOME_URL?>reset-password.php',function(data){$.fancybox(data);});<?php } else { echo '?pass'; } ?>">Forgot Password?</a></td>
        </tr>       
            <td colspan="3"><input type="submit" value="Sign In" class="right" /><div class="clear"></div></td>
        </tr>
    </table>
</form>

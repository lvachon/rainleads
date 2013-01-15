<?php include '../inc/trois.php';
loginRequired();
if(strlen($_POST['pass1']) && strlen($_POST['pass2'])){
	if($_POST['pass1']==$_POST['pass2']){
		$oldpass = md5("Mountain Dew has 100mg of sodium".$_POST['oldpass']);
		$newpass = md5("Mountain Dew has 100mg of sodium".$_POST['pass1']);
		$con = conDB();
		$r = mysql_query("UPDATE users SET password='$newpass' WHERE id=".verCookie()." and password='$oldpass' LIMIT 1",$con);
		if(mysql_affected_rows($con)>0){
			$qry = "success";
			$viewer->data['fresh_password']="0";
		}else{
			$qry = "fail";
		}
	}else{
		$qry="fail";	
	}
	header("Location: password.php?$qry");
	die();
}
?>
<html>
<head>
	<script>
	<?php if(isset($_GET['success'])){?>parent.$.fancybox("Your password has been changed");<?php } ?>
	<?php if(isset($_GET['fail'])){?>parent.$.fancybox("Your password has <b>not</b> been changed");<?php } ?>
	</script>
</head>
<body>
    <h2>Change Password</h2>
    <hr />
    <div style="width: 320px;">
		<form method='post' action='password.php' target='hidn'>
            <table cellpadding="5" width="320">
                <tr><td>Old Password:</td><td width="180"><input type='password' name='oldpass' style="width: 170px;" /></td></tr>
                <tr><td>New Password:</td><td><input type='password' name='pass1' style="width: 170px;" /></td></tr>
                <tr><td>Confirm Password:</td><td><input type='password' name='pass2' style="width: 170px;" /></td></tr>
                <tr><td>&nbsp;</td><td align="right"><input type='submit' value='Change' /></td></tr>
            </table>
            <iframe width='0' height='0' frameborder='0' name='hidn' id='hidn'></iframe>
		</form>
	</div>
</body>
</html>

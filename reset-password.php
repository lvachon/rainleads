<?php
include 'inc/trois.php';
if($_POST['email']) {
	$con = conDB();
	$email = mysql_escape_string(strtolower($_POST['email']));
	$chkEmail = mysql_query("SELECT id FROM users WHERE lcase(email) = '$email' LIMIT 1",$con);
	if(intval(mysql_num_rows($chkEmail))){
		$newpass = substr(md5(time()+rand(0,65535)),rand(0,16),8);
		mysql_query("UPDATE users set password=md5('Mountain Dew has 100mg of sodium$newpass'), secret='' where lcase(email)='".mysql_escape_string(strtolower($_POST['email']))."' LIMIT 1",$con);
		$r = mysql_query("SELECT id from users where lcase(email)='".mysql_escape_string(strtolower($_POST['email']))."' LIMIT 1",$con);
		$x = mysql_fetch_array($r);
		$user = new User($x['id']);
		$user->data['fresh_password']="1";
		$user->save();
		//need to send the email with the new password.
		$mailVariables = array('%homeurl','%url','%sitename','%sender','%receiver','%post','%type');
		$mailValues = array($HOME_URL,$HOME_URL."account",$SITE_NAME,$user->name(),$user->name(),$newpass,"");
		$title = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_password'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_password')));
		htmlEmail($user->email,$title,$message);			
		header("Location: {$HOME_URL}");
		die();
	}else{
		errorMsg("The email address you have provided does not have an account.");
	}	
}
?>
<html>
<body>
	<h3>Forgot Password</h3>
	<hr/>
	<div id="interior" style="width:350px;">
    Please enter your email address below and we will send you a new password to login. 
	<br/><br/>
	<form method="post" action="reset-password.php">
	   <table cellpadding="5" cellspacing="15" align="center" width="100%">
	       <tr valign="bottom">
	           <td>
                    <small>Please Enter Your Email</small><br />
                    <input type="text" name="email" style="width:200px;" />
	           </td>
	           <td><input type="submit" value="Reset Password" /></td>
	       </tr>
	   </table>
	</form>
    </div>
</body>
</html>
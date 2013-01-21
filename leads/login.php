<?php
include_once 'inc/trois.php';
$fail=false;
if(isset($_GET['out'])){
	$user = new User(verCookie());
	$user->logout();
	header("Location: {$HOME_URL}");
	die();
}
if(isset($_POST['email']) && isset($_POST['password'])){
	$con = conDB();
	$email = mysql_escape_string(strtolower($_POST['email']));
	$password = mysql_escape_string($_POST['password']);
	$r = mysql_query("SELECT id from users where lcase(email)='$email' and password=md5('Mountain Dew has 100mg of sodium{$password}') LIMIT 1",$con);
	$u = mysql_fetch_array($r);
	if(intval($u['id'])){
		$user = new User($u['id']);
		$account = showAccount();
		//die(var_dump($account));
		if(!in_array($user->id,$account->members)){
			$getAccount = mysql_query("SELECT account_id FROM membership WHERE user_id = {$user->id}",$con);
			$myaccount = mysql_fetch_array($getAccount);
			$account = new Account($myaccount['account_id']);
			header("Location: {$HOME_URL}index.php?msg=".urlencode("This profile is not associated with this account. Please try logging in <a href='http://{$account->subdomain}.{$DOMAIN}'>here</a>."));die();
		}
		if($user->login()){
			if(strlen($_POST['referer'])){
				$referer = $_POST['referer'];
				if(substr($_POST['referer'],0,1) == "/"){
					$referer = substr($_POST['referer'],1);
				}
				// go to the page you were trying to before you had to login
				header("Location: {$HOME_URL}{$referer}");die();
			}else{
				// go to default page after login
				header("Location: {$HOME_URL}account/dashboard.php");die();
			}
		}
	}else{
		header("Location: {$HOME_URL}index.php?msg=".urlencode("Incorrect Login, try again"));
		die();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<script>
		<?php if(isset($_GET['reset'])){?>$(document).ready(function(){$.fancybox("Your password has been reset.");});<?php } ?>
		<?php if($fail){?>$(document).ready(function(){$.fancybox("Email/Password combination not found.");});<?php } ?>
    </script>
</head>
<body>
	<?php include 'inc/login-form.php'; ?>
</body>
</html>

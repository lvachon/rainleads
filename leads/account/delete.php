<?php ob_start(); require_once('../inc/trois.php');
loginRequired();
accountRequired();
$account = new Account(verAccount());?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div style="width: 350px; padding: 4px;">
        <h3>Delete Account</h3>
        <hr />
        Please confirm that you would like to delete this account.<br /><br />
        Once completed this cannot be undone!<br /><br />
        <a href="killUser.php" class="button right">Delete</a>
        <div class="clear"></div>
	</div>
</body>
</html>

<?php ob_start(); require_once('../inc/trois.php');
loginRequired();
accountRequired();
$mem = new User(intval($_POST['id']));
$account = $viewer->getAccount();
if(!in_array($viewer->id,$account->admins) || !in_array($mem->id,$account->members)){
	errorMsg("You don't have permission to remove that user.");
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div style="width: 350px; padding: 4px;">
        <h2>Delete User</h2>
        Please confirm that you would like to delete this user's account:<br /><br />
        <div class="left" style="width:38px;"><?=$mem->avatar(32,32,false)?></div>
        <div class="left"><?=$mem->name()?><br/><small class="grey capital"><?=$mem->email?></small></div>
        <br /><br /><br />       
        Once completed this cannot be undone!<br /><br />
        
        <a href="kill-user.php?id=<?=$mem->id?>" class="button right">Delete</a>
        <div class="clear"></div>
	</div>
</body>
</html>

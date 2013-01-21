<?php include 'inc/trois.php';
$account = showAccount();
if(intval($account->id) && !strlen($_GET['key'])){
	//errorMsg("Invalid invitation key.");
}?>
<!DOCTYPE html>
<html>
<?php include('inc/head.php'); ?>
<style>
	h1{
		display:block;
		float:none;	
	}
	#full_width{
		font-size:14px;
		line-height:20px;	
	}
</style>
<body>
<p>&nbsp;</p>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:auto" class="inner">
    <div id="full_width">
        <h1><?=cmsTitle("RTFPrivacy"); ?></h1>
        <br />
        <?=nl2br(cmsRead("RTFPrivacy")); ?>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

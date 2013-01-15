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
	
</style>
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:auto" class="inner">
    <div id="full_width" >
    <div class="inner" style="">
        <h1><?=cmsTitle("RTFabout"); ?></h1>
        <br clear="all" />
        <div class="tour_feature_image left" style="margin:0px 5px 5px 0px;border:1px solid #CCC;"><img src="img/fuchsia.png" /></div>
        <p style=" padding:0 15px; line-height:25px; color:#333;"><?=nl2br(cmsRead("RTFabout")); ?></p>
        <div class="clear"></div>
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

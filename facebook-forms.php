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
<title>Create Facebook Contact Forms - Facebook Business Solutions</title>
<meta content="Create a Contact Form & Add it to your Facebook Business Page in Just 3 Minutes! Manage your Facebook Leads with an Easy to Use Web-Based Software" name="description" />
<meta  name="keywords" content="facebook form, facebook forms, facebook contact form, facebook web form, facebook business, facebook leads" />
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:auto" class="inner">
    <div id="full_width" >
    <div class="inner" style="">
        <h1><?=cmsTitle("RTFfacebook_forms"); ?></h1>
        <br clear="all" />
       
        <p style=" padding:0 15px; line-height:25px; color:#333;"><?=nl2br(cmsRead("RTFfacebook_forms")); ?></p>
        <div class="clear"></div>
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>
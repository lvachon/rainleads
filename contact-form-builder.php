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
<title>Create Website Contact Forms - Business Solutions</title>
<meta name="description"  content="Create a Contact Form & Add it to your Website in Minutes! Manage your Website Leads with an Easy to Use Web-Based Software" />
<meta name="keywords" content="website form, website forms, website contact form, website form, website business, website leads" />
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:auto" class="inner">
    <div id="full_width" >
    <div class="inner" style="">
        <h1><?=cmsTitle("RTFcontact_form"); ?></h1>
        <br clear="all" />
       
        <p style=" padding:0 15px; line-height:25px; color:#333;"><?=nl2br(cmsRead("RTFcontact_form")); ?></p>
        <div class="clear"></div>
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

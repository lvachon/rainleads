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
<style>
	#full_width .inner {
		width:780px;
		margin-top:12px;
	}
	strong.blue {
		font-weight: bold;
		color:#0C7EB9;
	}
	#content input[type=text], #content textarea{
		padding:10px;
		border:1px solid #c8c8c8;
	}
	table tr td {
		padding: 5px;
		font-size: 13px;
		font-weight: bold;
		color:#666;
	}
	h1{
		font-size:24px;
		color:#333;
		display:block;	
	}
</style>
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:auto" class="inner">
    <div id="full_width" >
    <div class="inner" style="font-size:16px; line-height:20px;">
        <h1><?=cmsTitle("RTFenterprise"); ?></h1>
        <br />
        <p style=" padding:0 15px; line-height:25px; color:#333;"><?=nl2br(cmsRead("RTFenterprise")); ?></p>
        <div class="clear"></div>
        <br/>
        
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

<?php include 'inc/trois.php';
$account = showAccount();
if(intval($account->id) && !strlen($_GET['key'])){
	//errorMsg("Invalid invitation key.");
}?>
<!DOCTYPE html>
<html>
<?php include('inc/head.php'); ?>
<body>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<div id="content" style="height:800px; border:none;">
    <div id="full_width" style="border:none;">
    <div class="inner" style="font-size:16px; line-height:20px; padding:15px;">
    <br/>
       <center> <h1>Thank You</h1><br/>
Your information has been sent and we will be in touch with you soon.
</center>
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<?php include('inc/footer.php') ?>  
</body>
</html>

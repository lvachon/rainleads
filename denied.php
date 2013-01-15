<!DOCTYPE html>
<?php include('inc/trois.php');?>
<html>
<?php include('inc/head.php'); ?>
<body>
<?php include('/inc/header.php'); ?>
<?php if(!verCookie()){ include('inc/nav.php'); }?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php if(verCookie()){ include('inc/sidenav.php'); } ?>
    </div>
    <div id="main" class="left">
    	<br />
    	<h2>Login Failed</h2>
       <?php include 'inc/login-form.php';?>
    </div>
    <div class="clear"></div>
</div>
<?php if(!verCookie()){ include 'inc/footer.php'; } ?>
</body>
</html>

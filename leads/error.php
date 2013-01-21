<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include 'inc/trois.php';
$msg = $_GET['msg'];?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('inc/head.php'); ?>

</head>

<body>
    <?php include('inc/header.php') ?>
   
    
    <div id="content" >
    	<div style="background:#f8f8f8; width:800px; margin:50px auto; height:auto; padding:10px 0 20px 0; border:1px solid #d0d0d0; outline:1px solid #fff;">
	    	<center>
	    	<h1 style="float:none;">Oops! There was an Error</h1>
	    	<br clear="all"/>
	        <?=$msg?>
	        <a href="javascript:void(0);" onclick="window.history.back();">Go Back</a>
	        </center>
        </div>
    </div>
    <?php include('inc/footer.php') ?>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include 'inc/trois.php';?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('inc/head.php'); ?>
<?php 
	if($_GET['title']){
		$title = $_GET['title'];
	}else{
		$title = 'RainLeads Features';
	}
?>
<style>
	#inner_side_nav {
		width:200px;		
		border-top:1px solid #c0c0c0;
		float: left;
	}
		#inner_side_nav .link {
			padding:15px;
			border: 1px solid #c0c0c0;
			font-weight: bold;
			color:#666;
			border-top:none;
			background: #f0f0f0;
			position: relative;
			z-index: 6;
		}
		#inner_side_nav .link:hover {
			cursor: pointer;
		}
		#inner_side_nav .link.active {
			border-right:1px solid #fff;
			background: #fff;
		}
		#inner_side_content{
			width:725px;
			position: relative;
			left:-1px;
			border:1px solid #c0c0c0;
			min-height: 500px;
			z-index: 3;
			padding: 15px;
		}
		.grey {
			padding: 15px;
			background: #f0f0f0;
			border-radius: 5px;
		}
		.right-box {
			text-align: right;
			margin: 45px 0;
		}
		.left-box {
			text-align: left;
			margin: 45px 0;
		}
		p {
			line-height: 18px;
		}
</style>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/nav.php') ?>
    
    <div id="content">
    	<div class="inner">
    		<br/>
    		<h1><?= $title ?></h1>
    		<div class="clear"></div>
    		<br/>
        	<div id="inner_side_nav" class="left">
        		<div class="link" onclick="document.location.href='/lead-management.php'">Lead Management</div>
        		<div class="link" onclick="document.location.href='/customForms.php'">Contact Forms</div>
        		<div class="link" onclick="document.location.href='/facebook-forms.php'">Facebook Forms</div>
        		<div class="link active" onclick="document.location.href='/contact-form-builder.php'">Contact Form Builder</div>
        		<div class="link" onclick="document.location.href='/virtual-business-cards.php'">Virtual Business Card</div>
        	</div>     
        	<div id="inner_side_content" class="left">
        		<div class="">
	        		<div class="left"><img src="/img/cfb_icon.png" align="left" style="margin-right:15px; padding:5px;" /></div>
	        		<h1 style="float:none; position:relative; top:8px;">Custom Form Builder</h1>
	        		<div class="clear"></div>
	        		
	        		<p style="">With RainLeads you don’t need to be a developer to create your own contact form. Our easy-to-use custom form builder allows you to customize every aspect of your form, from advanced form fields to colors and styles.</p><br/><p>The first step in the custom form builder allows you to edit and add to our simple default contact form, which includes your lead’s name, email address and comments. Add drop downs, date and time selectors, and multi-select boxes, and then label the fields accordingly. Fields can be required or optional.</p><br/><p>The next step is to style the fonts, sizes and colors of your form to match your existing brand or website. Once the form is styled, you can generate your embed code which is a short line of code that you can add to your website’s HTML, WordPress page, blog widgets or simply email the code directly to your web developer to add to your website. </p>
	        		<div class="clear"></div>
        		</div>
        		<div class="clear"></div>
        	<br/><br/>
	        		<img src="/img/cfb_ss_2.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
	        		<img src="/img/cfb_ss_1.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
        	</div>   
        	<div class="clear"></div>
        </div>
		<div class="clear"></div>
    </div>
    <div class="clear"></div>
    <br/>
    <?php include('inc/footer.php') ?>
</body>
</html>

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
        		<div class="link active" onclick="document.location.href='/facebook-forms.php'">Facebook Forms</div>
        		<div class="link" onclick="document.location.href='/contact-form-builder.php'">Contact Form Builder</div>
        		<div class="link" onclick="document.location.href='/virtual-business-cards.php'">Virtual Business Card</div>
        	</div>     
        	<div id="inner_side_content" class="left">
        		<div class="">
	        		<div class="left"><img src="/img/ff_icon.png" align="left" style="margin-right:15px; padding:5px;" /></div>
	        		<h1 style="float:none; position:relative; top:8px;">Integrated Facebook Forms</h1>
	        		<div class="clear"></div>
	        		
	        		<p style="">Are you interested in leveraging your social media channels? RainLeads lead management software offers you the tools to seamlessly integrate your contact forms right into your business’s Facebook page, giving you the ability to collect information from leads right into your RainLeads dashboard.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="left-box">
        			
	        		<h1 style="float:none;">One Click Integration</h1>
	        		
	        		
	        		<p>The first step to adding your customized contact form to your company's Facebook page is to authenticate your account. This allows us to identify which Facebook pages are associated with your account. Then simply choose the form you'd like to integrate and choose the Facebook page you'd like to add it to.<br/><br/>Voila! You can now receive inquiries from this key social media channel.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="left-box">
        			
	        		<h1 style="float:none;">Manage Multiple Pages</h1>
	        		
	        		
	        		<p>Have multiple Facebook pages? Create multiple forms to cater to each page, or use the same one on each. Save time by duplicating existing forms and editing new copies. Our RainLeads tracking technology collects source information with each lead you receive, so you can easily see where your leads are coming from and track each page’s performance and conversion.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="clear"></div>
        		<br/>
	        		<img src="/img/ff_ss_2.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
	        		<img src="/img/ff_ss_1.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
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

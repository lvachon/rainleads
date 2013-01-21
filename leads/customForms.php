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
        		<div class="link active" onclick="document.location.href='/customForms.php'">Contact Forms</div>
        		<div class="link" onclick="document.location.href='/facebook-forms.php'">Facebook Forms</div>
        		<div class="link" onclick="document.location.href='/contact-form-builder.php'">Contact Form Builder</div>
        		<div class="link" onclick="document.location.href='/virtual-business-cards.php'">Virtual Business Card</div>
        	</div>     
        	<div id="inner_side_content" class="left">
        		<div class="">
	        		<div class="left"><img src="/img/icf_icon.png" align="left" style="margin-right:15px; padding:5px; " /></div>
	        		<h1 style="float:none; position:relative; top:12px;">Integrated Contact Forms</h1>
	        		<div class="clear"></div>
	        		<p style="">Does your business receive online inquiries? If you do, chances are you are currently managing those inquiries right from your email or entering them manually into another CRM solution. RainLeads lead management software gives each user the ability to create customizable contact forms with multiple options for integration into their website, blog, multiple Facebook pages, or their unique RainLeads microsite.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="left-box">
        			
	        		<h1 style="float:none;">No More Data Entry</h1>
	        		
	        		
	        		<p>So what does this mean? It means that you can save hours of manual entry by bringing online lead inquiries directly into your RainLeads account, where you can assign them to sales staff, change their status and manage sales milestones. Once you create your contact form to collect all the critical information you need, here are a few ways you can integrate your form!</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="left-box">
        			
	        		<h1 style="float:none;">Form Creation</h1>
	        		
	        		
	        		<p>Create your form fields and then style the form to match your existing website for a seamless experience. Once your form customization is complete, you'll be able to embed the code into your website, blog or WordPress by copying iFrame or Javascript code right into the HTML. If you don't manage your own website code, simply email it to your web developer with one click instructions. You can also contact us through the Support tab on your account or call us toll-free at 800-985-0058 and request a form installation for a one-time $50 fee.<br/><br/>Once your form is installed, unless you change the size of your form, any style or field changes update automatically without the need to reinstall the code! </p>
	        		<div class="clear"></div>
        		</div>
        		<div class="left-box">
        			
	        		<h1 style="float:none;">Manage Multiple Forms</h1>
	        		
	        		
	        		<p>Have multiple websites? Create multiple forms to cater to each site, or use the same one on each. Save time by duplicating existing forms and editing new copies. Our RainLeads tracking technology collects source information with each lead you receive, so you can easily see where your leads are coming from and track each site’s performance and conversion.</p>
	        		<div class="clear"></div>
	        		<br/><br/>
	        		<img src="/img/cf.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
	        		<img src="/img/cf_ss_1.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
        		</div>
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

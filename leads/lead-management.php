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
        		<div class="link active" onclick="document.location.href='/lead-management.php'">Lead Management</div>
        		<div class="link" onclick="document.location.href='/customForms.php'">Contact Forms</div>
        		<div class="link" onclick="document.location.href='/facebook-forms.php'">Facebook Forms</div>
        		<div class="link" onclick="document.location.href='/contact-form-builder.php'">Contact Form Builder</div>
        		<div class="link " onclick="document.location.href='/virtual-business-cards.php'">Virtual Business Card</div>
        	</div>     
        	<div id="inner_side_content" class="left">
        		<div class="">
	        		<div class="left"><img src="/img/vbc_icon.png" align="left" style="margin-right:15px; padding:5px; position:relative; top:-5px; " /></div>
	        		<h1 style="float:none; position:relative; top:10px;">Lead Management</h1>
	        		<div class="clear"></div>
	        		<table  style="font-size:16px; margin-left:32px; font-weight:bold;" cellpadding="5">
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i1.png" height="16" /></td>
	        				<td>Receive email alerts when you receive new leads</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i2.png" height="16" /></td>
	        				<td>Create user accounts for your sales staff</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i3.png" height="16" /></td>
	        				<td>Oversee staff sales activity and stats</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i4.png" height="16" /></td>
	        				<td>Assign, sort and search your leads</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i5.png" height="16" /></td>
	        				<td>Receive a daily digest of your lead activity</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i6.png" height="16" /></td>
	        				<td>Set customized milestones and lead statuses</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i7.png" height="16" /></td>
	        				<td>Input leads manually or import them through your existing CRM provider</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i8.png" height="16" /></td>
	        				<td>Create customized forms for multiple web platforms</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i9.png" height="16" /></td>
	        				<td>Integrate lead forms into your website, Facebook page or blog</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i10.png" height="16" /></td>
	        				<td>Create a unique RainLeads microsite for your business</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i11.png" height="16" /></td>
	        				<td>Set up calendar events and reminders, and sync with iCal or Google calendars</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i12.png" height="16" /></td>
	        				<td>View quick stats of your lead activity</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i13.png" height="16" /></td>
	        				<td>Manage your contacts database</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i14.png" height="16" /></td>
	        				<td>Manage your pipeline and view impending sales</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i15.png" height="16" /></td>
	        				<td>Exports your leads to your own database or newsletter provider</td>
	        			</tr>
	        			<tr>
	        				<td width="16" valign="middle"><img src="/img/i16.png" height="16" /></td>
	        				<td>Attach proposals and other important documents with your contact</td>
	        			</tr>	        			
	        		</table>
        		</div>
        		<div class="clear"></div>
        	<br/><br/>
	        		<img src="/img/lm_ss_2.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
	        		<img src="/img/lm_ss_1.png" align="left" style="margin:25px; padding:5px; border:1px solid #c0c0c0;" />
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

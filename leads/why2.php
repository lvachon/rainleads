<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include 'inc/trois.php';?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('inc/head.php'); ?>
<?php 
	if($_GET['title']){
		$title = $_GET['title'];
	}else{
		$title = 'Default Title';
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
        	<div id="inner_side_nav" class="left">
        		<div class="link active" onclick="document.location.href='/customForms.php'">Contact Forms</div>
        		<div class="link " onclick="document.location.href='/facebook-forms.php'">Facebook Forms</div>
        		<div class="link" onclick="document.location.href='/contact-form-builder.php'">Contact Form Builder</div>
        		<div class="link" onclick="document.location.href='/virtual-business-cards.php'">Virtual Business Card</div>
        	</div>     
        	<div id="inner_side_content" class="left">
        		<div class="grey">
	        		<img src="img/grey.png" style="margin-right:5px;" width="200" height="200" align="left" />
	        		<h1 style="float:none;">Header</h1>
	        		
	        		
	        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed pellentesque dolor. Nunc non posuere diam. Nunc purus neque, ullamcorper sed rutrum nec, faucibus id urna. Sed non lectus leo, vitae iaculis diam. Nunc eu rhoncus est. Integer sed quam eget orci vestibulum dictum. Suspendisse in mi enim.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="right-box">
        			<img src="img/grey.png" style="margin-left:5px;" width="200" height="200" align="right" />
	        		<h1 style="float:none;">Header</h1>
	        		
	        		
	        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed pellentesque dolor. Nunc non posuere diam. Nunc purus neque, ullamcorper sed rutrum nec, faucibus id urna. Sed non lectus leo, vitae iaculis diam. Nunc eu rhoncus est. Integer sed quam eget orci vestibulum dictum. Suspendisse in mi enim.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="left-box">
        			<img src="img/grey.png" style="margin-right:5px;" width="200" height="200" align="left" />
	        		<h1 style="float:none;">Header</h1>
	        		
	        		
	        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed pellentesque dolor. Nunc non posuere diam. Nunc purus neque, ullamcorper sed rutrum nec, faucibus id urna. Sed non lectus leo, vitae iaculis diam. Nunc eu rhoncus est. Integer sed quam eget orci vestibulum dictum. Suspendisse in mi enim.</p>
	        		<div class="clear"></div>
        		</div>
        		<div class="right-box">
        			<img src="img/grey.png" style="margin-left:5px;" width="200" height="200" align="right" />
	        		<h1 style="float:none;">Header</h1>
	        		
	        		
	        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed pellentesque dolor. Nunc non posuere diam. Nunc purus neque, ullamcorper sed rutrum nec, faucibus id urna. Sed non lectus leo, vitae iaculis diam. Nunc eu rhoncus est. Integer sed quam eget orci vestibulum dictum. Suspendisse in mi enim.</p>
	        		<div class="clear"></div>
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

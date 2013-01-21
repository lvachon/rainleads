<?php include 'inc/trois.php';
$sd = explode(".",$_SERVER['HTTP_HOST']);
if(strlen($sd[0]) && $sd[0]!="www"){
	if(verCookie()){
		header("Location: {$HOME_URL}account/dashboard.php");die();
	}
	include 'simple_login.php';
	die();
}
if(strlen($_COOKIE['subdomain'] && $sd[0]=="www")){
	$sub = str_replace('www.',$_COOKIE['subdomain'].".",$HOME_URL);
	//header("Location: {$sub}index.php");
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('inc/head.php'); ?>
<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>css/styles.css?rand=<?= rand() ?>" />
<style>
	#signup_ribbon{
		background: #ECF6FC;
		padding: 10px;
		height: auto;
		margin-top:15px;
		border:1px dashed #A0B5C0;
		color:#327192;
		font-size: 14px;
		line-height: 17px;
		text-shadow:#ffffff 0 1px 0;
		text-align: center;
		outline: 5px solid #ECF6FC;
		box-shadow:0 0 1px 1px rgba(0,0,0,0.5), rgba(0,0,0,1) 0 0 14px 1px ;
	}
	#signup_ribbon a {
		color:#327192;
	}
	#showcase .tagline {
		color:#e5f0f6;
		margin-left: 16px;
		font-size: 16px;
		letter-spacing: .06em;
	}
	label {
		display: block;
		text-align: left;
	}
	table tr td input[type=text], table tr td input[type=password]{
		width:240px;
		margin-bottom:5px;
		border: 1px solid #327192;
		padding: 5px 3px;
		color:#327192;
		outline: 1px solid #fff !Important;
	}
	#showcase {
		height: auto;
		padding-bottom:20px;
	}
	.step2{
		display: none;
	}
	#rsvErrors {
	  display: none;
	  background-color: #ffffcc;
	  border: 1px solid red;
	  padding: 5px;
	  text-align: left;
	  font-size:12px;
	}
</style>
<script type="text/javascript" >
	function myOnComplete(){
		
	};
	$(document).ready(function() {
		$("#signup").RSV({
			onCompleteHandler: myOnComplete,
			
			displayType: "display-html",
			errorFieldClass: "text-error",
				rules: [
					"required,fname,Enter your First Name.",
					"required,lname,Enter your Last Name.",
					"required,email1,Enter your Email Address.",
					"valid_email,email1,Enter a valid email address.",
					"required,captcha_code,Enter the secret words.",
					"required,co_name,Enter a company name.",
					"required,pass1,Enter a password.",
					"required,pass2,Confirm your password.",
					"same_as,pass1,pass2,Ensure the passwords you enter are the same.",
					"required,subdomain,Enter a subdomain",
					"required,terms,You must agree to the Terms & Conditions",
					"custom_alpha,valid_pass,X,Password must be validated.",
					"custom_alpha,valid_email,X,Email address must be validated."
				]
			});
		<?php if(isset($_GET['msg'])){?>
			$.fancybox('<h1><?= $_GET['title'] ?></h1><p><?= $_GET['msg'] ?></p>');
		<?php } ?>
		<?php if(isset($_GET['captcha'])){?>
			$.fancybox('<h1>Error</h1><p>You did not enter the secret words correctly! Please, try again.</p>');
		<?php } ?>
	});
	function checkPassword(){
		pass = $('#pass1').val();
			$.post('check-password.php',{'pass':pass},function(data){$('#errors').html(data); 
				if(data == 'OK'){ 
					$('#valid_pass').val('1');
				}else{
					$('#valid_pass').val('0');
				}
				if(data == 'OK'){
					$('#errors').css({'color':'#0CB954'});
				}
			});
	}
	function checkEmail(){
		email = $('#email1').val();
			$.post('check-email.php',{'email':email},function(data){$('#emailerr').html(data); 
				if(data == 'OK'){ 
					$('#valid_email').val('1');
				}else{
					$('#valid_email').val('0');
				}
				if(data == 'OK'){
					$('#emailerr').css({'color':'#0CB954'});
				}
			});
	}
	function checkSubdomain(){
		title = $('#subdomain').val();
		if(title.length >2){
		$.post('check-subdomain.php',{'title':title},function(data){
		
		$('#domain_err').html(data); 
		
		if(data == 'Subdomain available.'){
			$('#domain_err').css({'color':'#0CB954'});
		}else{
			$('#domain_err').css({'color':'#B90C0C'});
		}
		
		});
		}else{
			$('#domain_err').val('');
		}
	}
	
	
	
</script>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/nav.php') ?>
    <div id="showcase">
    	<div class="inner">
				<br/>
				<center>
				<img src="img/tagline.png" />
				<div class="tagline">Turn leads into sales with a simple organized cloud-based software that your sales team will enjoy using!</div>
				<br/>
				<br/>
				<img src="img/showcase2.png" /></center>
				<br/>
				<div class="left" style="width:34%; margin-left:2%; color:#fff;">
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Easy to use <strong>contact form builder</strong>
					</div>
					<br/>
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Streamline by adding forms to you <strong>Website,<br/>Blog & Facebook Page</strong>
					</div>
					<br/>
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Edit forms and see <strong>live updates </strong>
					</div>
				</div>
				<div class="left" style="width:28%; margin-left:1%; color:#fff;">
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Forms submit leads directly into your &nbsp;&nbsp;&nbsp;&nbsp;RainLeads dashboard
					</div>
					<br/>
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; <strong>Manage lead distribution</strong> to staff
					</div>
					<br/>
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Set custom <strong>milestones & statuses </strong>
					</div>
				</div>
				<div class="left" style="width:26%; padding-left:6%; color:#fff;">
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Track form and team <strong>statistics</strong>
					</div>
					<br/>
					<div>
						<img src="img/dot2.png" style="position:relative; top:-1px;">&nbsp; Sync <strong>sales calendars</strong> for followups
					</div>
					<br/>
					<a href="signup.php" class="button green_button right">Try it FREE for 30 Days</a>
				</div>
				<div class="clear"></div>
			
        </div>
    </div>
    <div id="content">
    	<div class="dark-box">
	    	<img src="img/why.png" class="title_img" />
            <br/>
            <div class="left why_box">
            	<div class="icon">
                	<img src="img/nw.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Increase Your Sales</div>
                    Put your product and services in front of a broader audience by providing inquiry forms on your website, social media pages, and a personalized virtual business card. Start reaching more people and connect with more prospective clients instantly.
                </div>
                
            </div>
            <div class="right why_box" style="margin:5px auto;">
            	<div class="icon">
                	<img src="img/vcardicon.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Virtual Business Card Integration</div>
                    RainLeads provides every account holder with a virtual business card and a unique www.coba.se/ URL. The Cobase vCard is a simple one-page website where you can display your company logo, basic business information, social media profiles and an interactive RainLeads contact form.   
                </div>
                
            </div>
            <div class="clear"></div>
            <div class="left why_box">
            	<div class="icon">
                	<img src="img/sw.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Save Time</div>
                    Sometimes all it takes is a great system to create more of that precious commodity: time. Don’t waste another minute fishing through your emails for lead inquiries. Use the time you would typically spend manually entering lead data converting those leads to paying clients instead.
                </div>
                
            </div>
            <div class="right why_box">
            	<div class="icon">
                	<img src="img/trackicon.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Track Lead Milestones & Activity</div>
                    Use RainLeads to tag, categorize, track, delegate or simply view all your leads. Skip the spreadsheets and see at-a-glance statistics on your lead conversion and your staff’s success over time. With intuitive tools, you can easily keep track of all new, active, won and dead leads.  
                </div>
                
            </div>
            <div class="clear"></div>
            
            <div class="left why_box">
            	<div class="icon" style="width:64px; text-algin:center;">
                	<img src="img/websiteicon.png" style="" width="56" />
                </div>
                <div class="caption">
                	<div class="tagline">Website Contact Form Integration</div>
                    Create customizable contact forms with our contact form builder. A snippet of code is simply added to your website and leads will seamlessly flow directly into your lead management dashboard (NO MORE MANUAL ENTRY).
                </div>
                
            </div>
            <div class="right why_box">
            	<div class="icon">
                	<img src="img/facebookicon.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Facebook Contact Form Integration</div>
                    Seamlessly integrate your contact forms right into your business’s Facebook page, giving you the ability to collect information from Facebook leads right into your RainLeads lead dashboard.  
                </div>
                
            </div>
            <div class="clear"></div>
            
            
            <div class="clear"></div>
            
        </div>
        <br/>
        <img class="title_img" src="img/who.png" />
        <div class="inner">
            <div class="left">
                <div class="who_list">Freelancers & Small Business Owners</div>
                <div class="who_list">Web Designers & Developers</div>
                <div class="who_list">Real Estate Professionals</div>
                <div class="who_list">Photographers</div>
                <div class="who_list">Videographers</div>
                <div class="who_list">Business Consultants</div>
                <div class="who_list">Coaches & Public Speakers</div>
                <div class="who_list">Insurance Agents</div>
                <div class="who_list">Accountants</div>
                <div class="who_list">Mortgage Brokers</div>
            </div>
            <div class="right">
            	<div class="who_box">
                    <img src="http://pearsestreet.com/ps4/wp-content/uploads/2011/12/5.png" class="avatar" />
                    <p>“I can’t imagine working with anyone else to keep our sales process organized. As a web development company, being able to collect lead information from our website and have it immediately accessible to the sales team through our RainLeads panel is a huge time saver.”</p>
                    -Susan Dodge, Sr. Project Manager, Pearse Street, Inc.
                    <div class="clear"></div>
                </div>
                <div class="who_box">
                    <img src="http://pearsestreet.com/ps4/wp-content/uploads/2011/12/2.png" class="avatar" />
                    <p>“RainLeads provides a great cloud-based solution for collecting and tracking lead details. I’m exceedingly pleased and would recommend them to anyone who hasn’t found the right CRM solution for their specific business model. It’s definitely helped our bottom line.”</p>
                    -Jon McInerney, Co-Founder, Social Focus Marketing, Inc. 
                    <div class="clear"></div>
                </div>
            </div>	
            
        </div>
        
    </div>
    <div class="clear"></div>	
	<div class="inner" style=" margin-top:80px;">
		<a href="<?=$HOME_URL?>signup.php" class="button green_button" style="font-size:20px; margin-top:-45px; font-weight:normal;">Get Started & Try it Free for 30 Days</a>
	</div>
	<br/><br/>
    <?php include('inc/footer.php') ?>
</body>
</html>

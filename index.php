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
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/nav.php') ?>
    <div id="showcase">
    	<br/>
    	<center><img src="img/tagline.png" /></center>
        <br/>
        <div class="inner">
            <div class="left how_number">
                <div class="">
                    <div class="caption"><span class="hand">#1</span> Create your lead form</div>
					<br/><br/>
                    <img src="img/1.png" />
                </div>            
            </div>
            <div class="left how_number">
                <div class="">
                    <div class="caption"><span class="hand">#2</span> Add our simple code to your website</div>
                    <br/><br/>
                    <img src="img/2.png" />
                </div>            
            </div>
            <div class="left how_number">
                <div class="">
                    <div class="caption"><span class="hand">#3</span> Start managing leads instantly!</div>
                    <br/><br/>
                    <img src="img/3.png" />
                </div>            
            </div>
            <div class="clear"></div>
            <div class="right" style="color:#fff; margin:20px 60px; font-size:16px;">Simplify with cloud-based lead management software for as little as $9 a month. | <a href="<?=$HOME_URL?>signup.php" class="yellow bold">Get Started for Free!</a></div>
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
                	<div class="tagline">Increase Lead Generation</div>
                    Put your product and services in front of a broader audience by providing inquiry forms on your website and social media pages, or your personalized RainLeads microsite. Start reaching more people and connect with more prospective clients instantly.
                </div>
                
            </div>
            <div class="right why_box">
            	<div class="icon">
                	<img src="img/ne.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Simple Website & Social Media Integration</div>
                    Maximize your web presence by following our step-by-step form integration guide. Make life simple by adding a customized form to your website or Facebook Page and begin collecting inquiries into your RainLeads lead panel seamlessly.  
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
                	<img src="img/se.png" />
                </div>
                <div class="caption">
                	<div class="tagline">Track Lead Milestones & Activity</div>
                    Use RainLeads to tag, categorize, track, delegate or simply view all your leads. Skip the spreadsheets and see at-a-glance statistics on your lead conversion and your staff’s success over time. With intuitive tools, you can easily keep track of all new, active, won and dead leads.  
                </div>
                
            </div>
            <div class="clear"></div>
            <a href="<?=$HOME_URL?>signup.php" class="button green_button" style="font-size:20px; margin-top:5px; font-weight:normal;">Get Started & Try it Free for 30 Days</a>
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
    <?php include('inc/footer.php') ?>
</body>
</html>

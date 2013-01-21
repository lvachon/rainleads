<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include 'inc/trois.php';?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('inc/head.php'); ?>
<script src="/tour/jquery.bxslider.min.js"></script>
<script>
	$(document).ready(function(){
	  $('.bxslider').bxSlider({
	  	'adaptiveHeight':true,
	  	'pagerCustom':'#tabbox'
	  });
	});
</script>
<style>
.inner {
	width:1012px;
}
.bx-controls-direction{
	display: none;
}
#sideTour strong {
	font-size:16px;
	color:#555;
	font-weight: normal;
	
}
#content {
	height: auto;
}
#sideTour ul {
	margin: 5px 0;
}
	#sideTour ul li {
		padding:10px 0;
		color:#777;
		font-size:14px;
		display: inline;
		padding: 0px 5px;
	}
	#sideTour ul li.strong {
		display: block;
		margin-bottom: 5px;
	}
#tourImageWrap {
	margin:10px 0;
	padding: 5px;
	border:1px solid #c0c0c0;
	border-radius: 2px;
	background: #f0f0f0;
	
}
#tourContent {
	padding:20px;
	background: #fff;
	border: 1px solid #c0c0c0;
	border-radius: 2px;
	color:#666;
	line-height: 18px;
}
#tourContent h2 {
	color:#626466;
}
.tabbox {
	display: block;
	border-bottom: 1px solid #c0c0c0;
	margin-top: 10px;
}
.tabbox .tab {
	display:inline-table;
	margin: 0px;
	padding: 8px 16px;
	border: 1px solid #c0c0c0;
	border-bottom: none;
	border-radius: 5px 5px 0 0;
	background: #f0f0f0;
	color:#888;
	position: relative;
	top:1px;
	z-index: 5;
	text-shadow: #fff 0 1px 0 0;
}
.tabbox .tab.active {
	background: #fff;
	color:#626466;
	border-bottom: 1px solid #fff;
}
.featureList {
	background: #fff;
	padding: 10px;
	border-radius:2px;
	position: relative;
	top:2px;
	
	font-size: 13px;
	border: 1px solid #c0c0c0;
}
.featureList li {
	color:#333 !important;
}
</style>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/nav.php') ?>    
    <div id="content" class="inner">
    	<br/>
    	<div class="left"><h1>Feature Tour</h1></div>
    	
    	<div class="clear"></div>
    	
	    	<div class="tabbox" id="tabbox">
	    		<a class="tab" data-slide-index="0"><strong>Dashboard</strong></a>
	    		<a class="tab" data-slide-index="1"><strong>Lead Management</strong></a>
	    		<a class="tab" data-slide-index="2"><strong>Pipeline</strong></a>
	    		<a class="tab" data-slide-index="3"><strong>Contact Forms</strong></a>
	    		<a class="tab" data-slide-index="4"><strong>Facebook Forms</strong></a>
	    		<a class="tab" data-slide-index="5"><strong>Calendar</strong></a>
	    		<a class="tab" data-slide-index="6"><strong>Statistics</strong></a>
	    		<a class="tab" data-slide-index="7"><strong>Administration</strong></a>	    		
	    	</div>
	    	<div id="sliderId">
	    	<ul class="bxslider" id="sideTour">    		    		
	    		<li class="active" id="slide_dashboard">   		
		    		<ul class="featureList">    			
		    			<li>View Recent Leads</li>
		    			<li>|</li>
		    			<li>View Recent Activity</li>
		    			<li>|</li>
		    			<li>View Recent Statistics</li>
		    			<li>|</li>
		    			<li>View Team Activity</li>
		    			<li>|</li>
		    			<li>View Upcoming Events</li>
		    		</ul>
	    	
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/tour_dashboard.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		<h2>Lead Activity</h2>
			    		<p>Your RainLeads dashboard gives you an at-a-glance view of what’s happening with your leads and sales staff. View the last 5 leads you’ve received and assign them to your team members. See the latest activity on all your leads, including status changes, milestone completions and new events. Activity within the last 24-hour is highlighted for ease. You can also opt to receive a Daily Digest email including all your team’s activity each day.</p>
			    		<br/>
			    		<h2>Statistics & Events</h2>
			    		<p>View quick stats from the last 30 days, and see who on your team is logging in and when. Upcoming events are listed and link to any leads they may be associated with. From your dashboard you can easily link to view more statistics, leads, or activity!</p>
			    	</div>
			    </li>
			    <li id="slide_lead_management">   		
		    		<ul class="featureList">    			
		    			<li>Search & Sort Leads</li>
		    			<li>|</li>
		    			<li>Complete Milestones</li>
		    			<li>|</li>
		    			<li>Set Status</li>
		    			<li>|</li>
		    			<li>Assign to Team Members</li>
		    			<li>|</li>
		    			<li>Supplement Contact Info</li>
		    			<li>|</li>
		    			<li>View Lead History</li>
		    		</ul>
	    	
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/lead1.jpg" />
			    		</div>
			    	</div>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/lead2.jpg" />
			    		</div>
			    	</div>
			    	
			    	<div id="tourContent">
			    		
		        		<div class="clear"></div>
		        		<table  style="font-size:16px; margin:20px 0; color:#666;" cellpadding="5">
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
			    </li>
			    <li id="slide_pipeline">
			    	<ul class="featureList">
			    		<li>Add Leads to Pipeline</li>
			    		<li>|</li>
			    		<li>Add Files & Proposals</li>
			    		<li>|</li>
			    		<li>Set Proposal Amount & Probability</li>
			    	</ul>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/pipeline3.jpg" />
			    		</div>
			    	</div>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/pipeline.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		<p>Easily add any lead to the pipeline by clicking the star next to your lead’s name. This lists the lead in the pipeline list where you can search, sort and filter your pipeline leads further. Track lead to pipeline conversions with your quick stats or full statistic reports. </p>
			    		<br/>
			    		<p>Quickly add relevant files to your lead, including proposals with proposal name, amount and probability of closing. </p>
			    	</div>
			    </li>
			    <li id="slide_contact_forms">
			    	<ul class="featureList">
			    		<li>Create Multiple Forms</li>
			    		<li>|</li>
			    		<li>Add Forms to Facebook</li>
			    		<li>|</li>
			    		<li>Customize Form Fields & Styles</li>
			    		<li>|</li>
			    		<li>Embed Codes onto Website or Blog</li>
			    	</ul>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/custom-forms1.jpg" />
			    		</div>
			    		
			    	</div>
			    	<div id="tourContent">
			    		<p style="">With RainLeads you don’t need to be a developer to create your own contact form. Our easy-to-use custom form builder allows you to customize every aspect of your form, from advanced form fields to colors and styles.</p><br/><p>The first step in the custom form builder allows you to edit and add to our simple default contact form, which includes your lead’s name, email address and comments. Add drop downs, date and time selectors, and multi-select boxes, and then label the fields accordingly. Fields can be required or optional.</p><br/><p>The next step is to style the fonts, sizes and colors of your form to match your existing brand or website. Once the form is styled, you can generate your embed code which is a short line of code that you can add to your website’s HTML, WordPress page, blog widgets or simply email the code directly to your web developer to add to your website. </p>
			    	</div>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/custom-forms2.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		
			    		<p style="">Does your business receive online inquiries? If you do, chances are you are currently managing those inquiries right from your email or entering them manually into another CRM solution. RainLeads lead management software gives each user the ability to create customizable contact forms with multiple options for integration into their website, blog, multiple Facebook pages, or their unique RainLeads microsite.</p>
			    		<br/>
			    		<h2>No More Data Entry</h2>
		        		<p>So what does this mean? It means that you can save hours of manual entry by bringing online lead inquiries directly into your RainLeads account, where you can assign them to sales staff, change their status and manage sales milestones. Once you create your contact form to collect all the critical information you need, here are a few ways you can integrate your form!</p>
		        		
		        		<br/>
		        		
		        		<h2>Form Creation</h2>
		        		<p>Create your form fields and then style the form to match your existing website for a seamless experience. Once your form customization is complete, you'll be able to embed the code into your website, blog or WordPress by copying iFrame or Javascript code right into the HTML. If you don't manage your own website code, simply email it to your web developer with one click instructions. You can also contact us through the Support tab on your account or call us toll-free at 800-985-0058 and request a form installation for a one-time $50 fee.<br/><br/>Once your form is installed, unless you change the size of your form, any style or field changes update automatically without the need to reinstall the code!</p>
		        		
		        		<br/>
		        		
		        		<h2>Manage Multiple Forms</h2>
		        		<p>Have multiple websites? Create multiple forms to cater to each site, or use the same one on each. Save time by duplicating existing forms and editing new copies. Our RainLeads tracking technology collects source information with each lead you receive, so you can easily see where your leads are coming from and track each site’s performance and conversion.</p>
			    	</div>
			    </li>
			    <li id="slide_facebook_form">
			    	<ul class="featureList">
			    		<li>Sync with Facebook</li>
			    		<li>|</li>
			    		<li>Add Custom Forms to Business Facebook Pages</li>			    		
			    	</ul>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/fb1.jpg" />
			    		</div>
			    	</div>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/fb2.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		        				        		
		        		<p style="">Are you interested in leveraging your social media channels? RainLeads lead management software offers you the tools to seamlessly integrate your contact forms right into your business’s Facebook page, giving you the ability to collect information from leads right into your RainLeads dashboard.</p>
		        		
		        		<br/>
		        		
		        		<h2 style="float:none;">One Click Integration</h2>
		        		<p>The first step to adding your customized contact form to your company's Facebook page is to authenticate your account. This allows us to identify which Facebook pages are associated with your account. Then simply choose the form you'd like to integrate and choose the Facebook page you'd like to add it to.<br/><br/>Voila! You can now receive inquiries from this key social media channel.</p>
		        		
		        		<br/>
		        		
		        		<h2>Manage Multiple Pages</h2>
		        		<p>Have multiple Facebook pages? Create multiple forms to cater to each page, or use the same one on each. Save time by duplicating existing forms and editing new copies. Our RainLeads tracking technology collects source information with each lead you receive, so you can easily see where your leads are coming from and track each page’s performance and conversion.</p>
		        		
		        		
		        		
			    	</div>
			    </li>
			    <li id="slide_calendar">
			    	<ul class="featureList">
			    		<li>Create Events</li>
			    		<li>|</li>
			    		<li>Link Events with Leads</li>
			    		<li>|</li>
			    		<li>Set Reminders</li>
			    		<li>|</li>
			    		<li>Sync with iCal & Google Calendars</li>
			    	</ul>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/event1.jpg" />
			    		</div>
			    	</div>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/event2.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		<p>Generate new events right from your RainLeads account, including dates, times, reminders and the option to associate the event with a lead so you can easily link to the lead’s contact and project information before you speak. Create new events on RainLeads and seamlessly sync them with your existing iCal or Google calendars if you’d prefer. As an admin, you can also toggle between all events and those owned by specific team members.</p>
			    	</div>
			    </li>
			    <li id="slide_statistics">
			    	<ul class="featureList">
			    		<li>Advanced Form Statistics</li>
			    		<li>|</li>
			    		<li>Team Performance Statistics</li>
			    		<li>|</li>
			    		<li>Track Impressions, Submissions & Conversions</li>			    		
			    	</ul>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/stats1.jpg" />
			    		</div>
			    	</div>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/stats2.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		<p>Track the performance of your contact forms as well as your sales team. The RainLeads stats panel allows you to create reports for all or specific forms over a period of time to see impressions, submissions, country of origin and referring websites. You can also create reports over time to gauge your team members’ total leads, leads by status and milestones completed.</p>
			    	</div>
			    </li>
			    <li id="slide_administration">
			    	<ul class="featureList">
			    		<li>Add Team Members</li>
			    		<li>|</li>
			    		<li>Set Access Levels for Team Members</li>
			    		<li>|</li>
			    		<li>Customize Milestones & Statuses</li>			    		
			    	</ul>
			    	<div class="" id="tourImageWrap">
			    		<div id="tourImage">
			    			<img src="/tour/admin.jpg" />
			    		</div>
			    	</div>
			    	<div id="tourContent">
			    		<p>Through your admin panel you will have the ability to give your team members an appropriate level of access to the RainLeads tools on your account, from assigning leads to viewing statistics. You can also add or remove team members depending on your plan size. Add, edit, show, hide, delete and reorder lead milestones and statuses, including color coding!</p>
			    	</div>
			    </li>			    
			</ul>
	    	<div class="clear"></div>
	    </div>
	    </div>
    	<br/>
    	</div>
    </div>
    <div class="clear"></div>
    	<br/><br/>
    
    <?php include('inc/footer.php') ?>
</body>
</html>

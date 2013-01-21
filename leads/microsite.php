<html>
	<head>
		<title></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style>
		 body{
		 	background:#f8f8f8;
		 	font-family: 'Open Sans', sans-serif;
		 	margin: 0px;
		 	padding: 0px;
		 	line-height: 18px;
		 	color:#585858;
		 }
		 h1 {
		 	margin: 0px;
		 	padding: 0px;
		 	font-size: 24px;
		 	font-weight: normal;
		 }
		 a{
		 	color:#026ca5;
		 	text-decoration: none;
		 }
		 .left {
		 	float: left;
		 }
		 .clear {
		 	clear: both;
		 }
		 #box{
		 	width:900px;
		 	border:1px solid #c0c0c0;
		 	background: #fff;
		 	padding:0px;
		 	margin: auto;
		 	min-height: 700px;
		 }
		 #side {
		 	width: 200px;
		 	padding: 20px;
		 	font-size: 14px;
		 	color:#777;
		 	padding-bottom:60px;
		 }
		 strong {
		 	color:#333;
		 }
		 #main {
		 	width: 600px;
		 	margin: 20px;
		 	padding-top: 10px;
		 }
		 .company_desc{
		 	font-size:13px;
		 	line-height: 18px;
		 	color:#777;
		 	font-weight: normal;
		 	margin: 10px 0;
		 	border-bottom: 1px solid #c0c0c0;
		 	padding-bottom: 20px;
		 }
		 form table tr td {
		 	font-size: 13px;
		 }
		 form table tr td input[type=text],form table tr td textarea,form table tr td select{
		 	padding:5px;
		 	border: 1px solid #c0c0c0;
		 	font-size: 13px;
		 	width: 320px;
		 	outline: none !important;
		 }
		 form table tr td textarea{
		 	width: 450px;
		 }
		 .blue_button{
			background: #0491d8; /* Old browsers */
			background: -moz-linear-gradient(top, #0491d8 0%, #026fa8 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0491d8), color-stop(100%,#026fa8)); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top, #0491d8 0%,#026fa8 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top, #0491d8 0%,#026fa8 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top, #0491d8 0%,#026fa8 100%); /* IE10+ */
			background: linear-gradient(to bottom, #0491d8 0%,#026fa8 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0491d8', endColorstr='#026fa8',GradientType=0 ); /* IE6-9 */
			border: 1px solid #015380;
			color: white !important;
			box-shadow:  inset 0 1px 0 0 rgba(255,255,255,0.45);	
			padding-top:8px;
			cursor: pointer;
			padding: 7px 12px;
			padding-top:8px;
			text-align:center;
			border-radius: 2px;
		}
		ul{
			margin: 0px;
			margin-top: 10px;
			padding: 0px;
			padding-left: 18px;
			font-size: 13px;
			line-height: 20px;
		}
		hr{
			border: none;
			background: none;
			border-bottom: 1px solid #d4d4d4;
			margin: 15px 0;
		}
		</style>
		
		<script>
			$(window).resize(function(){
				$('#box').css({
					position:'absolute',
					left: ($(window).width() - $('#box').outerWidth())/2,
					top: ($(window).height()-80 - $('#box').outerHeight())/2
				});			
			});
		$(function() { 
			// To initially run the function:
			$(window).resize();
		});
		</script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="box">
			<div class="left" id="side">
				<img src="micro_logo.png" /><br/><br/>
				<strong>Services:</strong>
				<ul id="services">
					<li>Social Network Design</li>
					<li>Social Network Development</li>
					<li>Web Design</li>
					<li>Web Development</li>
					<li>iPhone Design</li>
					<li>Android Design</li>
					<li>Other computer stuff...</li>
				</ul>
				<hr/>
				<div id="address">
					76 Lafayette Street<br/>
					Suite 204<br/>
					Salem, Massachusetts 01904<br/>
					<br/>
					Tel: 978-607-0131<br/>
					Fax: 978-607-0134<br/>
					Email: <a href="mailto:info@pearsestreet.com">info@pearsestreet.com</a><br/>
					URL: <a href="http://www.pearsestreet.com">www.pearsestreet.com</a><br/>
				</div>
				<hr/>
				<div id="hours">
					<strong>Hours of Operation</strong><br/>
					Monday-Friday, 9am-5pm
				</div>
				<br/><br/>
				<a href="#"><img src="img/facebook.png"/></a>&nbsp;&nbsp;
				<a href="#"><img src="img/twitter.png"/></a>&nbsp;&nbsp;
				<a href="#"><img src="img/linkedin.png"/></a>&nbsp;&nbsp;
				<a href="#"><img src="img/google.png"/></a>
			</div>
			<div class="left" id="main">
				<h1>Pearse Street Inc.</h1>
				<div class="company_desc">
					Pearse Street Consulting, Inc. is a web development company dedicated to providing superior design and software development solutions, through our commitment to maximizing the creative process, considering tomorrow's trends and technologies, and delivering with integrity and professionalism.
				</div>
				<br/>
				<div>
					<form>
						<table>
							<tr>
								<td><strong>Full Name</strong><br/><input type="text" /></td>
							</tr>
							<tr>
								<td><strong>Email Address</strong><br/><input type="text" /></td>
							</tr>
							<tr>
								<td><strong>Phone #</strong><br/><input type="text" /></td>
							</tr>
							<tr>
								<td><strong>Service Needed</strong><br/><select rows="3" multiple="multiple"><option>Web Design</option><option>Web Development</option><option>iPhone Design</option><option>Android Design</option><option>Marketing</option></select></td>
							</tr>
							<tr>
								<td><strong>Comments</strong><br/><textarea rows="6"></textarea></td>
							</tr>
							
							<tr>
								<td align="right"><br/><a class="blue_button">Submit</a></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</body>
</html>

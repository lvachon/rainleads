<html>
	<head>
		<title></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="js/edit-in-place.js"></script>
		<link rel="shortcut icon" href="<?=$HOME_URL;?>img/favicon.ico" />
		<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>jquery.fancybox.css" />
		<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>tip-twitter/tip-twitter.css" />
		<script src="<?=$HOME_URL?>jquery.fancybox.js"></script>
		<script src="<?=$HOME_URL?>jquery.poshytip.min.js"></script>
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
		 	margin-top:50px;
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
		.editable {
			display: block;
			padding: 1px;
		}
		.editable:hover {
			background: #DFEFFE;
		}
		.eip_input{
			outline: none;
			border:1px solid #C1D8EF;
			padding: 2px;
		}
		.edit_image{
			width:200px;
			height: 200px;
			background: url('img/plus.png') center center no-repeat #edeff6;
			border:1px dashed #c1d8ef;
		}
		</style>
		
		<script>
			
		$(function() { 
			// To initially run the function:
			
			$('body').editables( { 
		      beforeEdit: function(field){
		        field.val(this.text());
		      },
		      beforeFreeze: function(display){ 
		        display.text(this.val());
		      },
		      onFreeze: function(){
		      	//alert($(this).attr('id')+":'"+$(this).val()+"'");
		      }
		    });
		});
		</script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<script>
$(function() {
 // Handler for .ready() called.
$('.tip').poshytip({
	className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignY:'bottom',
	alignX: 'center',
	allowTipHover: false,
	fade: false,
	slide: false,
	offsetY:5,
	offsetX:0
});
<?php if(strlen($_GET['msg'])){?>
	$.fancybox('<h2><?=urldecode($_GET['msg'])?></h2>');
<?php } ?>

});
</script>
	</head>
	<body>
		<div id="box">
			<div class="left" id="side">
				<div class="edit_image tip" title="Upload Logo">
					
				</div>
				<br/>
				<strong>Services:</strong>
				<ul id="services">
					<li>
						<span class="editable">
							<label data-type="editable" data-for="input#service1" data-field="service1">Fence Whitewashing</label>
							<input class="eip_input" id="service1" />
						</span>
					</li>
					<li>
						<span class="editable">
							<label data-type="editable" data-for="input#service2" data-field="service2">Widget Building</label>
							<input class="eip_input" id="service2" />
						</span>
					</li>
					<li>
						<span class="editable">
							<label data-type="editable" data-for="input#service3" data-field="service3">Imports / Exports</label>
							<input class="eip_input" id="service3" />
						</span>
					</li>
					<li>
						<span class="editable">
							<label data-type="editable" data-for="input#service4" data-field="service4">Fence Whitewashing</label>
							<input class="eip_input" id="service4" />
						</span>
					</li>
					<li>
						<span class="editable">
							<label data-type="editable" data-for="input#service5" data-field="service5">Widget Building</label>
							<input class="eip_input" id="service5" />
						</span>
					</li>
					<li>
						<span class="editable">
							<label data-type="editable" data-for="input#service6" data-field="service6">Imports / Exports</label>
							<input class="eip_input" id="service6" />
						</span>
					</li>
				</ul>
				<hr/>
				<div id="address">
					<span class="editable">
						<label data-type="editable" data-for="input#address1" data-field="address1">123 Main St.</label>
						<input class="eip_input" id="address1" />
					</span>
					<span class="editable">
						<label data-type="editable" data-for="input#address2" data-field="address2">Suite 321</label>
						<input class="eip_input" id="address2" />
					</span>
					<span class="editable">
						<label data-type="editable" data-for="input#city_state" data-field="city_state">City,ST 01234</label>
						<input class="eip_input" id="city_state"/>
					</span>
					<br/>
					
					<span class="editable">
						Tel: <label data-type="editable" data-for="input#tel" data-field="tel">555-555-1212</label>
						<input class="eip_input" id="tel" />
					</span>					
					<span class="editable">
						Fax: <label data-type="editable" data-for="input#fax" data-field="fax">555-555-1210</label>
						<input class="eip_input" id="fax" />
					</span>					
					<span class="editable">
						Email: <label data-type="editable" data-for="input#email" data-field="email">name@domain.com</label>
						<input class="eip_input" id="email" />
					</span>					
					<span class="editable">
						URL: <label data-type="editable" data-for="input#url" data-field="url">www.domain.com</label>
						<input class="eip_input" id="url" />
					</span>
				</div>
				<hr/>
				<div id="hours">
					<strong>Hours of Operation</strong><br/>
					<span class="editable">
							<label data-type="editable" data-for="input#hours" data-field="hours">Mon-Fri, 9-5</label>
							<input class="eip_input" id="hours" />
						</span>
				</div>
				<br/><br/>
				<a href="#"><img src="img/facebook.png"/></a>&nbsp;&nbsp;
				<a href="#"><img src="img/twitter.png"/></a>&nbsp;&nbsp;
				<a href="#"><img src="img/linkedin.png"/></a>&nbsp;&nbsp;
				<a href="#"><img src="img/google.png"/></a>
			</div>
			<div class="left" id="main">
				<h1 class="editable">
						<label data-type="editable" data-for="input#company" data-field="company">Pearse Street Inc.</label>
						<input class="eip_input" style="font-size:18px; padding:4px;" id="company" />
					</h1>
				<div class="company_desc">
					<span class="editable">
						<label data-type="editable" data-for="textarea#desc" data-field="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus blandit elementum dictum. In hac habitasse platea dictumst. Aliquam erat volutpat. Nam consectetur mollis ornare. Pellentesque sodales hendrerit est, imperdiet tempor lorem suscipit eget.</label>
						<textarea class="eip_input" id="desc" style="width:594px; margin-left:2px;" rows="4"></textarea>
					</span>
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

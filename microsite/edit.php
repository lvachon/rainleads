<?php include '../inc/trois.php';
loginRequired();
$account = new Account(verAccount());
$user = new User(verCookie());
if($account->user_id!=$user->id){errorMsg("Only the account administrator can modify the microsite");}
$ACCOUNTDEFAULTS = array("city_state"=>$account->data['city'].", ".$account->data['state']." ".$account->data['zip'],"address"=>$account->data['address'],"address2"=>$account->data['address2'],"tel"=>$account->data['phone']);//Of course the field names aren't the same, that would make too much fucking sense.   FUCKING FUCK! THEY AREN"T EVEN THE SAME STRUCTURE, FUCK YOU!
function Z($field){
	global $FUCKINGDEFAULTS,$ACCOUNTDEFAULTS,$account;
	if(strlen($account->data['MD'.$field])){return $account->data['MD'.$field];}
	if(strlen($ACCOUNTDEFAULTS[$field])){return $ACCOUNTDEFAULTS[$field];}
	return "";
	
}
?>
<html>
	<head>
	<html>
		<?php include('../inc/head.php'); ?>
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
		<style>
			.setting{
				padding: 15px 0;
				border-bottom: 1px dashed #c0c0c0;
			}
			.setting strong {
				font-size: 16px;
				
				display: block;
			}
			.editable {
				display: block;
				position: relative;
				
				padding:5px 0;
			}
			.editable input {
				
				top:0px;
				width: 220px;
				border: 1px solid #c0c0c0;
				padding: 5px;
			}
		</style>
	</head>
	<body>
		<?php include('../inc/header.php'); ?>
		<div id="content" class="inner">
			<div id="side" class="left">
		    	<?php include('../inc/sidenav.php') ?>
		    </div>
		    <div id="main" class="left">
		    	<h1>Edit Microsite Details</h1>
		    	<div class="clear"></div>
		    	<p>Edit the details that will show up on your Microsite webpage.</p>
		    	<br/>
		<form id='avfrm' target="hidn" action="save.php" method="post" enctype="multipart/form-data">
			<input type='file' name='file' id='file' onchange='$("#avfrm")[0].submit();' style='display:none;'/>
		</form>
		<iframe id='hidn' name='hidn' style='display:none;'></iframe>
		<form method='post' action='save.php'>
			
				<div class="edit_image tip" id='logodiv' title="Upload Logo" <?php if(file_exists(getcwd()."/{$account->id}.jpg")){?>style='background: url(<?=$account->id?>.jpg) center no-repeat;'<?php } ?> onclick='$("#file").click();'>
					
				</div>
				
				<div class="left" style="height:100%; width:150px; margin-right:20px;">
				<div class='setting'>
					<strong>Services:</strong>
					<ul id="services">
						<?php 
						$cnt=0;
						foreach(explode(",",$account->data['services']) as $service){?>
							<li>
								<input class="service_input" name="services[]" value="<?=htmlentities($service);?>"/>
							</li>
						<?php } ?>
						<li>
							<a href='javascript:void();' onclick='ohtml = $(this).parent().html();$(this).parent().html("<input class=\"service_input\" name=\"services[]\"/>");$("#services").append("<li>"+ohtml+"</li>");'/>Add service</a>
						</li>
					</ul>
				</div>
				</div>
				<div class="left" style="width:500px;">
				<div class='setting'>
					<strong>Address:</strong><br/>
					<span class="editable">
						<label data-type="editable" data-for="input#address" data-field="address">
							<div class="label">Address:</div>
							<input class="eip_input" name="address" id="address" value="<?=Z("address");?>"/>
						</label>
					</span>
					<span class="editable">
						<label data-type="editable" data-for="input#address2" data-field="address2">
							<div class="label">Address Line 2:</div>
							<input class="eip_input" name="address2" id="address2" value="<?=Z("address2");?>"/>
						</label>
					</span>
					<span class="editable">
						<label data-type="editable" data-for="input#city_state" data-field="city_state">
							<div class="label">City, State & Zip:</div>
							<input class="eip_input" id="city_state" name="city_state" value="<?=Z("city_state");?>"/>
						</label>
					</span>
					
				</div>
				<div class='setting'>
					<strong>Contact Info:</strong><br/>
					<span class="editable">
						<label data-type="editable" data-for="input#tel" data-field="tel">
							<div class="label">Telephone:</div>
							<input class="eip_input" name="tel" id="tel" value="<?=Z("tel");?>"/>
						</label>
					</span>				
					<span class="editable">
						<label data-type="editable" data-for="input#fax" data-field="fax">
							<div class="label">Fax:</div>							
							<input class="eip_input" name="fax" id="fax" value="<?=Z("fax");?>"/>
						</label>
					</span>					
					<span class="editable">
						<label data-type="editable" data-for="input#email" data-field="email">
							<div class="label">Email:</div>
							<input class="eip_input" name="email" id="email" value="<?=Z("email");?>"/>
						</label>
					</span>					
					<span class="editable">
						<label data-type="editable" data-for="input#url" data-field="url">
							<div class="label">URL:</div>
							<input class="eip_input" name="url" id="url" value="<?=Z("url");?>"/>
						</label>
					</span>
					<span class="editable">
						<label data-type="editable" data-for="input#hours" data-field="hours">
							<div class="label">Hours of Operation:</div>
							<input class="eip_input" name="hours" id="hours"  value="<?=Z("hours");?>"/>
						</label>
					</span>
				</div>
				
				<div class='setting'>
					<strong>Company Info:</strong><br/>
					<span class='editable'>
						<label data-type="editable" data-for="input#hours" data-field="hours">
							<div class="label">Company Name:</div>
							<input class="eip_input" style="font-size:18px; padding:4px;" name="company" id="company" value="<?=Z("company");?>"/>
						</label>
					</span>
					<span class="editable">
						<label data-type="editable" data-for="textarea#desc" data-field="desc">
							<div class="label">Description:</div>
							<textarea class="eip_input" name="desc" id="desc" style="width:594px; margin-left:2px;" rows="4"><?=Z("desc");?></textarea>
						</label>
					</span>
				</div>
				<br/>
				</div>
				<div class="clear"></div>
				<input type='submit' value='Save'/><input type='button' value='Preview' onclick='window.open("http://www.coba.se/<?=$account->data['microsite_url'];?>");'/>
			</div>
			<div class="clear"></div>
			
			</form>
		</div>
	</body>
</html>

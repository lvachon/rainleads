<?php include '../inc/trois.php';
loginRequired();
$acct = new Account(verAccount());
$f = new Form($_GET['id']);
if($f->account_id!=$acct->id){die("This form does not belong to the current account {$f->account_id}=={$acct->id}");}
?>
<html>
<?php include('../inc/head.php'); ?>

<script>
	var url = 'https://www.rainleads.com/forms/showform.php?id=<?=$f->id;?>&code=';
	var curType='iframe'
	function updateCodes(type,code){
		curType = type;
		if(type=='iframe'){
			$("#previewdiv").html("<iframe src='"+url+code+"' width='<?=max(intval($f->data['width']),400);?>' height='600' frameborder='0'></iframe>");
			$("#codetext").val("<iframe src='"+url+code+"' width='<?=max(intval($f->data['width']),400);?>' height='600' frameborder='0'></iframe>");
		}
		if(type=='ajax'){
			var script = "scr" + "ipt";
			outcode = "<div id='rainleads_ajax_request_div' ></div><"+script+" src='//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></"+script+"><"+script+">$.get('<?=$HOME_URL;?>/forms/showform.php?id=<?=$f->id;?>&code="+code+"',function(data){$('#rainleads_ajax_request_div').html(data);});</"+script+">";
			//$("#previewdiv").html(outcode);
			$.get("showform.php?id=<?=$f->id;?>&code="+code,function(data){$("#previewdiv").html(data);});
			$("#codetext").val(outcode);
		}
	}
	var intv=0;
	function showTab(tab){
		$('.tab_content').hide();
		$('*[data-tab="'+tab+'"]').show();
		$('.tab').removeClass('active');
		$('#tab-'+tab).addClass('active');
	}
</script>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
    <h1>Embed Your Contact Form</h1>
    
    <div class="clear"></div>
    <p style="font-size:14px; margin:5px 0 15px 0;">We offer a few different options when it comes to embedding your contact form for use by your leads.</p>
    <br/>
    <div class="tab_box vertical_tabs">
    	<div class="tab active" id="tab-code" onClick="showTab('code')">Website Embed Code</div>
    	<div class="tab" id="tab-microsite" onClick="showTab('microsite')">Use on a Microsite</div>
        <div class="tab" id="tab-facebook" onClick="showTab('facebook')">Add to a Facebook Page</div>
        <div class="tab" id="tab-popup" onClick="showTab('popup')">As a Pop-up Window Link</div>
    </div>
    <div class="tab_content vertical_tab_content" data-tab="code" style="font-size:14px; line-height:20px;">
		<div id='formcode<?=$f->id;?>'>
			<input type='hidden' value="<?=urlencode($f->data['title']);?>_iframe" id='tc' onkeydown='clearInterval(intv);intv = setTimeout("updateCodes(curType,$(\"#tc\").val())",250);'/>
			Code Type: <select onchange = 'updateCodes(this.value,"<?=urlencode($f->data['title']);?>_"+this.value);$("#tc").val("<?=urlencode($f->data['title']);?>_"+this.value);'>
				<option value='iframe'>IFrame</option>
				<option value='ajax'>JS Request</option>
			</select><br/>
			
			<textarea rows='10' cols='60' id='codetext'><iframe src='https://www.rainleads.com/forms/showform.php?id=<?=$f->id;?>&code=<?=urlencode($f->data['title']);?>_iframe' width='<?=max(intval($f->data['width']),400);?>' height='600' frameborder=0></iframe></textarea><br />
			<a class="button left" href="javascript:void(0);" onClick="$.post('email-code.php?id=<?=$f->id?>',function(data){$.fancybox(data);});">Email Code</a><div class="clear"></div>
		</div>
	</div>
	<div class="tab_content" data-tab="facebook" style="display:none;">
		<br/><br/>
		<h3>Add a Contact Form to your Facebook Fan Page!</h3>
		<p style="font-size:14px; margin:0px 0 15px 0;"><center>Use our exclusive Facebook app to allow your fans submit leads directly from Facebook.</center></p>
		<center>
			<div onClick="document.location.href='http://www.facebook.com/dialog/pagetab?app_id=346873902077931&next=http://www.rainleads.com/forms/facebook/view.php'" class="button_outside_border_blue">
				<div class="button_inside_border_blue">
					Add RainLeads to Facebook!
				</div>
			</div>
		</center>
	</div>
	
	</div>
	<div class="clear"></div>
</div>
<?php include('../inc/footer.php'); ?>

</body>
</html>

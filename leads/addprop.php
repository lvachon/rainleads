<?php include '../inc/trois.php';
$user = new User(verCookie());
$account = new Account(verAccount());
$con = conDB();
$id = mysql_escape_string(intval($_GET['id']));
$r = mysql_query("SELECT * from form_results where id=$id",$con);
$d = mysql_fetch_array($r);
if(intval($_GET['prop_id'])){
	$id = intval($_GET['prop_id']);
	$con = conDB();
	if(intval($_GET['kill'])){
		mysql_query("DELETE from proposals where id=$id and account_id={$account->id} LIMIT 1",$con);
		exec("rm ".getcwd()."/../proposals/{$id}");
		header("Location: lead.php?id={$_GET['lead_id']}&show=proposals");
		die();
	}
	$r = mysql_query("SELECT * from proposals where id=$id",$con);
	$prop = mysql_fetch_array($r);
}
?>
<h2 class="left"><?php if(intval($_GET['id'])){?>Edit<?php }else{ ?>Add<?php } ?> a File</h2>
<div class="clear"></div>
<hr class="title_line"/>
<form method='post' action='saveprop.php'  enctype='multipart/form-data'>
	<input type='hidden' name='result_id' value='<?=intval($_GET['id']);?>'/>
	<input type='hidden' name='id' value='<?=intval($prop['id']);?>'/>
	<table cellpadding="5">
		<tr><td width="135">File Name:</td><td><input type='text' name='title' style="width:260px;" value="<?=htmlentities($prop['title']);?>"/></td></tr>
		<tr><td>Is this a Proposal?</td><td><input type="checkbox" onchange="togProp()" /></td></tr>
		<tr class="prop-row"><td>Amount:</td><td><?= $account->data['currency'] ?> <input type='text' name='amount' style="width:60px;" value="<?=htmlentities($prop['amount']);?>"/></td></tr>
		<tr class="prop-row"><td>Probability:</td><td><div id='probbar'><div id='probslider'><div></div><span></span></div></div><input type='hidden' id='prob' name='prob' value="<?=floatval($prop['probability']);?>"/></td></tr>
		<?php if($account->storageUsed()<$account->storageLimit()){?>
			<tr><td>File:</td><td><input type='file' name='file'/><small><?=floor($account->storageUsed()/10485.76)/100;?>/<?=floor($account->storageLimit()/10485.76)/100;?>MB</small></td></tr>
		<?php }else{?>
			<tr><td>Storage limit reached, unable to upload file<small><?=floor($account->storageUsed()/10485.76)/100;?>/<?=floor($account->storageLimit()/10485.76)/100;?>MB</small></td></tr>
		<?php } ?>
		<?php if($d['pipeline'] !=1){?>
		<tr><td>Add lead to pipeline?</td><td><input type='checkbox' name='pipe' value='1' /></td></tr>
		<?php } ?>
		<tr><td colspan='2' align='right'><input type='submit' class="button blue_button" value='<?php if(intval($_GET['id'])){?>Save<?php }else{ ?>Add<?php } ?> File'/>
	</table>
	
<style>
	#probbar {width:270px;height:16px;background:#F5F6F7;border:solid 1px #DBDCDC;text-align:center; margin-right:16px;}
	#probslider {width:32px;height:16px;background:#0C7EB9;position:relative;overflow:;color:#FFFFFF; font-size:13px; font-weight:bold; text-align:center;line-height:16px;}
	#probslider::selection{background:#0C7EB9;}
	#probslider span {
		width:26px;
		height:26px;
		background: url('../img/metal-handle.png') center center no-repeat;
		display: block;
		position: absolute;
		top:-4px;
		cursor: pointer;
		right:-12px;
		
	}
	input[type=text]{
		padding:5px;
		border: 1px solid #c8c8c8;
	}
	.prop-row{display: none;}
</style>
<script>
var mouseX,mouseY,mouseDown;
$("#probslider").css({"width":($("#probbar").width()*<?=floatval($prop['probability']);?>)+"px"});
$("#probslider div").html("<?=floor(100*floatval($prop['probability']));?>%");
$("#probbar").mousemove(function(e){
	mouseX=e.pageX;
	mouseY=e.pageY;
	
	if(mouseDown==true){
		doPropSlider();
	}
});
function togProp(){
	if($('.prop-row').is(':hidden')){
		$('.prop-row').show();
	}else{
		$('.prop-row').hide();
	}
	$.fancybox.update();
}
$("#probbar").mousedown(function(e){
	mouseDown=true;
	doPropSlider();
	e.originalEvent.preventDefault();
});
$("#probbar").mouseup(function(e){
	mouseDown=false;
	
});

function doPropSlider(){
	var width = mouseX-$("#probbar").offset().left;
	var maxwidth = $("#probbar").width();
	if(width>maxwidth){width=maxwidth;}
	
	$("#probslider").css({"width":width+"px"});
	$("#prob").val(width/maxwidth);
	$("#probslider div").html(Math.floor(100*width/maxwidth)+"%");
}

</script>
			
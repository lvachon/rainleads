<?php
	include_once '../inc/trois.php';
	if(!in_array(verCookie(),$ADMIN_IDS)){errorMsg("This page is for administrators only");die();}
	$con = conDB();
	$viewer = new User(verCookie());	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?= $SITE_NAME ?> Network Administration</title>
<link type="text/css" href="css/custom-theme/jquery-ui-1.8rc1.custom.css" rel="stylesheet" />	
<link type="text/css" href="style.css" media="screen" rel="stylesheet" />
<link type="text/css" href="css/tipsy.css" media="screen" rel="stylesheet" />
<link type="text/css" href="css/colorbox.css" media="screen" rel="stylesheet" />
<link type="text/css" href="../css/facebox.css" media="screen" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="js/jquery.tipsy.js"></script>
<script type="text/javascript" src="../js/facebox.php"></script>
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8rc1.custom.min.js"></script>
<script>$(document).ready(function(){$("a[rel='facebox']").facebox();});</script>
<script>

	$(function() {
	var stop = false;
	$("#accordion h3").click(function(event) {
		if (stop) {
			event.stopImmediatePropagation();
			event.preventDefault();
			stop = false;
		}
	
	});
	$('#dialog').dialog({
				autoOpen: false,
				width: 600,
				buttons: {
					"Ok": function() { 
						$(this).dialog("close"); 
					}, 
					"Cancel": function() { 
						$(this).dialog("close"); 
					} 
				}
			});
	$("#accordion").accordion({
		header: "> div > h3"
	});
	
	$('.tip').tipsy({gravity: 'n'});
	$(".cbox").colorbox({iframe:true, innerWidth:690, innerHeight:425});
	});
	function del(what,id){
		$.post('delete.php',{
			   id:id,
			   type:what
			   },function(data){
					$.facebox(data);   
			   });
	}
	function edit(what,id){
		$.get("edit.php",{"id":id,"type":what},function(data){$.facebox(data);});
	}
	
	function feature(what,id){
		$.get("feature.php",{"id":id,"type":what},function(data){
			if (data == '0') {
				$('#f_'+what+'_'+id).attr('src','img/feature_off.png');
				$('#f_'+what+'_'+id).parent().attr('original-title', 'Feature');
			} else if (data == '1') {
				$('#f_'+what+'_'+id).attr('src','img/feature.png');
				$('#f_'+what+'_'+id).parent().attr('original-title', 'Un-Feature');
			}
		});
	}
</script>


<style>
.left {float:left;}
.right{float:right;}
.clear {clear:both;}
</style>
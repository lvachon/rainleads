<?php ob_start(); require_once('../inc/trois.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<form method='post' action='<?=$HOME_URL?>avatar/save.php'  enctype="multipart/form-data" target="hidden" id="upf">
		<h3>Upload Avatar</h3>
        <hr />
        <span id="msg">Choose Image File:</span>
        <br /><br/>
		<table width="370" cellpadding="0" cellspacing="6" border="0">
		<tr>
			<td align="left">
				<input type='file' class="text-box" name='photo' onchange="$('#up').show('slow'),$(this).hide(),$('#msg').html('Uploading &hellip;'),$('#upf').submit();" />
				<div id="up" style="display:none; padding:15px 0px;"></div>
			</td>
		</tr>		
		</table>
        <iframe width="0" height="0" frameborder="0" name="hidden" id="hidden"></iframe>
	</form>
</body>
</html>

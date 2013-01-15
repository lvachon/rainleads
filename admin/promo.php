<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'head.php'; 
$con = conDB();
if(intval($_GET['kill'])){
	mysql_query("DELETE from promo where id=".strval(intval($_GET['kill'])),$con);
	header("Location: promo.php");
	die();
}
if(strlen($_POST['code'])){
	$code = mysql_escape_string(strtolower($_POST['code']));
	$desc = mysql_escape_string($_POST['description']);
	$type = mysql_escape_string($_POST['type']);
	$amount = intval($_POST['amount']);
	mysql_query("INSERT INTO promo(code,description,`type`,amount) VALUES('$code','$desc','$type',$amount)",$con);
	header("Location: promo.php");
	die();
}
$pr = mysql_query("SELECT *,(SELECT count(*) from accounts where getData('promoUsed',data)=code) as uses from promo",$con);
?>
	
</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;Promo Codes</h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is your Promocode Administration Panel.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4>All Codes (<?=mysql_num_rows($pr);?>) <div class="clear"></div></h4>
                <div class="ui-widget-content">
                    <form method='post' action='promo.php'>
	                    <table width="776" cellpadding="8" cellspacing="0" style="font-size:12px; font-weight:normal;" class="table-list" id="userList">
	                    	<tr style="font-weight:bold;">
	                        	<td align="left" width="150">Code</td>
								<td>Description</td>
	                            <td>Type</td>
								<td>Amount</td>
	                            <td align="left" width="150">Uses</td>
	                            <td align="center" width="160">Action</td>
	                        </tr>
                        	<?php 
								$cnt = 0;
								
								while($row = mysql_fetch_array($pr)){
								?><tr>
									<td><?=$row['code'];?></td>
									<td><?=$row['description'];?></td>
									<td><?=$row['type'];?></td>
									<td><?=$row['amount'];?></td>
									<td><?=$row['uses'];?></td>
									<td><a href='javascript:void(0);' onclick='if(confirm("Do you really want to delete this promo code?")){document.location.href="promo.php?kill=<?=$row['id'];?>";}'>Delete</a></td>
								</tr>
								<?php }	?>
	                   		<tr>
                   				<td><input name='code' type='text'/></td>
                   				<td><textarea name='description'></textarea></td>
                   				<td><input type='text' value='free_months' disabled='true'/><input type='hidden' name='type' value='free_months' /></td>
                   				<td><input name='amount' type='text'/></td>
                   				<td></td>
                   				<td><input type='submit' value='Save'/></td>
                   			</tr>
	                    </table>
	            	</form>
                </div>
                
            </div>
        </div>
        
</div>
</body>
</html>

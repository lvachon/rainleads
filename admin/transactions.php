<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'head.php'; 

$pr = mysql_query("SELECT * from transactions order by datestamp desc",$con);
?>
	
</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;Transactions</h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is your Transaction Administration Panel.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4>All Transactions (<?=mysql_num_rows($pr);?>) <div class="clear"></div></h4>
                <div class="ui-widget-content">
                    <form method='post' action='promo.php'>
	                    <table width="776" cellpadding="8" cellspacing="0" style="font-size:12px; font-weight:normal;" class="table-list" id="userList">
	                    	<tr style="font-weight:bold;">
	                        	<td align="left" width="150">Date</td>
								<td>Account</td>
	                            <td>Type</td>
								<td>Amount</td>
								<td>TXID</td>
	                            <td align="left" width="150">Details</td>
	                        </tr>
                        	<?php 
								$cnt = 0;
								
								while($row = mysql_fetch_array($pr)){
									$ct = new Account($row['account_id']);
								?><tr>
									<td><?=date("M j Y",$row['datestamp']);?></td>
									<td><?=$ct->title;?></td>
									<td><?=$row['type'];?></td>
									<td><?=$row['amount'];?></td>
									<td><?=$row['txid'];?></td>
									<td>
										<div style='display:none;width:400px;height:400px;' id='txdetails<?=$row['id'];?>'><textarea style='width:400px;height:400px;'><?php var_dump(unserialize($row['data']));?></textarea></div>
										<a href='javascript:$.facebox($("#txdetails<?=$row['id'];?>").html());'>Details</a>
									</td>
								</tr>
								<?php }	?>
	                    </table>
	            	</form>
                </div>
                
            </div>
        </div>
        
</div>
</body>
</html>

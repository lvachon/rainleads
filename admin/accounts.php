<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'head.php'; 

$pr = mysql_query("SELECT *,(SELECT min(datestamp) from membership where account_id=accounts.id) as joined from accounts order by joined asc",$con);
?>
	
</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;Accounts</h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is your Accounts Administration Panel.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4>All Accounts (<?=mysql_num_rows($pr);?>) <div class="clear"></div></h4>
                <div class="ui-widget-content">
                    <table width="776" cellpadding="8" cellspacing="0" style="font-size:12px; font-weight:normal;" class="table-list" id="userList">
                    	<tr style="font-weight:bold;">
                        	<td align="left" width="150">Title</td>
							<td>Membership</td>
                            <td>Users</td>
							<td>Storage</td>
							<td>Forms</td>
                            <td align="left" width="150">Joindate</td>
                        </tr>
                    	<?php 
							$cnt = 0;
							
							while($row = mysql_fetch_array($pr)){
								$ct = new Account($row['id']);
								$rr = mysql_query("SELECT count(*) from forms where deleted=0 and account_id={$ct->id}",$con);
								$nf = mysql_fetch_array($rr);
								$nf = intval($nf[0]);
							?><tr>
								<td><a href='account.php?id=<?=$ct->id;?>'><?=$ct->title;?></a></td>
								<td><?=$ct->membership;?></td>
								<td><?=count($ct->members);?>/ <?=$ct->userLimit();?></td>
								<td><?=floor($ct->storageUsed()/10485.76)/100;?>/ <?=floor($ct->storageLimit()/10485.76)/100;?>MB</td>
								<td><?=$nf;?>/<?=$ct->formLimit();?></td>
								<td><?=date("M j, Y",$row['joined']);?></td>
							</tr>
							<?php }	?>
                    </table>
            </div>
                
            </div>
        </div>
        
</div>
</body>
</html>

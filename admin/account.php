<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'head.php'; 

$account = new Account($_GET['id']);
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
				This is your Account Details Panel.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4><?=$account->title;?> subscription <div class="clear"></div></h4>
                <div class="ui-widget-content">
                	<table>
                    	<tr>
                    		<td>Subscription Level:</td>
                    		<td><?=$account->membership;?></td>
                    	</tr>
                    	<?php if($account->membership=="free"){?>
                    		<tr>
	                    		<td>Expiration:</td>
	                    		<td><?=date("M j Y",$account->expiration);?></td>
	                    	</tr>
	                    <?php } ?>
                    	<tr>
                    		<td>Subscription ID:</td>
                    		<td><?=$account->sub_id;?></td>
                    	</tr>
                    	<tr>
                    		<td>Storage Limit:</td>
                    		<td><?=floor($account->storageUsed()/10485.76)/100.0;?>/<?=floor($account->storageLimit()/10485.76)/100.0;?>MB</td>
                    	</tr>
                    	<tr>
                    		<td><input type='button' value='+ 100MB' onclick='document.location.href="adjustthing.php?delta=100&account=<?=$account->id;?>&thing=storage";'/></td>
                    		<td><input type='button' value='- 100MB' onclick='document.location.href="adjustthing.php?delta=-100&account=<?=$account->id;?>&thing=storage";'/></td>
                    	</tr>
                    	<tr>
                    		<td>User Limit:</td>
                    		<td><?=count($account->members);?>/<?=$account->userLimit();?></td>
                    	</tr>
                    	<tr>
                    		<td><input type='button' value='+ User' onclick='document.location.href="adjustthing.php?delta=1&account=<?=$account->id;?>&thing=user";'/></td>
                    		<td><input type='button' value='- User' onclick='document.location.href="adjustthing.php?delta=-1&account=<?=$account->id;?>&thing=user";'/></td>
                    	</tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4><?=$account->title;?> info <div class="clear"></div></h4>
                <div class="ui-widget-content">
                	<table>
                    <?php foreach($account->data as $var=>$val){?>
                    	<tr>
                    		<td><?=htmlentities($var);?></td>
                    		<td><?=htmlentities($val);?></td>
                    	</tr>
                    <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4><?=$account->title;?> members <div class="clear"></div></h4>
                <div class="ui-widget-content">
                	<table>
                    <?php foreach($account->members as $uid){$ux = new User($uid);?>
                    	<tr>
                    		<td><?=$ux->avatar(64,64,false);?></td>
                    		<td><a href='view-user.php?id=<?=$ux->id;?>'><?=$ux->name("F l");?></a></td>
                    	</tr>
                    <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4><?=$account->title;?> forms <div class="clear"></div></h4>
                <div class="ui-widget-content">
                	<table>
                    <?php 
					$con = conDB();
					$r = mysql_query("SELECT *,(SELECT count(*) from form_impressions where form_id=forms.id) as imps,(SELECT count(*) from form_results where form_id=forms.id) as subs from forms where account_id = {$account->id} order by deleted,id",$con);
					while($f = mysql_fetch_array($r)){
						$form = new Form($f['id']);?>
                    	<tr>
                    		<td><?=$form->data['title'];?></td>
                    		<td><?=$f['imps']?> impressions</td>
                    		<td><?=$f['subs']?> submissions</td>
                    	</tr>
                    <?php } ?>
                    </table>
                </div>
            </div>
        </div>
</div>
</body>
</html>

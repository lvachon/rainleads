<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
	include 'head.php';
	$user = new User($_GET['id']);
	$getMembership = mysql_query("SELECT * FROM membership WHERE user_id = {$user->id}",$con);
	$membership = mysql_fetch_array($getMembership);
	$getTransactions = mysql_query("SELECT * FROM transactions WHERE user_id = {$user->id}",$con);
?>
<style>
h4 {
	margin:5px;
	padding:0 5px;
}
small a:hover {
	text-decoration:underline !important;
}
.grid_120_outer {width:116px; height:130px; overflow:hidden;  margin:0 6px;}
.grid_120_inner {width:110px; height:110px; overflow:hidden; border:1px solid #ccc; padding:2px;}
.grid_120 {width:110px; height:110px; overflow:hidden;}
.grid_120_meta {display:none; text-align:right; padding:3px; margin-top:5px;}
.view_all_title { margin:5px; position:relative; top:7px; font-size:11px;}
</style>

</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;<a href="users.php">All Users</a>&middot;<?= $user->name('F L') ?></h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is an Individual User Administration Panel. Here you can manage the registered member's details and media.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
            <br />
				<div class="left">
                	<div style="width:60px; height:60px; overflow:hidden;"><?= $user->avatar(60,0,false) ?></div>
                </div>
                <div class="left">
                	<h4 style="margin-bottom:10px;"><?= $user->name('F L') ?></h4>&nbsp;
               		<?php if(in_array($user->id,$ADMIN_IDS)){?>
                        <a href="users.php?kill=<?=$user->id?>" class="tip" original-title="Remove Admin"><img src="img/admin.png" title="remove admin" /></a>
                    <?php }else{?>
                        <a href="users.php?admin=<?=$user->id?>" class="tip" original-title="Make Admin"><img src="img/admin_off.png" title="make admin" /></a>
                    <?php } ?>&nbsp;
                    
                    <a href="<?= $HOME_URL ?>mail/send.php?id=<?=$user->id;?>" rel="facebox" class="tip" title="Send Message"><img src="img/email.png" /></a>&nbsp;
                    <a href="view-user.php?id=<?= $user->id ?>" class="tip" title="Edit User"><img src="img/edit.png" /></a>&nbsp;
                    <a href="javascript:none" onclick="deleteUser(<?= $user->id ?>)" class="tip" title="Delete User"><img src="img/delete.png" /></a>
                </div>               
                <br clear="all" />
                <br />
                
                <div class="ui-widget-content" style="padding-bottom:8px;">
                    <div class="left"><h4>Information</h4></div>
                    <div class="right"></div>
                    <br clear="all" />
                    <hr />
                   <form action="save-user.php" method="post">
                       <input type="hidden" name="id" value="<?=$user->id?>" />

                    <table width="100%" cellspacing="5" cellpadding="5">
                    	<tr>
                        <?php 
						$count = 0;
                        foreach($user->data as $key=>$value){
                          ?>
                          <?php if($key != 'group' && $key != 'gtitle'){?>
                                <td>                            
                                <label><?=$key?></label>:<br />
                                <?php if($key == 'join_date' || $key == 'last_activity' || $key == 'subscriptionExpires' || $key == 'subscriptionStart'){
                                    echo date('m/d/y',$value);
                                }else{?>
                                        
                                        <textarea name="<?=$key?>"><?=$value?></textarea>
                                <?php }?>
                                </td>
                            
                          <?php  $count++; if($count%2==0){echo "</tr><tr>";}
						  } ?>
                                
                        
						<?php } ?>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><input type="submit" value="Save" /></td>
                        </tr>
                    </table>
                    </form>
                    <br clear="all" />
                </div>
                <br />
                <div class="ui-widget-content" style="padding-bottom:8px;">
                <h4>Transactions</h4>
                <hr />
                <table width="100%">
					<tr>
						<td>Purchase Description</td>
						<td>Purchase Date</td>
						<td>Amount Paid</td>
					</tr>
					<?php while($row = mysql_fetch_array($getTransactions)){?>
						<tr>
							<td><?=$row['description']?></td>
							<td><?=date('n/j/Y',$row['datestamp'])?></td>
							<td>$<?=number_format($row['price'],2)?></td>
						</tr>
					<?php } ?>
				</table>
                <hr />
                
                <br clear="all" />
                </div>
                
                <br />
            </div>
        </div>
        
</div>
</body>
</html>

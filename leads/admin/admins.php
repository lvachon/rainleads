<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
	include 'head.php';
	
?>
<script>
function loadMore(current){
	$('#moreLink').html('<center><h4>Loading&hellip;</h4></center>');
	$.post('load-more.php',{
		current:current   
		},function(data){
		$('#userList').append(data);
	});
}
</script>
</head>

<body>
<div id="header">
    <img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;All Admins</h1>
    <p><a href="<?= $HOME_URL ?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
    <br clear="all" />
</div>
<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is your Administrator Administration Panel. Here you can manage the administrators of your social network.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4>Administrators (<?=count($ADMIN_IDS)?>)</h4>
                <div class="ui-widget-content">
                	
                    <br clear="all" />
                    <hr />
                    <table width="776" cellpadding="8" cellspacing="0" style="font-size:12px; font-weight:normal;" class="table-list" id="userList">
                    
                    	<tr style="font-weight:bold;">
                        	<td width="36"></td>
                        	<td align="left" width="150">Name</td>
                            <td align="left" width="150">Email Address</td>
                             <td align="center" width="100">Media</td>
                            <td align="center" width="160">Action</td>
                        </tr>
                        <?php 
							$cnt = 0;
							foreach($ADMIN_IDS as $id){
								$r = mysql_query("SELECT * From users where id=$id");
								$fuck = mysql_fetch_array($r);
								$user = new User($fuck['id']);
						?>
                   
                        <tr>
                        	<td width="36"><div style="width:26px; height:26px; overflow:hidden; background:#eee;"><?= $user->avatar(26,0,false) ?></div></td>
                        	<td align="left" width="150"><a href="view-user.php?id=<?= $user->id ?>"><?= $user->name(); ?></a></td>
                            <td align="left" width="150"><?= substr($user->email,0,48) ?></td>
                            <td align="center" width="120">
                            	<a href="user-photos.php?user=<?= $user->id ?>" class="tip" title="Photos"><img src="img/photo.png" /></a>&nbsp;&nbsp;&nbsp;
                                <a href="user-videos.php?user=<?= $user->id ?>" class="tip" title="Videos"><img src="img/video.png" /></a>&nbsp;&nbsp;&nbsp;
                                <a href="blogs.php?user=<?= $user->id ?>" class="tip" title="Blog"><img src="img/blog.png" /></a>
                            </td>
                            <td align="center">
                            	<?php if(in_array($user->id,$ADMIN_IDS)){?>
                                    <a href="users.php?kill=<?=$user->id?>" class="tip" original-title="Remove Admin"><img src="img/admin.png" title="remove admin" /></a>
								<?php }else{?>
                                    <a href="users.php?admin=<?=$user->id?>" class="tip" original-title="Make Admin"><img src="img/admin_off.png" title="make admin" /></a>
                            	<?php } ?>
                                &nbsp;
                                <?php if($user->isFeatured){?>
                                    <a href="javascript:none(0)" onclick="feature('user','<?= $user->id ?>')" class="tip" original-title="Un-Feature"><img id="f_user_<?= $user->id ?>" src="img/feature.png" title="un-feature" /></a>
								<?php }else{?>
                                    <a href="javascript:none(0)" onclick="feature('user','<?= $user->id ?>')" class="tip" original-title="Feature"><img id="f_user_<?= $user->id ?>" src="img/feature_off.png" title="feature" /></a>
                            	<?php } ?>
                                &nbsp;
                                <a href="<?= $HOME_URL ?>mail/send.php?id=<?=$user->id;?>" rel="facebox" class="tip" title="Send Message"><img src="img/email.png" /></a>&nbsp;
                                <a href="view-user.php?id=<?= $user->id ?>" class="tip" title="Edit User"><img src="img/edit.png" /></a>&nbsp;
                                <a href="javascript:none" onclick="deleteUser(<?= $user->id ?>)" class="tip" title="Delete User"><img src="img/delete.png" /></a>
                            </td>
                        </tr>
                        <?php $cnt++; } ?>                        
                    </table>
                   
                </div>
               <br />
            </div>
        </div>
        
</div>
</body>
</html>

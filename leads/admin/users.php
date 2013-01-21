<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
	include 'head.php';
	$qry = "users where 1";
	if(strlen($_GET['q'])){
		$q = mysql_escape_string($_GET['q']);
		$qry .= " AND (getData('fname',data) REGEXP '{$q}' or getData('lname',data) REGEXP '{$q}' OR email REGEXP '{$q}') ";
	}
	$page = intval($_GET['page']);
	if(!$page){$page=1;}
	$per_page=25;
	$offset = ($page-1)*$per_page;
	$target = "users.php?user={$_GET['user']}&sort={$_GET['sort']}&q={$_GET['q']}";
	if ($_GET['sort']=="name") {
		$sort = "ORDER BY getData('lname',data) ASC";
	} else if ($_GET['sort']=="user") {
		$sort = "ORDER BY getData('username',data) ASC";
	} else if ($_GET['sort']=="email") {
		$sort = "ORDER BY `email` ASC";
	} else {
		$sort = "ORDER BY `id` DESC";
	}
	$getUsers = mysql_query("SELECT * FROM $qry $sort LIMIT $offset, $per_page",$con);
	$getCount = mysql_query("SELECT COUNT(*) FROM $qry",$con);
	$count = mysql_fetch_array($getCount); $count = number_format(intval($count[0]),0,'.',',');
	$pgl = pageLinks($qry,$page,$per_page,$target);
	
	if(intval($_GET['admin'])){
		$newids = array();
		$newids = $ADMIN_IDS;
		$user = new User(intval($_GET['admin']));
		if($user->id && !in_array($user->id, $ADMIN_IDS)){
			array_push($newids, $user->id);
			$newids = implode(",",$newids);
			$f=fopen("adminids","w");
			fwrite($f, $newids);
			fclose($f);
			header("Location: users.php");
			die();
		}
	}
	
	if(intval($_GET['kill'])){
		$newids = array();
		foreach($ADMIN_IDS as $id){
			if(intval($id)>0 && intval($id)!=intval($_GET['kill'])){array_push($newids,$id);}
		}
		$newids = implode(",",$newids);
		$f=fopen("adminids","w");
		fwrite($f, $newids);
		fclose($f);
		header("Location: users.php");
		die();
	}
?>
<script>

function deleteUser(id){
	if(confirm("Do you really want to delete this user, this action cannot be undone")){
		document.location.href="killUser.php?id="+id;
	}
}
		
</script>
</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;All Users</h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				This is your User Administration Panel. Here you can manage the registered members of your social network.</p>
			</div>
		</div>
        <br />
        <div class="ui-widget">
        	<div class="ui-state-default ui-corner-all" style="padding: 0 .7em;"> 
				<h4>All Users (<?=$count?>) <a class="right" href="export-users.php">Export Users' Emails</a><div class="clear"></div></h4>
                <div class="ui-widget-content">
                	<form method='get' action="users.php" id='fff'>
	                	<table width="100%" cellpadding="5">
	                    	<tr>
	                        	<td>
	                        		<select name='sort' onchange='$("#fff").submit();'>
	                        			<option <?php if($_GET['sort']=="date"){echo "selected='selected'";} ?> value='date'>Newest</option>
	                        			<option <?php if($_GET['sort']=="name"){echo "selected='selected'";} ?> value='name'>Alphabetical (last name)</option>
	                        			<option <?php if($_GET['sort']=="email"){echo "selected='selected'";} ?> value='email'>Alphabetical (email)</option>
	                        		</select>
	                        	</td>
	                        	<td align="right"><input type="text" name="q" class="" value="<?=htmlentities($_GET['q']);?>" style="width:400px;" /></td>
                                <td><input type="submit" value="Search" /></td>
                            </tr>
                        </table>
                    </form>
                    <br clear="all" />
                    <hr />
                    <table width="776" cellpadding="8" cellspacing="0" style="font-size:12px; font-weight:normal;" class="table-list" id="userList">
                    
                    	<tr style="font-weight:bold;">
                        	<td width="36"></td>
                        	<td align="left" width="150">Name</td>
							<td>Account</td>
                            <td>Membership</td>
							<td>Date Joined</td>
                            <td align="left" width="150">Email Address</td>
                            <td align="center" width="160">Action</td>
                        </tr>
                        <?php 
							$cnt = 0;
							while($row = mysql_fetch_array($getUsers)){
								$user = new User($row['id']);
								$getMembership = mysql_query("SELECT * FROM membership WHERE user_id = {$user->id} ORDER BY id DESC",$con);
								$membership = mysql_fetch_array($getMembership);
								//var_dump($membership);
								$account = new Account($membership['account_id']);
							
						?>
                   
                        <tr>
                        	<td width="36"><div style="width:26px; height:26px; overflow:hidden; background:#eee;"><?= $user->avatar(26,0,false) ?></div></td>
                        	<td align="left" width="150"><a href="view-user.php?id=<?= $user->id ?>"><?= $user->name('F L') ?></a></td>
                            <td><a href="account.php?id=<?=$account->id?>"><?=$account->title?></a></td>
							<td><?=$membership['role']?></td>
							<td><?=date('n/j/Y',$row['datestamp']);?></td>
							<td align="left" width="150"><?= $row['email'] ?></td>
                            <td align="center">
                            	<?php if(in_array($user->id,$ADMIN_IDS)){?>
                                    <a href="users.php?kill=<?=$user->id?>" class="tip" original-title="Remove Admin"><img src="img/admin.png" title="remove admin" /></a>
								<?php }else{?>
                                    <a href="users.php?admin=<?=$user->id?>" class="tip" original-title="Make Admin"><img src="img/admin_off.png" title="make admin" /></a>
                            	<?php } ?>
                                &nbsp;
                                <a href="<?= $HOME_URL ?>mail/send.php?id=<?=$user->id;?>" rel="facebox" class="tip" title="Send Message"><img src="img/email.png" /></a>&nbsp;
                                <a href="view-user.php?id=<?= $user->id ?>" class="tip" title="Edit User"><img src="img/edit.png" /></a>&nbsp;
                                <a href="javascript:none" onclick="deleteUser(<?= $user->id ?>)" class="tip" title="Delete User"><img src="img/delete.png" /></a>
                            </td>
                        </tr>
                        <?php $cnt++; } ?>                        
                    </table>
                   <div id='paginatinon' class='pagination'/><?=$pgl;?></div>
                </div>
                
            </div>
        </div>
        
</div>
</body>
</html>

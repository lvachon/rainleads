<?php include '../includes/trois.php';
$con = conDB();
loginRequired();
$q = mysql_escape_string(strtolower($_POST['q']));
$u = new User(verCookie());
$friends = implode(',',$u->friends);
$getFriends = mysql_query("SELECT id FROM users WHERE lcase((getData('username',data)) REGEXP ('{$q}') OR getData('fname',data) REGEXP ('{$q}') OR getData('lname',data) REGEXP ('{$q}')) AND id IN ({$friends})",$con);
?>
<ul>
	<?php if(mysql_num_rows($getFriends)){
		while($f = mysql_fetch_array($getFriends)){ 
        $friend = new User($f['id']);?>
        <li onClick="$('#to_user').val('<?=$friend->id?>'),$('#search_user').val('<?=htmlentities(addslashes($friend->name()),ENT_QUOTES);?>'),$('#results').html(''),$('#results').hide();">
			<table><tr><td width="22"><?=$friend->avatar(16,16,false)?></td><td><?=trunc($friend->name(),50);?></td></tr></table>
        </li>
    	<?php }
	}else{?>
    	<li class="disabled">You have no contacts that match</li>
	<?php } ?>
</ul>    

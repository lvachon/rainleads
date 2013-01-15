<?php include_once '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if(count($account->members)>=$account->userLimit()){errorMsg("You have reached your user limit.  <a href='/account/updgrade.php'>Upgrade</a> your account.");die();}
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
if(strlen($_POST['emails'])){
	$con = conDB();
	$account = $viewer->getAccount();
	$emails = explode(',',$_POST['emails']);
	$count =0;
	foreach($emails as $email){
		$email = mysql_escape_string(trim($email));
		$key = md5($email."-".$account->id);
		mysql_query("INSERT INTO invites(account_id,email,invite_key,datestamp) VALUES({$account->id},'$email','$key',".time().")",$con);
		$err = mysql_error();
		if(strlen($err)){?>
			<script type="text/javascript">
				parent.$.fancybox('<h2>Invite Error</h2><div id="interior" style="width:350px;">Your invitation to <?=$email?> has not been sent for the following reason:<br />There has already been an invitation sent to <?=$email?>.</div>');
			</script>
		<?php die();}
		$mailVariables = array('%homeurl','%sitename','%receiver','%url','%email','%accountname');
		//changed to signupreal temporarily until we go live.
		$mailValues = array($HOME_URL,$SITE_NAME,$email,$HOME_URL."signup.php?key=".$key,$email,$account->title);
		$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_invite'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_invite')));
		htmlEmail($email,$subject,$message);
		$mailVariables = array('%homeurl','%sitename','%receiver','%email','%accountname','%domain');
		$mailValues = array($HOME_URL,$SITE_NAME,$viewer->name(),$email,$account->title,$HOME_URL);
		$sub = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_subuser'));
		$mes = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_subuser')));
		htmlEmail($viewer->email,$sub,$mes);
		$count++;
	}?>
	<script type="text/javascript">
		parent.$.fancybox('<h2><?=$count?> invitation<?php if($count != 1){?>s<?php } ?> sent.</h2>');
    </script>
<?php }
?>
<h2 class="left">Add Team Member</h2>
<div class="clear"></div>
<br/>
<div id="interior" style="width:400px; font-size:14px; overflow:hidden;">
	<form action="add-member.php" method="post" target="self">
    	<span class="strong">Email Addresses:</span><br />
    	<textarea type="text" name="emails" style="width:385px; border:1px solid #c0c0c0; padding:5px;"></textarea>
    	<small class="grey small"><center>Seperate emails with a comma.</center></small>
    	<br /><input type="submit" class="button blue_button right" value="Send Invitations" />
        <br clear="all" />
    </form>
    <iframe id="self" name="self" width="0" height="0" frameborder="0" style="visibility:hidden;"></iframe>
</div>
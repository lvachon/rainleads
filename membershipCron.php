<?php include 'inc/trois.php';
$con = conDB();
//this script is the cron job used to send out all of the automated messages that users receive
$getUsers = mysql_query("SELECT user_id,account_id FROM membership WHERE role = 'admin'", $con);
//emails arrays
while($row = mysql_fetch_array($getUsers)){
	$u = new User($row['user_id']);
	$account = new Account($row['account_id']);
	//if the user registered between 2 and 3 days ago
	if($u->datestamp >= time()-(2*86400) && $u->datestamp < time()-(3*86400)){
		$mailVariables = array('%homeurl','%sitename','%receiver','%url1','%url2','%email','%sender','%sender_email','%repphone');
		$mailValues = array($HOME_URL,$SITE_NAME,$user->name(),$HOME_URL."support/",$HOME_URL."tour.php",$user->email,'Richard Medeiros','richard@rainleads.com','(978)123-4567');
		$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_48hours'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_48hours')));
		htmlEmail($u->email,$subject,$message);
	}
	//if they are on their 25th day of registration and haven't purchased a membership
	if($u->datestamp >= time()-(25*86400) && $u->datestamp < time()-(26*86400) && !$account->paid){
		$mailVariables = array('%homeurl','%sitename','%receiver','%url');
		$mailValues = array($HOME_URL,$SITE_NAME,$user->name(),$HOME_URL."account/");
		$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_5day_warning'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_5day_warning')));
		htmlEmail($u->email,$subject,$message);
	}
	//if they are on their 31st day of registration and haven't purchased
	if($u->datestamp >= time()-(31*86400) && $u->datestamp < time()-(32*86400) && !$account->paid){
		$mailVariables = array('%homeurl','%sitename','%receiver','%url');
		$mailValues = array($HOME_URL,$SITE_NAME,$user->name(),$HOME_URL."account/");
		$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_trial_expired'));
		$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_trial_expired')));
		htmlEmail($u->email,$subject,$message);
	}
}?>
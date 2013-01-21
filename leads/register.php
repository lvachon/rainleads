<?php session_start();
include 'inc/trois.php';
//new captcha
include_once $HOME_DIR . '/captcha/securimage.php';

$securimage = new Securimage();
if(strlen($_POST['captcha_code'])){
	if ($securimage->check($_POST['captcha_code']) == false) {
		errorMsg("You have entered the security code incorrectly, please try again");die();
	}
}else{
	errorMsg("You must complete the security code, please try again");die();
}



$inv = array();
if(!strlen($_POST['fname'])){$inv[]="Your first name is blank";}
if(!strlen($_POST['lname'])){$inv[]="Your last name is blank";}
if($_POST['pass1']!=$_POST['pass2'] || strlen($_POST['pass1'])<5){$inv[]="Your passwords do not match or are blank";}
if(!intval($_POST['terms'])){$inv[]="You did not agree to the terms";}
if(count($inv)){
	errorMsg(implode("<br/>",$inv),true);
}


/* 
 * If you're still here that means that everything is valid and its:
 * DATABASE TIME
 */
$con = conDB();
//Insert the only two manually controlled columns: email and password

//Take that new row and make a user out of it, flesh out the details and save


//we need to check if the user was invited here by comparing the key if they are we don't need to add an account
if(strlen($_POST['key'])){
	$key = mysql_escape_string($_POST['key']);//key = invitees email-account_id
	$getInvite = mysql_query("SELECT * FROM invites WHERE `invite_key` = '$key'",$con) or die(mysql_error());
	$invite = mysql_fetch_array($getInvite);
	if(intval($invite['account_id'])){
		$role = 'team member';
		$ac_id = intval($invite['account_id']);
		$account = new Account($ac_id);
		$subdomain = $account->subdomain;
		mysql_query("DELETE FROM invites WHERE id = {$invite['id']}",$con);
	}else{
		errorMsg("Invalid invite key.");
		//die(var_dump($invite));
	}
	$r = mysql_query("SELECT * from users where lcase(email)=lcase('".mysql_escape_string($invite['email'])."')",$con);
	$u = mysql_fetch_array($r);
	if(!intval($u['id'])){//no user found make a new one
		$email = $invite['email'];
		$pass = mysql_escape_string(md5("Mountain Dew has 100mg of sodium".$_POST['pass1']));
		$datestamp = time();
		$r = mysql_query("INSERT INTO users(email,password,datestamp) VALUES('$email','$pass',$datestamp)",$con)or die(mysql_error());
		$id = intval(mysql_insert_id($con));
		if(!$id){errorMsg("Something went wrong trying to save your email/password");}
		
		$user = new User($id);
		$user->data['fname']=$_POST['fname'];
		$user->data['lname']=$_POST['lname'];
		$user->save();	
		
	}else{//user found init object
		$user = new User($u['id']);
	}
	//now we have a user no matter what
	$type = "subuser";
	mysql_query("INSERT INTO membership(`user_id`,`account_id`,`datestamp`,`role`) VALUES({$user->id},$ac_id,unix_timestamp(),'$role')",$con);//associate them with the account
	$HOME_URL = str_replace('www.',$account->subdomain.'.',$HOME_URL);//set subdomain in homeurl
	$user->login();//login the user
	header("Location: {$HOME_URL}account/dashboard.php");//send them on their way
	die();//stop the rest of the script from running

}else{//no key, so we need to make a new account and a new user
	
	$email = mysql_escape_string(strtolower($_POST['email1']));
	$pass = mysql_escape_string(md5("Mountain Dew has 100mg of sodium".$_POST['pass1']));
	$datestamp = time();
	$r = mysql_query("INSERT INTO users(email,password,datestamp) VALUES('$email','$pass',$datestamp)",$con)or die(mysql_error());
	$id = intval(mysql_insert_id($con));
	if(!$id){errorMsg("Something went wrong trying to save your email/password");}
	
	$user = new User($id);
	$user->data['fname']=$_POST['fname'];
	$user->data['lname']=$_POST['lname'];
	$user->save();
	
	$title = mysql_escape_string($_POST['co_name']);
	$subdomain = mysql_escape_string($_POST['subdomain']);
	mysql_query("INSERT INTO accounts(`title`,`user_id`,`datestamp`,`subdomain`,expires) VALUES('$title',{$id},$datestamp,'$subdomain',unix_timestamp()+86400*30)",$con) or die(mysql_error());
	$ac_id = mysql_insert_id($con);
	$role = 'admin';
	$type = "new account";
	if(intval($ac_id)){
		//DEFAULT STATUSES
		mysql_query("INSERT INTO statuses(`title`,`account_id`,`color`,`display`) VALUES('New',$ac_id,'color_3',0)",$con);
		mysql_query("INSERT INTO statuses(`title`,`account_id`,`color`,`display`) VALUES('Won',$ac_id,'color_1',1)",$con);
		mysql_query("INSERT INTO statuses(`title`,`account_id`,`color`,`display`) VALUES('Warm',$ac_id,'color_5',2)",$con);
		$warm_id = mysql_insert_id($con);
		mysql_query("INSERT INTO statuses(`title`,`account_id`,`color`,`display`) VALUES('Hot',$ac_id,'color_2',3)",$con);
		mysql_query("INSERT INTO statuses(`title`,`account_id`,`color`,`display`) VALUES('Dead',$ac_id,'color_6',4)",$con);
		mysql_query("INSERT INTO milestones(`title`,`account_id`,`color`,`display`) VALUES('Call Back',$ac_id,'color_1',0)",$con);
		$name = mysql_escape_string('Hingle McCringleberry');
		$email = mysql_escape_string('hmcringleberry@mccringleberry.com');
		mysql_query("INSERT INTO form_results(`name`,`email`,`status`,display_id) VALUES('$name','$email',$warm_id,0)",$con);
	}
	$account = new Account($ac_id);
}

//we need to send an email here to either verify their email or send them their password etc..
////
$mailVariables = array('%homeurl','%sitename','%receiver','%url1','%url2','%email','%password');
$mailValues = array($HOME_URL,$SITE_NAME,$user->name(),$HOME_URL."tour.php",$HOME_URL."support/",$user->email,$_POST['pass1']);
$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_welcome'));
$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_welcome')));
htmlEmail($user->email,$subject,$message);

if($type == 'subuser'){
	$mailVariables = array('%homeurl','%sitename','%receiver','%email','%post');
	$mailValues = array($HOME_URL,$SITE_NAME,$account->getUser()->name(),$user->email,$_POST['pass1']);
	$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_new_member'));
	$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_new_member')));
	htmlEmail($account->getUser()->email,$subject,$message);
}
mysql_query("INSERT INTO membership(`user_id`,`account_id`,`datestamp`,`role`) VALUES($id,$ac_id,$datestamp,'$role')",$con) or die(mysql_error()."INSERT INTO membership(`user_id`,`account_id`,`datestamp`,`role`) VALUES($id,$ac_id,$datestamp,'$role'") or die(mysql_error());
$HOME_URL = str_replace('www.',$_POST['subdomain'].".",$HOME_URL);

//echo $HOME_URL."account/";
header("Location: {$HOME_URL}account/login.php?id={$user->id}&plan=".intval($_POST['plan']));
die();																																																									
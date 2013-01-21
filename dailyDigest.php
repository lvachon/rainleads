<?php include 'inc/trois.php';
$con = conDB();
//this script is the cron job used to send out all of the automated messages that users receive
$getUsers = mysql_query("SELECT user_id,account_id FROM membership WHERE role = 'admin'", $con) or die(mysql_error());
//emails arrays
while($row = mysql_fetch_array($getUsers)){
	$u = new User($row['user_id']);
	$account = new Account($row['account_id']);
	$post = "";
	//get all activity within the last 24 hours if they want the email 
	if($account->data['daily_digest'] != '0'){
		$thresh =  time()-86400;
		$getActivity = mysql_query("SELECT * FROM actions WHERE datestamp > $thresh AND account_id = {$account->id} ORDER BY datestamp DESC",$con) or die(mysql_error());
		$actCount = 0;
		while($act = mysql_fetch_array($getActivity)){
			$use = new User($act['user_id']);
			$url_parts = explode('.',$HOME_URL);
			$url_parts[0] = "https://".$account->subdomain;
			$HOME_URL = implode('.',$url_parts);
			//$HOME_URL = str_replace('www.',$account->subdomain.".",$HOME_URL);
			include 'wall/types.php';
			//die(var_dump($act));
			$post.="<table cellpadding='2' width='100%'>
				<tr>
					<td rowspan='3' width='30'>{$use->avatar(24,24)}</td>
					<td valign='top'><span style='font-weight:bold;'>{$use->name()}</span> {$action}</td>
				</tr>
				<tr>
					<td style='font-size:13px;color:#666;'>{$detail} {$link}</td>
				</tr>
				<tr>
					<td>
						<div style='float:left;'><small style='font-size: 11px;color: #8A8A8A;'>". date('n/j/Y \a\t g:ia',$act['datestamp']) ."</small></div>
						<div style='clear:both;'></div>
					</td>
				</tr>
			</table>";
			$actCount++;
			
		}
		if($actCount > 0){
			$HOME_URL = "http://www.rainleads.com/";
			$mailVariables = array('%homeurl','%sitename','%receiver','%url','%post','%accountname');
			$mailValues = array($HOME_URL,$SITE_NAME,$u->name(),$HOME_URL."account/",$post,$account->title);
			$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_daily_digest'));
			$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_daily_digest')));
			echo $message;
			htmlEmail($u->email,$subject,$message);
		}
	}
}?>
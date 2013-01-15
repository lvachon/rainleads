<?php
ob_start();
include('inc/trois.php');

loginRequired();
$account = $viewer->getAccount();
	
$fail=false;
$con = conDB();

$FACEBOOK_APP_ID= '346873902077931';
$FACEBOOK_SECRET='cd6d57968fae9234cee39d000c454b05';

$app_id = $FACEBOOK_APP_ID;
   $app_secret = $FACEBOOK_SECRET;
   $my_url = $HOME_URL."facebook.php";

   session_start();
   $code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
     $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'];

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }

   if($_REQUEST['state'] == $_SESSION['state']) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = @file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $token = $_GET['token'];
	$account->data['fb_token'] = $params['access_token'];
	$account->save();
	header("Location: http://www.facebook.com/dialog/pagetab?app_id=346873902077931&next=http://{$account->subdomain}.rainleads.com/forms/view-facebook.php");
   	 
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }
?>
				

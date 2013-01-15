<?php include '../includes/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
$con = conDB();
$mailVariables = array('%homeurl','%sitename','%receiver');
$mailValues = array($HOME_URL,$SITE_NAME,$user->name());
$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_delete_account'));
$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_delete_account')));
htmlEmail($viewer->email,$subject,$message);
$viewer->delete();
header("Location: ".$HOME_URL);die();
?>

<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = $viewer->getAccount();
$members = implode(',',$_POST['members']);
$thanks = $_POST['thank_you'];
$id = intval($_POST['id']);
$new = false;
$form = new Form($id);
if($form->account_id!=$account->id){errorMsg("This form does not belong to the current account");die();}
if(!strlen($form->data['members'])){
	$new = true;
}
$form->data['members'] = $members;
$form->thankyou = $thanks;
$form->save();
if(count($account->forms()) == 1 && $new){
	$mailVariables = array('%homeurl','%sitename','%receiver','%url','%sender','%sender_email','%repphone');
	$mailValues = array($HOME_URL,$SITE_NAME,$viewer->name(),$HOME_URL."support/",'Richard Medeiros','richard@rainleads.com','(978)123-4567');
	$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_first_form'));
	$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_first_form')));
	htmlEmail($viewer->email,$subject,$message);
} 
if($_POST['embed'] == 1){
	header("Location: {$HOME_URL}forms/formcode.php?id={$id}");die();
}else{
	header("Location: {$HOME_URL}forms/index.php");die();
}?>

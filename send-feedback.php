<?php include 'inc/trois.php';
$con = conDB();
//$submitFlag = mysql_query("INSERT INTO feedback (`time`,`subject`,`msg`,`user_id`,`email`) VALUES ('".time()."','".mysql_escape_string($_POST['subject'])."','".mysql_escape_string($_POST['msg'])."','".verCookie()."','".mysql_escape_string($_POST['email'])."')",$con);
//echo mysql_error();
$email = $_POST['email'];
$msg = $_POST['msg'];
$subject = $_POST['subject'];
$datestamp = date('n/d/Y \a\t g:iA',time());
$message = "SENT FROM: {$email} on {$datestamp}<br /><br />".$msg;
htmlEmail($SITE_CONTACT,$subject,$message);
header("Location: {$HOME_URL}contact.php?msg=Feedback Submitted");?>

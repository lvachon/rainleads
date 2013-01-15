<?php include_once '../inc/trois.php';
loginRequired();
$acct = new Account(verAccount());
if(intval($_POST['form_id'])){
	$f = new Form($_POST['form_id']);
	if($f->account_id!=$acct->id){die("This form does not belong to the current account {$f->account_id}=={$acct->id}");}
	$email = $_POST['email'];
	if(strlen($_POST['instructions'])){
		$instructions = "They have provided the following instructions <br /><br />".nl2br($_POST['instructions']);
	}else{
		$instructions = "";
	}
	$post = "<strong>IFrame:</strong><br />";	
	$post.= htmlentities('<iframe src="https://www.rainleads.com/forms/showform.php?id='.$f->id.'&code='.urlencode($f->data['title']).'_iframe" width="'.max(intval($f->data['width']),400).'" height="600" frameborder=0></iframe>');
	$post.="<br /><br /><strong>JS Request</strong><br />";
	$post.= htmlentities('<div id="rainleads_ajax_request_div"></div><script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script><script>$.get("https://pearse.rainleads.com/forms/showform.php?id='.$f->id.'&code='.urlencode($f->data['title']).'",function(data){$("#rainleads_ajax_request_div").html(data);});</script>');	
	$mailVariables = array('%homeurl','%sitename','%receiver','%sender','%post','%instructions');
	$mailValues = array($HOME_URL,$SITE_NAME,$email,$viewer->name(),$post,$instructions);
	$subject = str_replace($mailVariables,$mailValues,cmsTitle('RTFemail_send_embed'));
	$message = str_replace($mailVariables,$mailValues,nl2br(cmsRead('RTFemail_send_embed')));
	htmlEmail($email,$subject,$message);?>
    <script type="text/javascript">parent.$.fancybox('<h2>Code Sent</h2>');</script>
<?php die();}else{
	$f = new Form($_GET['id']);
}
if($f->account_id!=$acct->id){die("This form does not belong to the current account {$f->account_id}=={$acct->id}");}?>
<h2>Send Embed Code</h2>
<hr />
<div class="interior" style="width:350px;">
    <form method="post" action="email-code.php" target="hidn">
        <input type="hidden" name="form_id" value="<?=$f->id?>" />
        <strong>Email</strong><br />
        <input type="email" name="email" style="width:340px;" /><br />
        <strong>Instructions</strong><br />
        <textarea name="instructions" style="width:340px;height:150px;">Please add this contact form to my website.</textarea><br />
        <input type="submit" class="right button" value="Send" />
    </form>
    <iframe id="hidn" name="hidn" frameborder="0" width="0" height="0"></iframe>
</div>
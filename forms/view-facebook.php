<?php include '../inc/trois.php';

function parse_signed_request($signed_request) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

$sr = parse_signed_request($_REQUEST['signed_request']);
if($sr){
	//Being viewed from Facebook
	$con = conDB();
	$id = $sr{'page'}{'id'};
	$get = mysql_query("SELECT * FROM facebook_pages WHERE page_id='".mysql_escape_string($id)."'",$con);
	$row = mysql_fetch_array($get);
	$form = new Form($row['form_id']);
	
	?>
	<?= $form->getHTML(false,"fb",false) ?>
	<style>.reqmissing{border:solid 1px #D64A4A;}</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script><?=$form->getValidationJS();?></script>
	<?php
}else{
	loginRequired();
	accountRequired();
	$account = $viewer->getAccount();
	$con = conDB();
	$pages = array();
	
	foreach(array_keys($_REQUEST['tabs_added']) as $page){
		$count = 0;
		$check = mysql_query("SELECT * FROM facebook_pages WHERE page_id='".mysql_escape_string($page)."'",$con);
		$count = mysql_num_rows($check);
		if($count ==0){
			$add = mysql_query("INSERT into facebook_pages (`account_id`,`page_id`) VALUES ('{$account->id}','".mysql_escape_string($page)."')",$con);
		}
		
	}
	if($count ==0){
		header("Location:edit-facebook.php");
	}else{
		header("Location:edit-facebook.php?msg=This Facebook Page has already been linked to another account!");	
	}
}
?>
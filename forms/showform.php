<?php include '../inc/trois.php';
$id = intval($_GET['id']);
if(!$id){die("No form");}
$form = new Form($id);
if(!intval($form->id)){die("No form");}
echo $form->getHTML(false,$_GET['code'],isset($_GET['manual']));
$con = conDB();
if(!isset($_GET['manual'])){
	$r = mysql_query("INSERT INTO form_impressions(form_id,datestamp,referer,ip,tracking_code) VALUES({$form->id},unix_timestamp(),\"".mysql_escape_string($_SERVER['HTTP_REFERER'])."\",\"".mysql_escape_string($_SERVER['REMOTE_ADDR'])."\",\"".mysql_escape_string($_GET['code'])."\")",$con);
echo mysql_error();
}
?>
<style>.reqmissing{border:solid 1px #D64A4A;}</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script><?=$form->getValidationJS();?></script>

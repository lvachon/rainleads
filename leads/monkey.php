<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>
	a=false;
</script>
<iframe id='ifr' name='ifr' src='about:blank' onload='if(a){history.go(0);}else{a=true;}'></iframe>
<?php
include 'inc/trois.php';
$con = conDB();
$r = mysql_query("SELECT id from forms order by rand() limit 1",$con);
$f = mysql_fetch_array($r);
$form = new Form($f['id']);
echo $form->getHTML(false,"monkey",true);
?>
<script>

	$("input,select,textarea").val("monkey");
	$("form").attr("target","ifr");
	$("form").submit();	
</script>

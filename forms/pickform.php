<?php include '../inc/trois.php';
$account = new Account(verAccount());
$con = conDB();
$r = mysql_query("SELECT * from forms where account_id = {$account->id} and deleted=0",$con);
if(intval(mysql_num_rows($r))==1){
	$f = mysql_fetch_array($r);
	header("Location: showform.php?manual=1&id={$f['id']}&fancy={$_GET['fancy']}");
	die();
}
if(intval(mysql_num_rows($r))==0){
	?><div style='width:320px;heigt:240px;'>You have no forms to create a lead with.  <a href='create-form.php' target="_parent">Make one.</a></div>
	<?php
	die();
}
echo "<div style='width:240px;' id='clead_content'>";
echo "Choose which form you wish to create the lead with:<br/>";
echo "<select name='id' id='fselect' style='min-width:200px;'>";
while($f = mysql_fetch_array($r)){
	$form = new Form($f['id']);
	echo "<option value='{$form->id}'>".htmlentities($form->data['title'])."</option>";
}
echo "</select>\n";
echo "<input type='submit' value='OK' onclick='$.get(\"/forms/showform.php?manual=1&id=\"+$(\"#fselect\").val(),function(data)".'{$'.".fancybox(data);});' />\n";
echo "</div >";
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
	?><div style='width:320px;heigt:240px;'>You have no forms to create a lead with.  <a href='/forms/create-form.php' target="_parent">Create one.</a></div>
	<?php
	die();
}
echo "<div style='width:300px;' id='clead_content'>";
echo "<h1>Create a Lead</h1><br clear='all' /><br/>Choose a Form:<br/>";
echo "<select style='padding:10px; height:26px; float:left; font-size:14px; min-width:200px; margin:3px;' name='id' id='fselect'>";
while($f = mysql_fetch_array($r)){
	$form = new Form($f['id']);
	echo "<option value='{$form->id}'>".htmlentities($form->data['title'])."</option>";
}
echo "</select>";
echo "<input type='submit' class='button' style='padding:5px 15px;' value='OK' onclick='$.get(\"/forms/showform.php?manual=1&id=\"+$(\"#fselect\").val(),function(data)".'{$'.".fancybox(data);});' />\n";
echo "</div >";
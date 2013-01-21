<?php include '../inc/trois.php';
$account = new Account(verAccount());
$con = conDB();
$r = mysql_query("SELECT * from forms where account_id = {$account->id} and deleted=0",$con);
echo "<div style='width:240px;'>";
echo "Choose which form you wish to create the lead with:<br/>";
echo "<form method='get' action='showform.php'>\n";
echo "<input type='hidden' name='manual' value='1'/>\n";
echo "<select name='id' id='fselect' style='min-width:200px;'>";
while($f = mysql_fetch_array($r)){
	$form = new Form($f['id']);
	echo "<option value='{$form->id}'>".htmlentities($form->data['title'])."</option>";
}
echo "</select>\n";
echo "<input type='submit' value='OK'/><form>\n";
echo "</div >";
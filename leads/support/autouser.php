<?php include '../includes/trois.php';
$con = conDB();
$q = mysql_escape_string($_GET['q']);
$r = mysql_query("SELECT * from users where getData('username',data) regexp '^{$q}.*' or getData('fname',data) regexp '{$q}' or getData('lname', data) regexp '{$q}' order by length(getData('username',data)) asc LIMIT 1",$con);
while($u = mysql_fetch_array($r)){
	$user = new User($u['id']);
	echo $user->id.";".$user->name()."\n";
}

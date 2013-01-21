<?php include 'inc/trois.php';
if(verCookie()){
	$user = new User(verCookie());
}
if($_POST['email']){
	$con = conDB();
	$r = mysql_query("SELECT * from users where lcase(email)=lcase('".mysql_escape_string($_POST['email'])."') LIMIT 1",$con);
	$u = mysql_fetch_array($r);
	$user = new User($u['id']);
}

if($user->id){
	$r = mysql_query("SELECT subdomain from accounts where id IN (SELECT account_id from membership where user_id={$user->id})",$con);
	while($s=mysql_fetch_array($r)){?>
		<a href='https://<?=$s[0].'.'.$DOMAIN;?>'><?=$s[0];?></a><br/>
	<?php }
	die(); 
}else{
	?>
	Use this form to find the subdomains associated with the accounts you are a member of.
	<div id='findsubdiv'>
		Email:<input type='text' id='email' name='email'/>
		<input type='button' value='Search' onclick='$.post("findsubdomain.php",{"email":$("#email").val()},function(data){$("#findsubdiv").html(data);});'/>
	</div>

<?php } 

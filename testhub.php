<?php include 'inc/trois.php';
loginRequired();
$user = new User(verCookie());
$con = conDB();
$r = mysql_query("SELECT * from membership where user_id={$user->id}",$con);
$m = mysql_fetch_array($r);
$acct = verAccount();
if($m['role']=='admin'){
	$forms = mysql_query("SELECT * from forms where account_id = $acct order by datestamp desc",$con);
}else{
	$forms = mysql_query("SELECT * from forms where account_id = $acct and id in (SELECT result_id from assignments where user_id={$user->id}) order by datestamp desc",$con);
}

?>
<a href='forms/create-form.php'>New form</a><br/><br/>

<?php while($f = mysql_fetch_array($forms)){?>
	<b>Form #<?=$f['id'];?></b><br/>
	<a href='forms/create-form.php?id=<?=$f['id'];?>'>Edit fields</a><br/>
	<a href='forms/edit-form.php?id=<?=$f['id'];?>'>Edit style</a><br/>
	<a href='forms/showform.php?id=<?=$f['id'];?>'>Show output</a><br/>
	Code:<br/>
	<textarea>
	<iframe src='http://mocircles.com/forms/showform.php?id=<?=$f['id'];?>' width='400' height='600'></iframe>
	</textarea><br/><br/>
	<?php
	$res = mysql_query("SELECT * from form_results where form_id={$f['id']} order by datestamp desc",$con);
	if(mysql_num_rows($res)){?>
		<div style='padding-left:10px;'>
		<b>Results</b><br/>
		<?php
		while($r = mysql_fetch_array($res)){?>
			<a href='forms/lead.php?id=<?=$r['id'];?>'>Result #<?=$r['id'];?></a><br/><?php 
		}?>
		</div>
	<?php } ?>
	
	<hr/>
	<?php
}?>





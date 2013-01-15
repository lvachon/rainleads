<?php include '../inc/trois.php';
loginRequired();
if(!verAccount()){errorMsg("No account associated with login");}
$account = new Account(verAccount());
$con = conDB();
?>
<!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
    	<h1>Lead Statistics</h1>
    	<div class="segmented_tabs">
			<div class="segment left active">
				User Totals
			</div>
			<div class="segment right">
				Team Totals
			</div>
		</div>
    	<div class="clear"></div>
    	<br/>
		
		
		
		
	</div>
</div>
</body>
</html>

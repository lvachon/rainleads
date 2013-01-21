<?php include('../inc/trois.php');
loginRequired();
$account = new Account(verAccount());
?><!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
        <h1>All Activity</h1>
        <div class="clear"></div>
        <div id="activity" class="scrollbar1"> 
			<?php include 'wall.php'; echo getActivity('all',0);?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('../inc/footer.php'); ?>
</body>
</html>

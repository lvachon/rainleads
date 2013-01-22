<!DOCTYPE html>
<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}

$things = array(array("name"=>"+100MB storage","price"=>5,"tx"=>"storage"),array("name"=>"+1 User account","price"=>12,"tx"=>"user"));
?>
<html>
<?php include('../inc/head.php'); ?>
<body>
<?php include('../inc/header.php'); ?>
<style>
	.empty{
		background:#D7F0FF;
		border-color:#659ABC !important;;
	}
</style>
<div id="content" class="inner">
	<div id="side" class="left">
		<?php include('../inc/sidenav.php') ?>
	</div>
	<div id="main" class="left">
		
		<div id='chooseclass'>
			<h2>Checkout:</h2>
			One user account:  $12.00/mo
			<hr class='title_line'/>
			<form method='post' action='pay.php' id='buyform'>
				<?php include '../inc/billing_form.php';?>
				<input type='submit' value='Subscribe'/> 
			</form>
		</div>
	 </div>
	<div class="clear"></div>
</div>
<script>
$(function() {
  $('form input.suggested').each(function(data){
  		if($(this).val() == ''){
  			if($(this).attr('type') !='file'){
	  			$(this).addClass('empty');
	  		}
  		}
  });
});
</script>
<?php include('../inc/footer.php'); ?>
</body>
</html>

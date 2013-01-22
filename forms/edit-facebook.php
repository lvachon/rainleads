<?php include '../inc/trois.php';
$con = conDB();
loginRequired();
accountRequired();
$account = new Account(verAccount());

$fb_token = $account->data['fb_token'];

$get = mysql_query("SELECT * FROM facebook_pages WHERE account_id='{$account->id}'",$con);
$pages = json_decode(file_get_contents("https://graph.facebook.com/me/accounts?access_token=".$account->data['fb_token']));
//echo mysql_error();
$count = mysql_num_rows($get);
//var_dump($pages);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<?php include('../inc/head.php'); ?>
</head>

<body>
    <?php include('../inc/header.php') ?>
    <div id="content" class="inner">
	    <div id="side" class="left">
	    	<?php include('../inc/sidenav.php') ?>
	    </div>
	    <div id="main" class="left">
	    	<h1>Facebook Forms</h1>            
            <div class="clear"></div>
            <p style="font-size:14px; margin:0px 0 15px 0;">Use our exclusive Facebook app to allow your fans submit leads directly from your Facebook Fan Page.</p>
            <br/>
           
                      
            <?php if(isset($pages)){?>
	            <?php if($count>0){?>   
	            <strong>Facebook Pages Connected</strong>
	            <div class="facebook_area">           
	            <?php
	            	$con = conDB();
	            	$gf = mysql_query("SELECT * from forms where account_id = {$account->id} and deleted=0 order by datestamp desc",$con);
	            	$forms = array();
					while ($row = mysql_fetch_array($gf)) {
					    $forms[] = $row;
					}
	            	while($row = mysql_fetch_array($get)){
	            		$page = json_decode(file_get_contents("https://graph.facebook.com/".$row['page_id'].'?fields=picture,name,link'));	            		
	            		$tabs = json_decode(file_get_contents("https://graph.facebook.com/".$row['page_id']."/tabs/346873902077931?access_token=".$account->data['fb_token']));
	            		
	            		$link = $tabs->{'data'}[0]->{'link'};
	            		$name = $page->{'name'};
	            		if ($name == ''){$name = "Unpublished Page";}
	            		
	            			            		
	            	?>
	            	
	            	<div class="fb_page">
	            		<table width="100%">
	            			<tr valign="top">
	            				<td rowspan="4" width="55"><img src="<?= $page->{"picture"}->{'data'}->{'url'} ?>" /></td>
	            				<td align="left" valign="top"><a href="<?= $link ?>" target="_blank"><strong class="blue"><?= $name ?></strong></a></td>
	            			</tr>
	            			<tr>
	            				<td><small>Choose a form to display on this page:</small></td>
	            			</tr>
	            			<tr>
	            				<td>
	            					<select name="form_id" id="form_<?=$row['id'] ?>" onchange="setFacebookForm(<?= $row['id'] ?>,<?= $page->{'id'} ?>)">
	            						<optgroup label="Contact Forms">
	            							<option value="--">Choose a form</option>
			            					<?php foreach($forms as $r){
								    			$form = new Form($r['id']);
								    		?>
								    			<option value="<?= $form->id ?>" <?php if($row['form_id']==$r['id']){echo "selected";}?>><?=$form->data['title'];?></option>
								    		<?php } ?>
							    		</optgroup>
						    		</select>
	            				</td>
	            			</tr>
	            		</table>
	            	</div>
	            <?php } ?>
	            </div>
	            <center>
	            
	            <div onClick="document.location.href='http://www.facebook.com/dialog/pagetab?app_id=346873902077931&next=http://<?= $account->subdomain ?>.rainleads.com/forms/view-facebook.php'" class="button_outside_border_blue">
						<div class="button_inside_border_blue">
							Add RainLeads to another Page.
						</div>
					</div>
	            </center>
	            <?php }else{ ?>
	            <br/><br/>
				<h3>Add a Contact Form to your Facebook Fan Page!</h3>
				
				<center>
					<div onClick="document.location.href='http://www.facebook.com/dialog/pagetab?app_id=346873902077931&next=http://<?= $account->subdomain ?>.rainleads.com/forms/view-facebook.php'" class="button_outside_border_blue">
						<div class="button_inside_border_blue">
							Add RainLeads to Facebook!
						</div>
					</div>
				</center>
				<?php } ?>
			<?php }else{?>
				<h3>Link Your Facebook and RainLeads Accounts!</h3>
				<?php
				
					$x="login();";
				//removed membership type check, that's why this is built so damn weird.
				?>
				<center>
					<div onClick="<?=$x;?>" class="button_outside_border_blue">
						<div class="button_inside_border_blue">
							Authorize Facebook
						</div>
					</div>
				</center>
			<?php } ?>
			
	    </div>
	    <div class="clear"></div>
	</div>
	<script>
		function setFacebookForm(row,page){
			var id = $("select#form_"+row).val();
			$.post('set-facebook-form.php',{row:row,page:page,id:id},function(data){
				alert('success');
			})
		}
	</script>
	<div class="clear"></div>
	</div>
    <?php include('../inc/footer.php') ?>
</body>
</html>

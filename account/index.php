<!DOCTYPE html>
<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = $viewer->getAccount();?>
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
        <div id="admin_settings">
            <h1>My Account</h1>  
            <br/>          
            <br clear="all"/>
            <h2 class="left">Personal Information</h2>
            <hr class="title_line" />
            <div class="clear"></div>
            <form action="save-user.php" method="post">
                
                    <table style="font-size:13px;" id="settings_table">
                        <tr valign="top">
                            <td rowspan="2" style="width:86px; position:relative; height:86px;">
                                <a href="javascript:void(0);"  class="tip" title="Change Picture" style="display:block;" onclick="$.post('<?=$HOME_URL?>avatar/upload.php',function(data){$.fancybox(data);});"><?=$viewer->avatar(80,80,false)?></a><br />
                            </td>
                            <td colspan="2" valign="top"><label>First Name:</label><br/><input type="text" name="fname" value="<?=$viewer->data['fname']?>" style="width:200px;" /><br/><br/></td>
                            
                            <td valign="top" colspan="2"><label>Last Name:</label><br/><input type="text" name="lname" value="<?=$viewer->data['lname']?>" style="width:200px;" /></td>
                        </tr>
                        <?php if(!in_array($viewer->id, $account->admins)){?>
	                        <tr>
	                        	<td align="right"><label>Only show leads assigned to me <input type='checkbox' name='onlymyleads' value='1' <?php if(intval($viewer->data['onlymyleads'])){echo "checked";}?>/></td>
	                        </tr>
                        <?php } ?>
                        <tr>
                            <td valign="top"><a href="javascript:void(0);" onclick="$.post('<?=$HOME_URL?>account/password.php',function(data){$.fancybox(data);});" style="font-size:11px;">Reset Password</a>
                                &middot; <a href="javascript:void(0);" onclick="$.post('<?=$HOME_URL?>account/delete.php',function(data){$.fancybox(data);});" style="font-size:11px;">Delete Profile</a></td>
                            <td colspan="2" align="right"><input type="submit" value="Save Personal Information" class="button blue_button" /><br class="clear" /></td>
                        </tr>
                    </table>
                
            </form>
			<?php if($viewer->id == $account->user_id){?>
				<br/>
                <h2 class="left">Company Information</h2>
                <hr class="title_line" />
                <div class="clear"></div>
                <form action="save-account.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$account->id?>" />
                    <table style="font-size:14px; color:#888; display:block; margin:auto;" id="settings_table" cellpadding="8">
                        <tr>                            
                            <td align="right"><label>Title:</label></td><td><input type="text" class="suggested" name="title" value="<?=$account->title;?>" style="width:200px;" /></td>
                            <td align="right"><label>Subdomain:</label></td><td><input type="text" class="suggested"  name="subdomain" value="<?=$account->subdomain?>" style="width:200px;" disabled /></td>
                        </tr>
                        <tr>
                            <td align="right"><label>Phone:</label></td><td><input type="text" class="suggested"  name="phone" value="<?=$account->data['phone'];?>" style="width:200px;" /></td>
                            <td align="right"><label>Fax:</label></td><td><input type="text" class="suggested"  name="fax" value="<?=$account->data['fax'];?>" style="width:200px;" /></td>
                        </tr>
                        <tr>
                        	<td align="right"><label>Website:</label></td>
                        	<td><input type="text" name="website" value="<?=$account->data['website'];?>" style="width:200px;" /></td>
                        	<td align="right"><label>Industry:</label></td>
                        	<?php $industries = array('Agriculture','Accounting','Advertising','Aerospace','Aircraft','Airline','Apparel & Accessories','Automotive','Banking','Broadcasting','Brokerage','Biotechnology','Call Centers','Cargo Handling','Chemical','Computer','Consulting','Consumer Products','Cosmetics','Defense',
							'Department Stores','Education','Electronics','Energy','Entertainment & Leisure','Executive Search','Financial Services','Food, Beverage & Tobacco','Grocery',
							'Health Care','Hospitality','Internet Publishing','Insurance','Investment Banking','Legal','Manufacturing','Motion Picture & Video','Music','Newspaper Publishers',
							'Non-Profit','Online Auctions','Pharmaceuticals','Private Equity','Publishing','Real Estate','Retail & Wholesale','Service','Software','Sports','Technology',
							'Telecommunications','Television','Transportation','Venture Capital');?>
							<td><select name="industry" style="width:210px;"><?php foreach($industries as $i){?><option value="<?=$i?>" <?php if($account->data['industry'] == $i){?>selected<?php } ?>><?=$i?></option><?php } ?></select></td>
                        </tr>
                        <tr>
                            <td align="right"><label>Address:</label></td><td><input type="text" class="suggested"  name="address" value="<?=$account->data['address']?>" style="width:200px;" /></td>
                            <td align="right"><label>Address2:</label></td><td><input type="text" name="address2" value="<?=$account->data['address2']?>" style="width:200px;" /></td>                  
                         </tr>
                        <tr>    
                            <td align="right"><label>City:</label></td><td><input type="text" class="suggested"  name="city" value="<?=$account->data['city'];?>" style="width:200px;" /></td>
                       		<td align="right"><label>State / Postal Code:</label></td><td><select name="state"><?php $selected = $account->data['state']; include '../inc/state_select.php'; ?></select>&nbsp;<input type="text" name="zip" value="<?=$account->data['zip'];?>" style="width:143px;" /></td>
						</tr>
                        <tr>
                            <td align="right"><label>Country:</label></td><td><select name="country" style="width:210px;"><?php $selected = $account->data['country']; include '../inc/country_select.php'; ?></select></td>
                            <td align="right"><label>Currency:</label></td><td><select name="currency" style="width:50px;"><?php $selected = $account->data['currency']; include '../inc/currency_select.php'; ?></select></td>
                        </tr>
						<tr>
                            <td align="right"><label>Twitter Url:</label></td><td><input type="text" name="twitter" class="suggested" value="<?=$account->data['twitter']?>" style="width:200px;" /></td>
                            <td align="right"><label>Facebook Url:</label></td><td><input type="text" name="facebook" value="<?=$account->data['facebook']?>" class="suggested" style="width:200px;" /></td>
                        </tr>
						<tr>
                            <td align="right"><label>LinkedIn Url:</label></td><td><input type="text" name="linkedin" class="suggested" value="<?=$account->data['linkedin']?>" style="width:200px;" /></td>
                            <td align="right"><label>Google+ Url:</label></td><td><input type="text" name="google" value="<?=$account->data['google']?>" class="suggested" style="width:200px;" /></td>
                        </tr>
                        <tr>
                            <td valign="top"><label>Logo:</label></td>
                            <td valign="top">
                            	<div class="left"><?=$account->thumbHTML(25,0);?>&nbsp;</div>
                            	<div class="left"><input type="file" name="file" /></div><div class="clear"></div>
                           </td>
                           <td valign="top">
								<label>Google Analytics</label>
						   </td>
						   <td valign="top"><textarea name="analytics" style="width:200px; height:100px;" class="suggested"><?=$account->data['analytics']?></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="center"><input type="submit" value="Save Company Information" class="button blue_button" /><br class="clear" /></td>
                        </tr>
                    </table>
                    </center>
                </form>
                <br/>
                <h2 class="left">Account Usage Information</h2>
                <hr class="title_line" style="margin-bottom:0px;" /> 
                               
                <?php include '../inc/accountdetails.php';?>
               	<table width="100%">
                    <tr>
                    	<td width="100">
                    		<a style="width:100px;" href="<?=str_replace('http://','https://',$HOME_URL)?>account/upgrade.php" class="button green_button">Change Plan</a>
						</td>
						<?php if($account->membership!="free" && $account->membership!="lite"){?>
						<td>
							<a style="width:130px;" href="<?=str_replace('http://','https://',$HOME_URL)?>account/alacarte.php" class="button green_button">A-la-carte Upgrades</a>
                    	</td>
                    	<?php } ?>
                    	<td align="right">
                    		<small><a style="width:140px; font-size:11px !important; padding:5px 0px !important;" href="<?=str_replace('http://','https://',$HOME_URL)?>account/cancel.php" class="button">Cancel Subscription</a></small>
                    	</td>
                    </tr>
                </table>
                            
            <?php } ?>
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

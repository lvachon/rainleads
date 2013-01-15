<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	 include '../inc/trois.php';
	 loginRequired();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<?php include '../inc/head.php'; ?>
<body>
    <div id="wrap">
        <?php include '../inc/header.php'; ?>
        <?php include '../inc/nav.php'; ?>
        <div id="content" class="inner">
        	<div id="main" class="right">
            	<h3>Create An Event</h3>
                <hr />
                <form method="post" action="save.php" enctype="multipart/form-data">
                	<div id='megaForm' >
                		<div id='multiForm'>
		                	<table cellpadding="5" align="center" class='multiTable' id="settings_table">
		                    	
		                        <tr>
		                        	<td>
										<strong>Title:</strong><br />
		                            	<input type="text" name="title" style="width:570px;" />
                                    </td>
		                        </tr>
		                        <tr>
		                        	<td>
										<strong>Description:</strong><br />
		                            	<textarea name="description" rows="5" style="min-width:570px; max-width: 570px; min-height: 74px; max-height: 400px;"></textarea>
                                    </td>
		                        </tr>
		                        <tr>
		                        	<td>
										<strong>Date &amp; Time:</strong><br />
		                        		<select name='start_month' style="width:120px">
		                        			<?php $selected=date("n");include '../inc/month_select.php'; ?>
		                        		</select>
		                        		<select name='start_day' style="width: 54px;">
		                        			<?php $selected=date("j");include '../inc/day_select.php'; ?>
		                        		</select>
		                        		<select name='start_year' style="width: 64px;">
			                            	<?php $selected=date("Y"); echo yearSelect('future',10,$selected); ?>
		                        		</select>
		                        		&nbsp;@&nbsp;
                                        <select name='start_hour' style="width: 54px;">
                                            <?php $selected=date("g");include '../inc/hour_select.php'; ?>
                                        </select>
                                        <select name='start_minute' style="width: 54px;">
                                            <?php $selected=date("i");include '../inc/minute_select.php'; ?>
                                        </select>
		                        		<select name='start_ampm' style="width: 50px;">
		                        			<option value='AM'><?=('AM')?></option>
		                        			<option value='PM' <?php if(date("G")>=12){echo "selected='selected'";}?>><?=('PM')?></option>
		                        		</select>
		                        	</td>
		                        </tr>
		                       
                                <tr>
                                	<td>
                                    	<strong>Lead:</strong><br />
                                    	<select name="lead_id" style="width:435px;">
                                    		<?php $acc = $viewer->getAccount();
											 $leads = $acc->leads();
											foreach($leads as $l){
												if(strlen($_GET['lead_id'])){
													$selected = intval($_GET['lead_id']);
												}?>
												<option value="<?=$l->id?>" <?php if($selected == $l->id){?> selected="selected"<?php } ?>><?=$l->name?></option>
											<?php }?>
                                        </select>
                                    </td>
                                </tr>
		                        <tr>
		                        	<td align="right"><input type='submit' class="button" value='<?=('Save').' '.('Event')?>' /></td>
		                        </tr>
		                    </table>
	                    </div>
                    </div>
                </form>
            </div>
            <div id="side" class="left">
            	<?php include '../inc/sidenav.php'; ?>
            </div>
            <div class="clear"></div>
         </div><?php include('../inc/footer.php') ?>
    </div>    
</body>
</html>
<?php include 'inc/trois.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<?php include 'inc/head.php';?>
	<body>	
		<?php include 'inc/header.php'; ?>
		<?php include 'inc/nav.php'; ?>
		<div id="content" style="height:auto" class="inner">
			<div id="full_width" >
				<div class="inner">
					<div class="left" style="width:779px;">
						<h1>Contact Us</h1>
                        <br clear="all" />
                        <br/>
						<form method="post" action="<?= $HOME_URL ?>send-feedback.php">
							<table width="640" cellpadding="5" style="font-size:14px; color:#555; line-height:20px;">
								<tr>
									<td width="350">
										Your Email:<br />
										<input type="text" name="email" <?php if (intval($viewer->id)) { ?>value="<?= $viewer->email ?>"<?php } ?> style="width:250px;" />
									</td>
									<td>
										Subject:<br />
										<select name="subject" style="width:260px;">
											<option value="--">Select a Subject</option>
											<option value="General Inquiry">General Inquiry</option>
											<option value="Enterprise Solutions">Enterprise Solutions</option>
											<option value="Error Report">Report an Error</option>
											<option value="Media Inquiry">Media Inquiry</option>
											<option value="Other">Other</option>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										Message:<br />
										<textarea name="msg" style="min-width:620px; max-width:620px; min-height: 74px; max-height: 400px;"></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="right"><input type="submit"  class="button blue_button" value="Send Message" /></td>
								</tr>
							</table>
						</form>
						<iframe id="hiden" name="hiden" frameborder="0" height="0" width="0"></iframe>
					</div>
					<div class="right" style="padding:10px;width:180px">
						<?=nl2br(cmsRead('RTFcontact_us'))?>
					</div>
					<div class="clear"></div>
				</div>
   			</div>
		</div>
		<?php include('inc/footer.php'); ?>
	</body>
</html>
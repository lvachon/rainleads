<div id="divrecaptcha"> 
	<img id="captcha" src="<?=$HOME_URL?>captcha/securimage_show.php" alt="CAPTCHA Image"  /><!--Important-->  
 	<div class="recaptcha_only_if_image"><strong>Enter the words shown above</strong></div>
	<input type="text" name="captcha_code" size="10" maxlength="6" /> 
    <object style="position:relative; top:-28px; left:110px;" type="application/x-shockwave-flash" data="<?=$HOME_URL?>captcha/securimage_play.swf?audio_file=/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" width="19" height="19"> 
    	<param name="movie" value="<?=$HOME_URL?>captcha/securimage_play.swf?audio_file=/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" />
	</object>

</div>

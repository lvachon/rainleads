<script>
var RecaptchaOptions = {
			theme : 'custom',
			lang: 'en',
			custom_theme_widget: 'divrecaptcha' //div enclosing widget elements
		};
</script>
<div id="divrecaptcha"> 
	<div id="recaptcha_image" style="border:1px solid #ccc; margin-bottom:1px;"></div><!--Important-->
	<div><a href="#" style="color:#0C7EB9; display:block; margin-top:6px;" onclick="Recaptcha.reload();"><small><span style="color:#0C7EB9; text-decoration:none;">Can't Read The Words?</span></small></a></div>    
    <br />
 	<div class="recaptcha_only_if_image"><span style="color:#e28d03;">Enter the words shown above</span></div>
	<input type="text" name="recaptcha_response_field" id="recaptcha_response_field" style="width:260px;" class="text-box"  />
</div>
<?php
	require_once('recaptchalib.php');
	$publickey = "6LcBDrsSAAAAAH-_skXyVnfNV1lan1UzqL9zPP9a"; // you got this from the signup page
    echo recaptcha_get_html($publickey);
?>

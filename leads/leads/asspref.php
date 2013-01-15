<div>Would you like to see only your leads by default?
<br/>
<input type='submit' value='Yes' name='yesbutton' onclick='$.post("../account/save-user.php",{onlymyleads:1,dontaskleads:$("#dontaskleads:checked").length},function(data){$.fancybox.close();});'>
<input type='submit' value='No' name='yesbutton' onclick='$.post("../account/save-user.php",{onlymyleads:0,dontaskleads:$("#dontaskleads:checked").length},function(data){$.fancybox.close();});'><br/>
<label>Don't ask again<input type='checkbox' value='1' id='dontaskleads'/></label>
<?php include_once '../inc/trois.php';?>
<script>
	function savePost(){
		$.post("<?=$HOME_URL?>wall/save.php",{'post':$('#post').val(),'id':<?=$_GET['id']?>},function(data){$('#activity_feed').prepend(data);$.fancybox.close();});
	}
</script>
<h2>Add a Note</h2>
<div id="interior" style="width:350px;">
    <textarea name="post" id="post" style="width:340px;"></textarea>
    <input type="button" value="Save" onClick="savePost();" class="button right" />
    <div class="clear"></div>
</div>

<?php include 'head.php';?>
<script>
$(function() {
	$("#accordion").accordion( "activate" , 2)	   
});
$(document).ready(function(){
	setupRT(document.getElementById("srctxt"),document.getElementById("outdiv"));
	$("#srctxt").elastic();
});
</script>
<script type="text/javascript" src='../js/psRTF.js'></script>
<script type="text/javascript" src='../js/elastic.js'></script>
<?php 
if(strlen($_GET['edit'])){
	$con = conDB();
	$r = mysql_query("SELECT * from cms where chunk_id='".mysql_escape_string($_GET['edit'])."' ORDER BY datestamp desc LIMIT 1",$con);
	$cms = mysql_fetch_array($r);
	srand(time());
	$rnd = strval(rand(0,1000));?>
    <div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;<a href="./">Admin Panel</a>&middot;CMS</h1>
<p><a href="<?=$HOME_URL;?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />

<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
	<div class="ui-widget">
        <form method='post' action='cms.php' target="hidn">
        	<input type='hidden' name='chunk_id' value="<?=htmlentities($cms['chunk_id']);?>" />
            
        	<div class="ui-state-highlight ui-corner-all" style="padding: 10px 0.7em;">
            	<div class="left">
                    <strong>Title:</strong><br />
                    <input type="text" name="title" style="width: 500px" value="<?=htmlentities($cms['title']); ?>" />
                </div>
                <div class="right">
                    <strong>Identifier:</strong><br />
                    <input type='text' disabled='disabled' style="width: 200px;" value="<?=htmlentities($cms['chunk_id']);?>" />
            	</div>
                <div class="clear"></div>
            </div>
            <br />
            <div class="ui-state-default ui-corner-all" style="padding: 10px 0.7em;">
            	In many entries you will see variables this is a key of what these mean:<br />
                <style>
					ul li{
						width:50%;
						float:left;
						font-weight:normal;
					}
				</style>
                <ul style="width:100%; list-style:none;">
             		<li>%homeurl = Your site's homepage link</li>
                    <li>%sitename = Your site's name</li>
                    <li>%url = Dynamic reply link</li>
                    <li>%sender = Dynamic sender's name</li>
                    <li>%receiver = Dynamic receiver's name</li>
                    <li>%post = User's Message/Comment</li>
                    <li>%type = Media Type</li>
                </ul>
                <div class="clear"></div>
				<?php if(substr($cms['chunk_id'],0,3)!="RTF"){ ?>
                    <textarea id='srctxt' name="content"><?=htmlentities($cms['content']); ?></textarea>
                <?php } else { ?>
                    <div id="rtf_box" class="left">
                        <div id="rtf_controls">
                            <input type='button' value='b' onclick='addTag("b");' style="font-weight: bold;" class="first" />
                            <input type='button' value='i' onclick='addTag("i");' style="font-style: italic;" />
                            <input type='button' value='u' onclick='addTag("u");' style="text-decoration: underline;" />
                            <input type='button' value='&bull;' onclick='addTag("li");' />
                            <input type='button' value='center' onclick='addTag("center");' />
                            <input type='button' value='link' onclick='addLink();' class="last" />
                        </div>
                        <textarea id='srctxt' name="content"><?=htmlentities($cms['content']); ?></textarea>
                    </div>
                    
                    <div id="rtf_preview" class="right">
                        <strong>Preview:</strong><br/>
                        <div id='outdiv'></div>
                    </div>
                    <div class="clear"></div>
                <?php } ?>
                <input type='submit' value='Save' class="right" style="margin-right: 1%;" />
                <div class="clear"></div>
            </div>
        </form>
        <iframe id="hidn" name="hidn" height="0" frameborder="0" width="0"></iframe>
    </div>
</div>

<?php
}elseif(strlen($_POST['content']) && isset($_POST['title']) && strlen($_POST['chunk_id'])){
	$con = conDB();
	if(substr($_POST['chunk_id'],0,3)!="RTF"){$content = mysql_escape_string(strip_tags($_POST['content']));}
	else{$content = mysql_escape_string($_POST['content']);}
	$chunk_id = mysql_escape_string($_POST['chunk_id']);
	$title = mysql_escape_string($_POST['title']);
	mysql_query("INSERT INTO cms(content,title,chunk_id,datestamp) VALUES('$content','$title','$chunk_id',".time().")",$con);?>
	<script type="text/javascript">
		parent.$.facebox("Entry Saved!");setTimeout("parent.document.location='cms.php?edit=<?=$_POST['chunk_id']?>';",1500);
    </script>
<?php }elseif(strlen($_GET['id'])){
	$chunk_id = mysql_escape_string($_GET['id']);
	$con = conDB();
	mysql_query("INSERT INTO cms(chunk_id,datestamp) VALUES('$chunk_id',UNIX_TIMESTAMP())",$con);
	header("Location: cms.php?edit={$chunk_id}");die();
} ?>
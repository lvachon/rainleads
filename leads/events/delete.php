<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include '../inc/trois.php'; 
	$con = conDB();
	loginRequired(true);
	
	if(intval($_POST['id'])){
		$event = new event($_POST['id']);
		if($event->delete()){
			header("Location: calendar.php"); die();
		}else{
			errorMsg("That's not yours to delete",true,true); die();
		}
	} else {
		$event = new event(intval($_GET['id']));
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Delete Event</title>
</head>
<body>
    <h3>Delete Event</h3>
    <div id="interior" style="width:350px;">
    
    <form action="delete.php" method="post">
    	<input type="hidden" name="id" value="<?=$event->id?>" />
        <table width="100%">
            <tr valign="top">
                <td width="230">
                    Are you sure you would like to delete this event: <strong><?=htmlentities($event->title);?></strong>?<br /><br />
                    This action cannot be undone.
                </td>
            </tr>
            <tr>
                <td align='right'><input type='submit' class="button" value='Delete' /></td>
            </tr>
        </table>
	</form>
    </div>
</body>
</html>
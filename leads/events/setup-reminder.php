<?php include_once '../inc/trois.php';?>
<h3>Set Up Reminder</h3>
<div class="interior" style="width:270px;">
    <form action="save-reminder.php" method="post" target="hdn">
    <input type="hidden" name="event_id" value="<?=intval($_GET['id'])?>" />
    <table id="settings_table">
    <tr>
    	<td><input type="text" name="qty" style="width:69px;" placeholder="qty"/></td>
    	<td><select name="unit" style="width:180px;">
                <option value="minutes">minutes</option>
                <option value="hours">hours</option>
                <option value="days">days</option>
                <option value="weeks">weeks</option>
            </select>
        </td>
    </tr>
    <tr>
    	<td colspan="2"><textarea name="message" style="width:250px;max-width:250px;" placeholder="message you would like to recieve with your reminder."></textarea></td>
    </tr>
    <tr>
    	<td colspan="2"><input type="submit" class="button right" value="Set Reminder"/><br clear="all" /></td>
    </tr>
    <table>
    </form>
    <iframe name="hdn" id="hdn" frameborder="0" width="0" height="0"></iframe>
</div>

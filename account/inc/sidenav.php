<div class="link" data-file="dashboard.php" id="dashboard-tab" onclick="document.location.href='<?=$HOME_URL?>account/dashboard.php'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/dash-icon.png" id="dash-icon" />
    </div>
    <div class="title left">
        Dashboard
    </div>
    <div class="clear"></div>
</div>
<div class="link" data-file="leads/index.php" id="leads-tab" onclick="document.location.href='<?=$HOME_URL?>leads/index.php'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/lead-icon.png" id="lead-icon" />
    </div>
    <div class="title left">
        Leads
    </div>             
    <div class="clear"></div>
</div>
<div class="link" data-file="leads/index.php?pipe=1" id="pipeline-tab" onclick="document.location.href='<?=$HOME_URL?>leads/index.php?pipe=1'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/star-side-icon.png" id="star-side-icon" />
    </div>
    <div class="title left">
        Pipeline
    </div>             
    <div class="clear"></div>
</div>
<div class="link" data-file="calendar.php" id="event-tab" onclick="document.location.href='<?=$HOME_URL?>events/calendar.php'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/event-icon.png" id="event-icon" />
    </div>
    <div class="title left">
        Calendar
    </div>             
    <div class="clear"></div>
</div>
<div class="link" data-file="forms.php" id="forms-tab" onclick="document.location.href='<?=$HOME_URL?>forms/index.php'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/forms-icon.png" id="forms-icon" />
    </div>
    <div class="title left">
        Contact Forms
    </div>             
    <div class="clear"></div>
</div>
<div class="link" data-file="edit-facebook.php" id="facebook-tab" onclick="document.location.href='<?=$HOME_URL?>forms/edit-facebook.php'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/facebook-icon.png" id="facebook-icon" />
    </div>
    <div class="title left">
        Facebook Forms
    </div>             
    <div class="clear"></div>
</div>
<?php if($viewer->getAccount()->data['view_stats'] != '0' || ($viewer->getAccount()->data['view_stats'] == '0' && $viewer->id==$viewer->getAccount()->user_id)){?>     
<div class="link" data-file="stats/form_totals.php" id="stats-tab" onclick="document.location.href='<?=$HOME_URL?>stats/form_totals.php'">
    <div class="icon left">
        <img src="<?=$HOME_URL?>img/stats-icon.png" id="stats-icon" />
    </div>
    <div class="title left">
        Statistics
    </div>             
    <div class="clear"></div>
</div>
<?php } ?>
<?php if($viewer->id==$viewer->getAccount()->user_id){?>     
	<div class="link" data-file="microsite/index.php" id="microsite-tab" onclick="document.location.href='<?=$HOME_URL?>microsite/index.php'">
	    <div class="icon left">
	        <img src="<?=$HOME_URL?>img/globe-icon.png" id="globe-icon" />
	    </div>
	    <div class="title left">
	        Microsite
	    </div>             
	    <div class="clear"></div>
	</div>          
    <div class="link" data-file="settings.php" id="settings-tab" onclick="document.location.href='<?=$HOME_URL?>account/settings.php'">
        <div class="icon left">
            <img src="<?=$HOME_URL?>img/settings-icon.png" id="settings-icon" />
        </div>
        <div class="title left">
            Admin Settings
        </div>
        <div class="clear"></div>
    </div>
<?php } ?>
<script>
//Script to set current page class
$(function() {
  var current_path = window.location.pathname.split('/').pop();
  
  if(current_path ==''){current_path='dashboard.php';}
  //alert(current_path);
  $('*[data-file="'+current_path+'"]').addClass('current');
  
  //Setup Mouseover Funtions for the Navigation
	$("#side").children(".link").each(function(){
		if($(this).is('*[data-file="'+current_path+'"]')){
				id=$(this).find('.icon img').attr('id');
				$(this).find(".icon img").attr({"src" :"<?=$HOME_URL?>img/"+ id + "-on.png "});
			}else{
			 $(this).mouseover(function() {
				id=$(this).find('.icon img').attr('id');
				$(this).find(".icon img").attr({"src" :"<?=$HOME_URL?>img/"+ id + "-on.png "});
			});
			$(this).mouseout(function () {
				id=$(this).find('.icon img').attr('id');
				$(this).find(".icon img").attr({"src" :"<?=$HOME_URL?>img/"+ id + ".png "});
			});
		}
	});
});

</script>
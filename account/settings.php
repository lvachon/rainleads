<!DOCTYPE html>
<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = $viewer->getAccount();
if($viewer->id != $account->user_id){
	errorMsg("You don't have permission to access this content.");
	die();
}?>
<html>
<?php include('../inc/head.php'); ?>
<script src="../js/ui.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
	 // Handler for .ready() called.
		$('.swatch_select').click(function(){
			var sOpt = $(this).find('.swatch_options');
			if($(sOpt).is(':hidden')){
				$(sOpt).show(); 
			}else{
				$(sOpt).hide(); 
			}
		});
		
		$('.swatch_option').click(function(){
			   var swClass = "color_"+$(this).attr('data-colorID');
				$(this).parent('.swatch_options').parent().attr({'class':'swatch_select '+swClass});  
				id = $(this).parent().parent().parent().parent().attr('id');
				$.post('save-color.php',{'id':id,'color':swClass},function(data){});
		});
		
		
		
		$("#milestone_list").sortable({
			update: function(){
				var myarray = $(this).sortable('serialize');
				$.post('save-order.php',{'order':myarray,'table':'milestones'},function(data){});
			}
		});
		
		$("#status_list").sortable({
			update: function(){
				var myarray = $(this).sortable('serialize');
				$.post('save-order.php',{'order':myarray,'table':'statuses'},function(data){});
			}
		});
		
	});
	function updateAdmin(selector,status){
		if(status == true){
			admin = 1;
		}else{
			admin = 0;
		}
		$.post('save-admin.php',{'admin':admin,'id':selector},function(data){});
	}
	
	function updateShow(selector,status){
		$.post('save-show.php',{'show':status,'id':selector},function(data){});
	}
	
	function changeData(field,val){
		if($("#"+field).is(':checked')){
			val = '1';
		}else{
			val = '0';
		}
		$.post('modifyData.php',{'account_id':<?=$account->id?>,'field':field,'val':val});	
	}
	
	
	function saveName(selector,val){
		$.post('save-name.php',{'id':selector,'val':val},function(data){});		
	}
	
	$(function(){
		$("input.hidden").bind("keyup",function(e){
			 var code = (e.keyCode ? e.keyCode : e.which);
			 if(code == 13){
				id = $(this).parent().parent().attr('id');
				val = $("#"+id+"_title").val();
			 	saveName(id,val);
			 }
		});
	});
	
	function remove(selector){
		type = selector.substr(0,selector.indexOf('_') );
		console.log(type);
		if(confirm("Are you sure you want to remove this "+type+"?")){
			$.post('remove.php',{'id':selector},function(data){
				$('#'+selector).fadeOut(function(){$('#'+selector).remove();});
			});
		}
	}
	
</script>
<style>
	input.hidden{
		border:none;
		background:none;
		font-weight: bold;
		font-size: 14px;
		width:195px;
		margin-top:-3px;
	}
	ul.ui-sortable li{
		cursor:move;
	}
</style>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
    <div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
        <div id="admin_settings">
            <h1>Admin Settings</h1>            
            <div class="clear"></div>
            <br/>
            <h2 class="left">Basic Settings</h2>
            <hr class="title_line" />
            <div class="clear"></div>
            	<ul id="settings_list">
                	<li><label><input type="checkbox" name="assign_leads" id="assign_leads" <?php if(intval($account->data['assign_leads'])){?> checked="checked"<?php } ?> value="1" onChange="changeData('assign_leads',$(this).val());"> Allow Team Members to Assign Leads</label></li>
                    <li><label><input type="checkbox" name="see_all_leads" id="see_all_leads" <?php if(intval($account->data['see_all_leads'])){?> checked="checked"<?php } ?> value="1" onChange="changeData('see_all_leads',$(this).val());"> Allow Team Members to See All Leads</label></li>
                    <li><label><input type="checkbox" name="notify_members" id="notify_members" <?php if(intval($account->data['notify_members'])){?> checked="checked"<?php } ?> value="1" onChange="changeData('notify_members',$(this).val());"> Notify Members of Newly Assigned Leads</label></li>
                    <li><label><input type="checkbox" name="daily_digest" id="daily_digest" <?php if(intval($account->data['daily_digest'])){?> checked="checked"<?php } ?> value="1" onChange="changeData('daily_digest',$(this).val());"> Send Daily Digest to Administrator</label></li>
                    <li><label><input type="checkbox" name="set_statuses" id="set_statuses" <?php if($account->data['set_statuses'] != '0'){?> checked="checked"<?php } ?> value="1" onChange="changeData('set_statuses',$(this).val());"> Allow Team Members to Set Lead Statuses</label></li>
                    <li><label><input type="checkbox" name="add_forms" id="add_forms" <?php if(intval($account->data['add_forms'])){?> checked="checked"<?php } ?> value="1" onChange="changeData('add_forms',$(this).val());"> Allow Team Members to Edit or Create Forms</label></li>
                    <li><label><input type="checkbox" name="view_stats" id="view_stats" <?php if($account->data['view_stats'] != '0'){?> checked="checked"<?php } ?> value="1" onChange="changeData('view_stats',$(this).val());"> Allow Team Members to View Statistics</label></li>
                </ul>
            <br/>
            <h2 class="left">Team</h2>
            <hr class="title_line"/>
            <div class="clear"></div>
            <?php foreach($account->members as $m){
				$mem = new User($m);?>            
                <div class="team_member">
                    <div class="left" style="width:38px;"><?=$mem->avatar(32,32,false)?></div>
                    <div class="left"><?=$mem->name()?><br/><small class="grey capital"><?=$mem->email?></small></div>
                    <div class="right">
                        <small class="grey">
                            <?php if($viewer->id == $mem->id){ 
                                echo "You"; 
                            }else{
                                if(in_array($mem->id,$account->admins)){
                                    echo "Admin";
                                }else{
                                    echo "Team Member";
                                }
                            }?>
                        </small>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php } ?>
            <br/>
            <?php if(count($account->members)<$account->userLimit()){?>
            	<div class="left"><div class="button blue_button" onclick="$.post('add-member.php',function(data){$.fancybox(data);});">Add Team Member</div></div>
            <?php }else{ ?>
            	<div class="left">You have reached your user limit.  Please <a href='/account/upgrade.php'>upgrade</a> your account.</a></div>
            <?php } ?>
            <div class="clear"></div>
            <br/><br/><br/>
            <h2 class="left">Milestones</h2>
            <hr class="title_line"/>
            <div class="clear"></div>
            <ul id="milestone_list">
                <?php foreach($account->milestones() as $milestone){?>
                    <li class="milestone_row" id="milestone_<?=$milestone->id?>">
                       
                        <div class="left milestone_name"><input class="hidden" type="text" name="title" id="milestone_<?=$milestone->id?>_title" onChange="saveName('milestone_<?=$milestone->id?>',$(this).val());" value="<?=$milestone->title?>" /></div>
                        <div class="right">
                        	<div class="show" id="show_milestone_<?=$milestone->id?>" <?php if(!$milestone->show){?>style="display:none;"<?php } ?>>
                                <a href="javascript:void(0)" class="disabled">show</a>&nbsp;&nbsp;&nbsp;<a onclick="updateShow('milestone_<?=$milestone->id?>',0);$('#hide_milestone_<?=$milestone->id?>').toggle();$('#show_milestone_<?=$milestone->id?>').toggle();" href="javascript:void(0)">hide</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="remove('milestone_<?=$milestone->id?>');">delete</a>
                            </div>
                            <div class="hide" id="hide_milestone_<?=$milestone->id?>" <?php if($milestone->show){?>style="display:none;"<?php } ?>>
                                <a href="javascript:void(0)" onclick="updateShow('milestone_<?=$milestone->id?>',1);$('#hide_milestone_<?=$milestone->id?>').toggle();$('#show_milestone_<?=$milestone->id?>').toggle();">show</a>&nbsp;&nbsp;&nbsp;<a class="disabled" href="javascript:void(0)">hide</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="remove('milestone_<?=$milestone->id?>');">delete</a>
                            </div>
                        
                        </div>
                        <div class="clear"></div>
                    </li>
                <?php } ?>
            </ul>
            <br/>
            <div class="left"><div class="button blue_button"  onclick="$.post('add-status.php',{'table':'milestones'},function(data){$.fancybox(data);});">Add Milestone</div></div>
            <div class="clear"></div>
            <br/><br/><br/>
            <h2 class="left">Lead Statuses</h2>
            <hr class="title_line"/>
            <div class="clear"></div>
            <div id="lead_statuses">
            	<ul id="status_list">
                	<?php foreach($account->statuses() as $status){?>
                        <li class="status_row" id="status_<?=$status->id?>">
                            <div class="swatch_arrow left" data-statusID=1>
                            <div class="swatch_select <?=$status->color?>">&#9660;
                                <div class="swatch_options">
                                    <div class="swatch_option color_1" data-colorID="1"> </div>
                                    <div class="swatch_option color_2" data-colorID="2"> </div>
                                    <div class="swatch_option color_3" data-colorID="3"> </div>
                                    <div class="swatch_option color_4" data-colorID="4"> </div>
                                    <div class="swatch_option color_5" data-colorID="5"> </div>
                                    <div class="swatch_option color_6" data-colorID="6"> </div>
                                    <div class="swatch_option color_7" data-colorID="7"> </div>
                                    <div class="swatch_option color_8" data-colorID="8"> </div>           
                                    <br clear="all" />
                                </div>
                            </div>    
                            </div>
                            <div class="left milestone_name"><input class="hidden" type="text" name="title" id="status_<?=$status->id?>_title" onChange="saveName('status_<?=$status->id?>',$(this).val());" value="<?=$status->title?>" /></div>
                            <div class="right">
                            	<div class="show" id="show_status_<?=$status->id?>" <?php if(!$status->show){?>style="display:none;"<?php } ?>>
                                	<a href="javascript:void(0)" class="disabled">show</a>&nbsp;&nbsp;&nbsp;<a onclick="updateShow('status_<?=$status->id?>',0);$('#hide_status_<?=$status->id?>').toggle();$('#show_status_<?=$status->id?>').toggle();" href="javascript:void(0)">hide</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="remove('status_<?=$status->id?>');">delete</a>
                            	</div>
                                <div class="hide" id="hide_status_<?=$status->id?>" <?php if($status->show){?>style="display:none;"<?php } ?>>
                                	<a href="javascript:void(0)" onclick="updateShow('status_<?=$status->id?>',1);$('#hide_status_<?=$status->id?>').toggle();$('#show_status_<?=$status->id?>').toggle();">show</a>&nbsp;&nbsp;&nbsp;<a class="disabled" href="javascript:void(0)">hide</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="remove('status_<?=$status->id?>');">delete</a>
                                </div>
                            </div>
                            
                            <div class="clear"></div>
                        </li>
                    <?php } ?>
                   
                </ul>
                <br/>
                <div class="left"><div class="button blue_button" onclick="$.post('add-status.php',{'table':'statuses'},function(data){$.fancybox(data);});">Add Status</div></div>
                <div class="clear"></div>
            </div>
        </div>
    
    <div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<?php include('../inc/footer.php'); ?>
<ol id="feature-tips" style="display:none;">
	<li data-id="settings_list"  data-options="tipLocation:left" >
    	<div class="title">Set Team Permissions</div>
        <div class="desc">
        	This is where you can control the different permissions available to your team.
        </div>
    </li>
	
</ol>
<script>
function showNextPage(){
	document.location.href='<?= $HOME_URL ?>account/settings.php?ft=true';
}
<?php if($_GET['ft'] == 'true'){?>
 $(window).load(function() {
    $("#feature-tips").joyride({
      /* Options will go here */
    });
  });
<?php } ?>

</script>
</body>
</html>

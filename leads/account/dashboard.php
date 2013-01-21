<?php include('../inc/trois.php');
loginRequired();
$account = new Account(verAccount());
?><!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<script src="/js/ddslick.js"></script>

<script>
var assignData = [
	<?php 
	$cnt=0;
	foreach($account->members as $u){
		$user = new User($u);
		if($cnt!=0){echo ",\n";}
		$cnt++;
		echo "{text: \"".$user->name("F l")."\",value:{$user->id},imageSrc:'{$user->avatarSrc()}',selected:";
		if($d['assigned_user'] == $user->id){echo "true";}else{echo "false";}
		echo "}";
	}
	?>
];

function doAssign(id){
	$("#assign"+id).css({opacity:0.5});
	$.post("../leads/assign.php",{result_id:id,user_id:$("#assign"+id).val()},function(data){
		$("#assign"+id).css({opacity:1.0});
	});
}


assignText="Assign user";
$(document).ready(function(){
	
	a = $('.assign');
	for(var i=0;i<a.length;i++){
		var stext = $(a[i]).attr("selected-text");
		var sid = $(a[i]).attr("lead-id");
		assignData[0].lead_id = sid;
		$(a[i]).ddslick({
			data:assignData,
			width: 170,
			imagePosition: "left",
			selectText: stext,
			onSelected: function(data){$.post("../leads/assign.php",{result_id:data.original[0].id.substring(6),user_id:data.selectedData.value},function(data){});}
			});
	}
});

function togglepipe(id){
	$.get("../leads/togpipe.php",{"result_id":id},function(data){
		if(data=="0"){
			$("#pipestar"+id).html('<img class="tip" title="Add to Pipeline" src="<?=$HOME_URL?>img/star-icon.png" />');
		}else{
			$("#pipestar"+id).html('<img class="tip" title="Remove from Pipeline" src="<?=$HOME_URL?>img/star-icon-on.png" />');
		}
	});
}
</script>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
        <div id="recent_leads">
            <h1>Recent Leads</h1>
            <h4><a href="<?=$HOME_URL?>leads/index.php">View All Leads</a></h4>
            <div class="clear"></div>
            <?php
            $con = conDB();
			$sort="datestamp desc";            
           	$assfilter = "";
           	$page = 1;
           	if(!$page){$page = 1;}
           	$per_page=5;
           	$offset = ($page-1)*$per_page;
            if(intval($account->data['see_all_leads']) || in_array($viewer->id,$account->admins)){
            	$qry = "form_results where length(name)>0 and length(email)>0 and form_id IN (SELECT id from forms where account_id={$account_row['id']}) {$fcon} {$filter} {$assfilter} and deleted=0";
            	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort} LIMIT $offset,$per_page",$con);
            }else{
            	$qry = "form_results where length(name)>0 and length(email)>0 and id IN (SELECT result_id from assignments where user_id={$viewer->id}) {$fcon} {$filter} and deleted=0";
            	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort} LIMIT $offset,$per_page",$con);
            }
            
			$x=0;
            $pgl = pageLinks($qry,$page,$per_page,"index.php?sort={$_GET['sort']}&filter={$_GET['filter']}&form_id={$_GET['form_id']}");
            while($lead = mysql_fetch_array($res)){
            	$tuser = new User($lead['assigned_user']);
            ?>
            <div class="lead_row" >
                <table width="100%" cellpadding="5" cellspacing="0">
                    <tr valign="middle">
                    	<td onclick="document.location.href='../leads/lead.php?id=<?= $lead['id'] ?>'" valign="middle" class='id_cell' width="25" align="center"><small>#<?=$lead['display_id'];?></small></td> 
                        <td  valign="middle" class="star_cell" width="16" align="center"><a href='javascript:void(0);' onclick='togglepipe(<?=$lead['id'];?>);' id='pipestar<?=$lead['id'];?>'><img class="tip" title="Add to Pipeline" src="<?=$HOME_URL?>img/star-icon<?php if(intval($lead['pipeline'])){echo "-on";}?>.png" /></a></td>
                        <td onclick="document.location.href='../leads/lead.php?id=<?= $lead['id'] ?>'" class="status_cell" valign="middle" align="left" width="70" >
                            <div class="status <?=$lead['statuscolor'];?>"><?=$lead['statustext'];?></div>
                        </td>
                        <td onclick="document.location.href='../leads/lead.php?id=<?= $lead['id'] ?>'" valign="middle" class="name_cell" width="304" align="left"><?=$lead['name'];?><br/><small><?=$lead['email'];?></small></td>
                        <td onclick="document.location.href='../leads/lead.php?id=<?= $lead['id'] ?>'" class="date_cell" valign="middle" align="right"><?=date("M jS @ g:ia",$lead['datestamp']);?></td>
                        <td valign="middle" align="" class="assign_cell" width="184">
                         	<?php 
                         	if(in_array($viewer->id,$account->admins) || intval($account->data['assign_leads'])){
                        		if(intval($tuser->id)){?>
                        			<div class='assign' id = 'assign<?=$lead['id'];?>' lead-id="<?=$lead['id'];?>" selected-text="<?=$tuser->name("F l");?>" selected-id="<?=$tuser->id;?>"></div><?php
                        		}else{?>
                        			<div class='assign' id = 'assign<?=$lead['id'];?>' lead-id="<?=$lead['id'];?>" selected-text="Unassigned" selected-id="<?=$tuser->id;?>"></div><?php
                        		}
                        	}else{ 
                        		if($lead['assigned_user'] !=0){?><small>Assigned To: </small> <?php echo $tuser->name("F l");}
                        	}?>
                        </td>
                    </tr>            	
                </table>
            </div>
            <?php $x++;} ?>
            <?php if($x == 0){?>
            	<div class="message">You have no recent leads. <a href="javascript:void(0);" onclick='$.fancybox({"href":"../forms/pickform.php?fancy=1","type":"ajax"});'>Add a lead</a> or <a href="<?= $HOME_URL ?>forms/">Create a Form</a> for your website.</div>
            <?php } ?>
        </div>
        <div class="left" id="recent_activity">
        	<h1>Recent Activity</h1>
            <h4><a href="<?=$HOME_URL?>wall/">View all Activity</a></h4>
            <div class="clear"></div>
           	<?php include '../wall/wall.php'; echo getActivity('all',18); ?>
            
            
        </div>
        <div class="right" id="recent_stats">
        	<h1>Recent Statistics</h1>
            <h4><a href='../stats/form_totals.php'>View all Statistics</a></h4>
            <div class="clear"></div>
            <?php
            	$forms = array();
				
				$r = mysql_query("SELECT id from forms where account_id={$account->id}",$con);
				while($a = mysql_fetch_array($r)){
					$forms[]=$a['id'];
				}
				$forms = implode(",",$forms);
				$st = mktime(0,0,0)-86400*30;
				$r = mysql_query("SELECT count(*) from form_results where form_id IN ({$forms}) and deleted=0 and datestamp>$st",$con);
				$totalLeads = mysql_fetch_array($r);
				$totalLeads = intval($totalLeads[0]);
				
				$r = mysql_query("SELECT count(*) from form_results where form_id IN ({$forms}) and deleted=0 and pipeline=1 and datestamp>$st",$con);
				$totalPipes = mysql_fetch_array($r);
				$totalPipes = intval($totalPipes[0]);
				
				$r = mysql_query("SELECT count(*) from form_impressions where form_id IN ({$forms}) and tracking_code='microsite' and datestamp>$st",$con);
				//echo "SELECT count(*) from form_impressions where form_id IN ({$forms})  and tracking_code='microsite' and datestamp>$st";
				$totalMS = mysql_fetch_array($r);
				$totalMS = intval($totalMS[0]);
				
				
			?>
				<br/>
				<h3>Last 30 days:</h3>
				<div class="clear"></div>
				<div class="left stat-box">
					<div class="count first"><?= $totalLeads ?></div>
					<div class="title">Leads Received</div>
				</div>
				<div class="left stat-box">
					<div class="count second"><?= $totalPipes ?></div>
					<div class="title">Leads Pipelined</div>
				</div>
				<div class="left stat-box" style="border:none;">
					<div class="count third"><?= $totalMS ?></div>
					<div class="title">Microsite Views</div>
				</div>
				<div class="clear"></div>
				<hr style="border-bottom:1px solid #C8C8C8;" />
                <h1>Team Activity</h1>
                <div class="clear"></div>
                <?php $getMembers = mysql_query("SELECT id FROM users WHERE id IN(SELECT user_id FROM membership WHERE account_id = {$account->id}) ORDER BY getData('last_login',data) DESC",$con); 
                while($i = mysql_fetch_array($getMembers)){
					$mem = new User($i['id']);
					if(strlen($mem->data['last_login'])){
						$logged_in = timeAgo(intval($mem->data['last_login']));
					}else{
						$logged_in = "no login found";
					}?>
                    <div class="team_member">
                        <div class="left" style="width:38px;"><?=$mem->avatar(32,32,false)?></div>
                        <div class="left" style="width:235px; word-wrap:break-word;"><?=$mem->name()?><br/><small class="grey capital">Last Login: <?=$logged_in?></small></div>
                        <div class="right" >
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
                <h1>Upcoming Events</h1>
                <div class="clear"></div>	
				<?php $datestamp = time(); $getEvents = mysql_query("SELECT id FROM events WHERE start_time > $datestamp AND account_id = {$account->id} ORDER BY start_time ASC LIMIT 5",$con) or die(mysql_error()); 
				//echo "SELECT id FROM events WHERE start_time > $datestamp AND account_id = {$account->id} ORDER BY start_time ASC LIMIT 5";
				while($e = mysql_fetch_array($getEvents)){
					$event = new Event($e['id']);
					include '../events/small.php';
				}?>		
	
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('../inc/footer.php'); ?>
<ol id="feature-tips" style="display:none;">
	<li data-id="settings-tab"  data-options="tipLocation:right" >
    	<div class="title">Customize Your Lead Panel</div>
        <div class="desc">
        	<ul>
            	<li>Invite Team Members by Email</li>
                <li>Add Milestones</li>
            </ul>
        </div>
    </li>
	<li data-id="leads-tab"  data-options="tipLocation:right" >
    	<div class="title">Manage Your Leads</div>
        <div class="desc">
        	<ul>
            	<li>Add new leads</li>
                <li>Assign leads</li>
                <li>Track lead activity</li>
            </ul>
        </div>
    </li>
    <li data-id="forms-tab"  data-options="tipLocation:right" >
    	<div class="title">Create Custom Contact Forms</div>
        <div class="desc">
        	<ul>
            	<li>Create custom contact form</li>
                <li>Style & preview form</li>
                <li>Add form to your website or microsite</li>
            </ul>
        </div>
    </li>
    <li data-id="pipeline-tab"  data-options="tipLocation:right"  >
    	<div class="title">Manage Your Pipeline</div>
        <div class="desc">
        	<ul>
            	<li>Upload Proposals</li>
                <li>Track Probability</li>
            </ul>
        </div>
    </li>
    <li data-id="stats-tab"  data-options="tipLocation:right"  data-button="Next Page">
    	<div class="title">Statistics</div>
        <div class="desc">
        	<ul>
            	<li>View lead statistics</li>
                <li>Run reports</li>
                
            </ul>
        </div>
    </li>
</ol>
<script>
function showNextPage(){
	document.location.href='<?= $HOME_URL ?>account/settings.php?ft=true';
}
 function showFeatureTips(){
    $("#feature-tips").joyride({
      /* Options will go here */
	  'postRideCallback':showNextPage
    });
 }
</script>
</body>
</html>

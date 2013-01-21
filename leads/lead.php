<?php include '../inc/trois.php';
loginRequired();
$viewer = new User(verCookie());
if(!verAccount()){errorMsg("No account associated with login");}
$account = new Account(verAccount());
$id = intval($_GET['id']);
$f = new Form();
$con = conDB();
$r = mysql_query("SELECT *, (SELECT account_id from forms where id=form_id) as account_id,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from form_results where id=$id",$con);
$d = mysql_fetch_array($r);
if(!intval($d['id'])){die("NO DATA");}
if(intval($d['account_id'])!=$account->id){errorMsg("This lead does not belong to the current account");die();}
$f->elems = unserialize($d['data']);
for($i=0;$i<count($f->elems);$i++){
	//if(is_a($f->elems[$i],"FormElement")){$f->elems[$i]->init();}
}
$assigned = new User($d['assigned_user']);
?>
<!DOCTYPE html>
<html>

<?php include ('../inc/head.php');?>
<script src='/js/ddslick.js'></script>
<script>
	var stateData = [
		<?php 
		if(!in_array($viewer->id,$account->admins)){$adcon = " and admin!=1 ";}
		$r = mysql_query("SELECT * from statuses where `show`=1 and account_id={$account->id} {$adcon} order by display asc",$con);
		$out = array();
		$statusText = "Set Status";
		while($state = mysql_fetch_array($r)){
			$x = array('text'=>'<div class="dd-selected-image left '.$state['color'].'"></div>'.$state['title'],'value'=>$state['id'],'selected'=>false);
			if($d['status']==$state['id']){
				$x['selected']=true;
				$statusText = $state['title'];
				$statusColor = $state['color'];
			}
			$out[]=$x;
		}
		for($i=0;$i<count($out);$i++){
			if($i){echo ",";}
			echo json_encode($out[$i]);	
		}
		?>
	];
	var statusSelect = "Set Status";
		var assignData = [
		<?php 
			$cnt=0;
			foreach($account->members as $u){
				$user = new User($u);
				if($cnt!=0){echo ",\n";}
				$cnt++;
				echo "{text: \"".$user->name("F L")."\",value:{$user->id},imageSrc:'{$user->avatarSrc()}',selected:";
				if($d['assigned_user'] == $user->id){echo "true";}else{echo "false";}
				echo "}";
			}
		?>
	];
	var assignText = "Assign Lead";
	var glbl;
	function init(){
	 	$('#state').ddslick({
			data:stateData,
			width: 110,
			imagePosition: "left",
			selectText: statusSelect,
			background:'#f0f0f0',
			onSelected: function (data) {
				$.post("formstatus.php",{id:<?=$d['id'];?>,status:data.selectedData.value},function(data){});
				
				}
			});
		$('#assign').ddslick({
			data:assignData,
			width: 170,
			imagePosition: "left",
			selectText: assignText,
			onSelected: function (data) {
				$.post("assign.php",{result_id:<?=$d['id'];?>,user_id:data.selectedData.value},function(data){});
				
			}
			});

		$.get("showresult.php?id=<?=$_GET['id'];?>",function(data){
			$("#form_fields").html(data);
		});
	}

	function togms(msid){
		$.post("togglemilestone.php",{result_id:<?=$d['id'];?>,'msid':msid},function(data){
			if(data=="1"){
				$("#msrow"+msid).addClass("active");
				$("#mscb"+msid)[0].checked=true;
				notif("information","Marked Milestone Complete");
			}else{
				$("#msrow"+msid).removeClass("active");
				$("#mscb"+msid)[0].checked=false;
				notif("information","Marked Milestone Incomplete");
			}
		});
	}

	function togglepipe(){
		$.get("togpipe.php",{"result_id":<?=$d['id'];?>},function(data){
			if(data=="0"){
				$("#pipestar<?=$d['id'];?>").html('<img class="tip" title="Add to Pipeline" src="<?=$HOME_URL?>img/star-icon.png" />');
			}else{
				$("#pipestar<?=$d['id'];?>").html('<img class="tip" title="Remove from Pipeline" src="<?=$HOME_URL?>img/star-icon-on.png" />');
			}
		});
	}

	
	function showTab(tab){
		$('.tab_content').hide();
		$('*[data-tab="'+tab+'"]').show();
		$('.tab').removeClass('active');
		$('#tab-'+tab).addClass('active');
	}
	$(function() {
		$('.dd-option-image').css( "background-size", "cover" );
	});
	
	<?php if(!$_GET['show']){?>
		$(function() {
		 	$('div[data-tab=details]').show();
		});
		
		
	<?php }else{ ?>
		$(function() {
			showTab('<?= $_GET['show'] ?>');
		});
	<?php } ?>
	
</script>
<style>
	.inputcell input[type=text]{
		padding:5px;
		width:242px;	
	}
	.inputcell select {
		padding:4px;	
	}
</style>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
    <div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
    	
    	
        <div id="lead_content">
            <div class="top_section">
                <div class="left">    
                    <!-- IF the lead is in "the pipeline" show star icon \/ icon -->
                   
                    <h2><?=$f->getElemByName('name')->value;?> <a href='javascript:void(0);' onclick='togglepipe();' id='pipestar<?=$d['id'];?>'><img class="tip" title="Add to Pipeline" src="<?=$HOME_URL?>img/star-icon<?php if($d['pipeline']){echo "-on";}?>.png" /></a></h2><?=$f->getElemByName('email')->value;?>
                    <div class="clear"></div>                
                    <table class="lead_table" cellpadding="5">
                         <tr>
                            <td class="lead_table_label">Lead #:</td>
                            <td class="lead_table_value"><?= $d['display_id'] ?></td>
                        </tr>
                        <tr>
                            <td class="lead_table_label">Received:</td>
                            <td class="lead_table_value"><?=date("M jS Y, g:ia T",$d['datestamp']);?></td>
                        </tr>
                        <tr>
                            <td class="lead_table_label">Source:</td>
                            <td class="lead_table_value"><?php if($d['tracking_code'] == 'fb'){ echo "Facebook"; }else{ echo $d['tracking_code']; }?></td>
                        </tr>
                        <tr>
                            <td class="lead_table_label" valign="middle">Status:</td>
                            <td class="lead_table_value state_select_table"><?php if($account->data['set_statuses'] == 0 && $viewer->id != $account->user_id){?><div class="status <?=$statusColor;?>"><?=$statusText;?></div><?php }else{ ?><div id="state"></div><?php } ?></td>
                        </tr>
                        <tr>
                            <td class="lead_table_label" valign="middle">Assigned:</td>
                            <td class="lead_table_value">
                            <?php if(in_array($viewer->id,$account->admins)){?><div id="assign"></div><?php }else{echo $assigned->name("F l");}?>
                            </td>
                        </tr>
                        <tr>
                            <td class="lead_table_label" valign="middle" >
                            <?php if(!intval($d['deleted'])){?>
                            	<input type='button' class="button small" value='Archive Lead' style="font-size:10px !important;" onclick='if(confirm("Do you really want to archive this lead?")){document.location.href="killlead.php?id=<?=$d['id'];?>";}'/>
                            <?php }else{?>
                            	<input type='button' class="button small" value='Unarchive Lead' onclick='if(confirm("Do you really want to unarchive this lead?")){document.location.href="activate.php?id=<?=$d['id'];?>";}'/>
                            <?php } ?>
                            
                            </td>
                            <td class="lead_table_value">
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right">
					
                	
                
                	<div id="milestone_list">
                		<div class="clear"></div>
                		<h5 style="margin-top:10px; font-size:16px;">Milestones</h5>
               		 	<div class="milestone-list">
							<?php $r = mysql_query("SELECT * from milestones where account_id={$account->id} and `show`=1 {$adcon} order by display asc",$con);
                            echo mysql_error();
                            $ms = explode(",",$d['milestones']);
                            while($m = mysql_fetch_array($r)){
                            ?>
                                <div onclick='togms(<?=$m['id'];?>);' class="milestone_capsule <?php if(in_array($m['id'],$ms)){?>active<?php }?>"  id='msrow<?=$m['id'];?>'>
                                    <input type="checkbox" <?php if(in_array($m['id'],$ms)){?>checked="checked"<?php }?> value="<?=$m['id'];?>"  id='mscb<?=$m['id'];?>' onchange='togms(<?=$m['id'];?>);'/>
                                    <div class="dot"></div>
                                    <div class="ms_text"><?=$m['title'];?></div>
                                </div>
                            <?php } ?>                         
                		</div>
                   	</div>                    
	            </div>
                <div class="clear"></div>
            </div>
            <!-- END TOP SECTION -->
            
            <!-- TAB SECTION -->
            <div class="tab_box">
            	<div class="tab active" id="tab-details" onClick="showTab('details')">Details</div>
                <div class="tab" id="tab-contact" onClick="showTab('contact')">Contact Info</div>
                <div class="tab" id="tab-activity" onClick="showTab('activity')">Activity</div>
                <div class="tab" id="tab-proposals" onClick="showTab('proposals')">Files</div>
                <div class="tab" id="tab-events" onClick="showTab('events')">Events</div>
                <!--<div class="tab" id="tab-facebook" onClick="showTab('facebook')">Facebook</div>
                <div class="tab" id="tab-linkedin" onClick="showTab('linkedin')">LinkedIn</div>-->
            </div>
            <div class="tab_content" data-tab="details" style="display:none;">
                <div id='form_fields'>
                	<?= $f->getResultHTML();?>                    
                </div>
            </div>
            <div class="tab_content" data-tab="proposals" style="display:none;">
            	<div class="right">
            		<a href="javascript:void(0)" onclick='$.get("addprop.php?id=<?=$d['id'];?>",function(data){$.fancybox(data);});' class="button blue_button">Add a File</a>
            	</div>
            	<div class="clear"></div>
                <div id='proposal_list'>
                	<?php $r = mysql_query("SELECT * from proposals where result_id={$d['id']} order by datestamp desc",$con);
                	$px=0;
                	while($prop = mysql_fetch_array($r)){$px++; $uux = new User($prop['user_id']);?>
                	   <div class="proposal">
                	   		<table cellpadding="5" width="100%">
                	   			<tr>
                	   				<td rowspan="5" width="32">
                	   					<img src="<?= $HOME_URL ?>img/clip.png" />
                	   				</td>
                	   				<td valign="top" width="90">
                	   					<strong>File Name:</strong>
                	   				</td>
                	   				<td valign="top">
                	   					 <?=$prop['title'];?>
                	   				</td>
                	   			</tr>
                	   			<?php if($prop['amount'] >0){?>
                	   			<tr>
                	   				<td valign="top">
                	   					<strong>Amount:</strong> 
                	   				</td>
                	   				<td valign="top">
                	   					<?= $account->data['currency'] ?><?=$prop['amount'];?>
                	   				</td>
                	   			</tr>
                	   			<?php } ?>
                	   			<?php if($prop['probability'] >0){?>
                	   			<tr>
                	   				<td valign="top">
                	   					<strong>Probability:</strong> 
                	   				</td>
                	   				<td colspan="2">
                	   					<div class="prob_small prob_cold"><?=floor(100*$prop['probability'])?>%</div>
                	   				</td>                	   				
                	   			</tr>
                	   			<?php } ?>
                	   			<?php if(file_exists(getcwd()."/../proposals/{$prop['id']}") && strlen($prop['data'])){?>
                	   			<tr>
                	   				<td colspan="2"><img class="proposal_doctype" src="<?= $HOME_URL ?>img/doctypes/pdf.png" /> <a href="../proposals/<?=$prop['id'];?>" target="_blank"><?=$prop['data'];?></a></td>
                	   				
                	   			</tr>
                	   			<?php } ?>
                	   			<tr>
                	   				<td colspan="2">
                	   					<small>Added <?=date("F jS, Y", $prop['datestamp'])?> by <?=$uux->name("F l");?>
                	   					<a href='javascript:void(0)' onclick='$.get("addprop.php?prop_id=<?=$prop['id'];?>&id=<?=$d['id'];?>",function(data){$.fancybox(data);});'>Edit</a>  &middot; 
                	   					<a href='javascript:void(0)' onclick='if(confirm("Do you really want to delete this proposal?  This cannot be undone")){document.location.href="addprop.php?prop_id=<?=$prop['id'];?>&kill=1&lead_id=<?=$d['id'];?>";}'>Delete</a>
                	   					</small></td>
                	   			</tr>
                	   		</table>
                	   </div>
                	<?php } ?> 
                	<?php if($px==0){?>
                		<br/>
                		<center class="message" style="margin:0px;">You have no files associated with this lead.</center>
                	<?php } ?>
                </div>
            </div>
             <div class="tab_content" data-tab="contact" style="display:none;">
                <div id='contact_info'>
                	<form method='post' action='saveinfo.php' target='hidn'>
                		<input type='hidden' name='result_id' value='<?=$d['id'];?>' />
		               
		                	<?php $cdata = unserialize($d['contact_data']);?>
		                	<div class="left" style="width:35%; margin-right:50px; margin-left:80px;">
                                <h3 class="left">Address Details</h3>
                                <hr class="title_line"/>
                                <div class="clear"></div>
                                <div class='labelcell' >Address<div class='inputcell'><input type='text' name='address1' value="<?=htmlentities($cdata['address1']);?>"/></div></div>
                                <div class='labelcell' >Address 2<div class='inputcell'><input type='text' name='address2' value="<?=htmlentities($cdata['address2']);?>"/></div></div>
                                <div class='labelcell' >City<div class='inputcell'><input type='text' name='city' value="<?=htmlentities($cdata['city']);?>"/></div></div>
                                <div class='labelcell' >State/Province<div class='inputcell'><input type='text' style="width:242px;" name='state' value="<?=htmlentities($cdata['state']);?>"/></div></div>
                                <div class='labelcell' >Country<div class='inputcell'><input type='text' name='country' value="<?=htmlentities($cdata['country']);?>"/></div></div>
                                <div class='labelcell' >Postal Code<div class='inputcell'><input type='text' name='zip' value="<?=htmlentities($cdata['zip']);?>"/></div></div>
                                <div class='labelcell' >Time Zone<div class='inputcell'><?php include '../inc/tzselect.php';?></div></div>
		                	</div>
                            <div class="left" style="width:35%;">
                            	<h3 class="left">Contact Details</h3>
                                <hr class="title_line"/>
                                <div class="clear"></div>
                                <div class='labelcell' >Best way to contact<div class='inputcell'><input type='text' name='bwtc' value="<?=htmlentities($cdata['bwtc']);?>"/></div></div>
                                <div class='labelcell' >Business name<div class='inputcell'><input type='text' name='businessname' value="<?=htmlentities($cdata['businessname']);?>"/></div></div>
                                <div class='labelcell' >Mobile #<div class='inputcell'><input type='text' name='mobile' value="<?=htmlentities($cdata['mobile']);?>"/></div></div>
                                <div class='labelcell' >Job Title<div class='inputcell'><input type='text' name='jobtitle' value="<?=htmlentities($cdata['jobtitle']);?>"/></div></div>
                                <div class='labelcell' >Department<div class='inputcell'><input type='text' name='dept' value="<?=htmlentities($cdata['dept']);?>"/></div></div>
                                <div class='labelcell' >Industry<div class='inputcell'><input type='text' name='industry' value="<?=htmlentities($cdata['industry']);?>"/></div></div>
                                <div class='labelcell' >IM Handle<div class='inputcell'><input type='text' name='imhandle' style="width:150px;" value="<?=htmlentities($cdata['imhandle']);?>"/><?php include '../inc/imselect.php';?></select></div></div>
                                
                                
                            </div>
                            <div class="clear"></div>
                            <div class='labelcell' style="margin-left:80px;" >Notes<br/><div class='inputcell'><textarea name='notes' rows="6" style="width:560px;"><?=htmlentities($cdata['notes']);?></textarea></div></div>
		                	<div style="margin-left:80px; width:560px;">
                                <h3 class="left">Social Details</h3>
                                <hr class="title_line"/>
                                <div class="clear"></div>
                                <div style="padding:5px 18px;">
                                    <div class="left" style="margin-right:5px;">
                                    <?php if(strlen($cdata['linkedin'])){
										$li = htmlentities($cdata['linkedin']);
									}else{
										$li = "http://www.linkedin.com/in/";
									}?>
                                    <div class='labelcell' >LinkedIn URL<div class='inputcell'><input type='text' placeholder="http://www.linkedin.com/in/" name='linkedin' value="<?=$li?>"/></div></div>
                                    </div>
                                    <div class="left" style="margin-right:5px;">
                                    <?php if(strlen($cdata['facebook'])){
										$li = htmlentities($cdata['facebook']);
									}else{
										$li = "http://www.facebook.com/";
									}?>
                                    <div class='labelcell' >Facebook URL<div class='inputcell'><input type='text' placeholder="http://www.facebook.com/" name='facebook' value="<?=$li;?>"/></div></div>
                                    </div>
                                    <div class="clear"></div>
                                    <br/>
                                    <?php if(strlen($cdata['website'])){
										$li = htmlentities($cdata['website']);
									}else{
										$li = "http://";
									}?>
                                    <div class="left" style="margin-right:5px;">
                                    <div class='labelcell' >Website URL<div class='inputcell'><input type='text' placeholder="http://" name='website' value="<?=$li;?>"/></div></div>
                                    </div>
                                     <?php if(strlen($cdata['twitter'])){
										$li = htmlentities($cdata['twitter']);
									}else{
										$li = "@";
									}?>
                                    <div class="left" style="margin-right:5px;">
                                    <div class='labelcell' >Twitter<div class='inputcell'><input type='text' placeholder="@" name='twitter' value="<?=$li;?>"/></div></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
		                	<br/>
		                	
		                	<center><input type='submit' class="button blue_button" value='Save Contact Details'/></center>
		                
	                </form>
	                <iframe id='hidn' name='hidn' width='0' height='0' frameborder='0' src='about:blank'></iframe>
                </div>
            </div>
            <div class="tab_content" data-tab="activity" style="display:none;">                
                <div id="activity" class="scrollbar1">  	    
                   <?php include '../wall/wall.php'; echo getActivity('lead',20,$d['id']);?>
				    
                </div>
            </div>
            <div class="tab_content" data-tab="events" style="display:none;">
            	<a class="right button blue_button" href="javascript:void(0);" onClick="$.post('<?=$HOME_URL?>events/upload-pop.php?lead_id=<?=$d['id']?>',function(data){$.fancybox(data);});">Add Event</a><br clear="all" />
                <div class="scrollbar1">
                	<?php $datestamp = time(); $getUpcoming = mysql_query("SELECT id FROM events WHERE lead_id = {$d['id']} AND start_time > $datestamp ORDER BY start_time ASC",$con);
					if(intval(mysql_num_rows($getUpcoming))){?>
                        <h5>Upcoming Events</h5>
                        <?php 
                        	$ux =0;
                        	while($e = mysql_fetch_array($getUpcoming)){
                        	$ux++;
                            $event = new Event($e['id']);
                            include '../events/include.php';
                        } 
					}?>
					
                	<?php
					$getPast = mysql_query("SELECT id FROM events WHERE lead_id = {$d['id']} AND start_time < $datestamp ORDER BY start_time DESC",$con);
					if(intval(mysql_num_rows($getPast))){?>
                    	<h5>Past Events</h5>
                    	<?php 
                    		$pex = 0;
                    		while($e = mysql_fetch_array($getPast)){
                    		$pex++;
                            $event = new Event($e['id']);
                            include '../events/include.php';
                        }
					} ?>     
					<?php if($pex==0 and $ux==0){?>
                		<br/>
                		<center class="message" style="margin:0px;">There are no events associated with this lead.</center>
                	<?php } ?>         
                </div>
            </div>
        </div>
    </div>
    <script>init();</script>
    <div class="clear"></div>
</div>
 <?php include('../inc/footer.php'); ?>
</body>
</html>

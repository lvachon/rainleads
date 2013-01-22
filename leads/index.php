<?php include '../inc/trois.php';
loginRequired();
if(!verAccount()){errorMsg("No account associated with login");}
$acct = new Account(verAccount());
$viewer = new User(verCookie());
?>
<!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<script>

var assignData = [
	<?php 
	$cnt=0;
	foreach($acct->members as $u){
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
	$.post("assign.php",{result_id:id,user_id:$("#assign"+id).val()},function(data){
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
			onSelected: function(data){$.post("assign.php",{result_id:data.original[0].id.substring(6),user_id:data.selectedData.value},function(data){});}
			});
	}
});

function togglepipe(id){
	$.get("togpipe.php",{"result_id":id},function(data){
		if(data=="0"){
			$("#pipestar"+id).html('<img class="tip" title="Add to Pipeline" src="<?=$HOME_URL?>img/star-icon.png" />');
		}else{
			$("#pipestar"+id).html('<img class="tip" title="Remove from Pipeline" src="<?=$HOME_URL?>img/star-icon-on.png" />');
		}
	});
}

<?php if($_GET['pipe'] !=1){?>
$(function() {
 $("#lead-icon").attr({'src':'<?= $HOME_URL ?>img/lead-icon-on.png'});
 $('#leads-tab').addClass('current');
});
<?php }else{ ?>
$(function() {
 $("#star-side-icon").attr({'src':'<?= $HOME_URL ?>img/star-side-icon-on.png'});
 $('#pipeline-tab').addClass('current');
});
<?php } ?>

</script>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php'); ?>
        
    </div>
    <div id="main" class="left">
    	<?php if(intval($_GET['form_id'])){?>
	    	<div class="right">                        	                     
	        	<a class="button blue_button" href='javascript:void()' onclick='$.fancybox({"href":"../forms/showform.php?id=<?= $_GET['form_id'] ?>&manual=1&fancy=1","type":"iframe"});'>Create Lead</a>
	        </div>
	    <?php }else{ ?>
	    	<div class="right">                        	                     
	        	<a class="button blue_button" style="margin-left:5px;" href='javascript:void()' onclick="$.post('../forms/pickform.php?fancy=1',function(data){$.fancybox(data);});">Create Lead</a>
	        </div>
	    <?php } ?>
	    <div class="right">                        	                     
	        	<a class="button blue_button" href='javascript:void()' onclick='$.fancybox({"href":"import.php","type":"iframe","width":380,"maxHeight":"200","autoSize":"false"});'>Import Leads</a>
	        </div>
        <div id="recent_leads">
        	<?php
        	if(intval($_GET['form_id'])){$f = new Form($_GET['form_id']);echo "<h1>".trunc($f->data['title'],12)."'s leads</h1>";}else{
        		if(intval($_GET['delcon'])){
        			$f = new Form($_GET['form_id']);echo "<h1>Archived Leads</h1>";
        		}else{
        			if(intval($_GET['pipe'])){
        				echo "<h1>Pipeline leads</h1>";
        			}else{
        				echo "<h1>All leads</h1>";
        			}
        		}
        	}
        	?>
            <div class="right">            
            	<div id="filter"></div>
                <div id="sort"></div>    
                <div id='assfilter'></div> 
            </div>
            <div class="clear"></div>
            <br/>          
            <?php
            $con = conDB();
            if(intval($_GET['form_id'])){
            	$form = new Form($_GET['form_id']);
            	$fcon = " and form_id={$form->id} ";
            }
			$sort="datestamp desc";            
           	if($_GET['sort']=="new"){
           		$sort = "datestamp desc"; 
           	}
           	if($_GET['sort']=="old"){
           		$sort = "datestamp asc";
           	}
           	if($_GET['sort']=="alpha"){
           		$sort = "name asc";
           	}
           	if($_GET['sort']=="status"){
           		$sort = "(SELECT display from statuses where id=status) asc";
           	}
           	if($_GET['sort']=="code"){
           		$sort = "tracking_code asc";
           	}
           	if($_GET['sort']=="assignment"){
           		$sort = "(SELECT user_id from assignments where result_id=id) asc";
           	}
           	$filter = "";
           	if(intval($_GET['filter'])){
           		$filter = " and status=".intval($_GET['filter'])." ";
           	}
           	$assfilter = "";
           	if(intval($_GET['ass'])){
           		$assfilter = " and (SELECT user_id from assignments where result_id=id)=".strval(intval($_GET['ass']))." ";
           	}else{
           	
	           	/*if(intval($viewer->data['onlymyleads'])){
	           		$assfilter = " and (SELECT user_id from assignments where result_id=id)={$viewer->id}";
	           	}*/
           	}
           	
           	if(intval($_GET['pipe'])){
           		$pipecon = " and pipeline=1 ";
           	}
           	
           	if(strlen($_GET['q'])){
           		$q = mysql_escape_string($_GET['q']);
           		$qfilter = " and (name regexp '$q' or email regexp '$q')";
           	}
           	
           	$page = intval($_GET['page']);
           	if(!$page){$page = 1;}
           	$per_page=25;
           	$offset = ($page-1)*$per_page;
           	
           	$delcon = " and deleted=0 ";
           	if(intval($_GET['delcon'])){
           		$delcon= " and deleted=1 ";
           	}
           	
           	
            if(intval($acct->data['see_all_leads']) || in_array($viewer->id,$acct->admins)){
            	$qry = "form_results where length(name)>0 and length(email)>0 and form_id IN (SELECT id from forms where account_id={$acct->id}) {$fcon} {$filter} {$assfilter} {$qfilter} {$delcon} {$pipecon}";
            	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id and account_id={$acct->id}) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort} LIMIT $offset,$per_page",$con);
            }else{
            	$qry = "form_results where length(name)>0 and length(email)>0 and id IN (SELECT result_id from assignments where user_id={$viewer->id} and account_id={$acct->id}) {$fcon} {$filter} {$qfilter} {$delcon} {$pipecon}";
            	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id and account_id={$acct->id}) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort} LIMIT $offset,$per_page",$con);
            }
            
            $pgl = pageLinks($qry,$page,$per_page,"index.php?sort={$_GET['sort']}&filter={$_GET['filter']}&form_id={$_GET['form_id']}&delcon={$_GET['delcon']}&pipe={$_GET['pipe']}&q={$_GET['q']}&ass={$_GET['ass']}");
            echo "<!--{$acct->id}-->";
            while($lead = mysql_fetch_array($res)){
            	$tuser = new User($lead['assigned_user']);
            ?>
            <div class="lead_row"  >
                <table width="100%" cellpadding="5" cellspacing="0">
                    <tr valign="middle">
                    	<td onclick="document.location.href='lead.php?id=<?= $lead['id']; ?>'" valign="middle" class='id_cell' width="25" align="center"><small>#<?=$lead['display_id'];?></small></td> 
                        <td  valign="middle" class="star_cell" width="16" align="center"><a href='javascript:void(0);' onclick='togglepipe(<?=$lead['id'];?>);' id='pipestar<?=$lead['id'];?>'><img class="tip" title="<?php if(intval($lead['pipeline'])){?>Remove from<?php }else{?>Add to<?php } ?> Pipeline" src="<?=$HOME_URL?>img/star-icon<?php if(intval($lead['pipeline'])){echo "-on";}?>.png" /></a></td>
                        <td onclick="document.location.href='lead.php?id=<?= $lead['id']; ?>'" class="status_cell" valign="middle" align="left" width="80" >
                            <div class="status <?=$lead['statuscolor'];?>"><?=$lead['statustext'];?></div>
                        </td>
                        <td onclick="document.location.href='lead.php?id=<?= $lead['id']; ?>'" valign="middle" class="name_cell" width="304" align="left"><?=$lead['name'];?><br/><small><?=$lead['email'];?></small></td>
                        <td onclick="document.location.href='lead.php?id=<?= $lead['id']; ?>'" class="date_cell" valign="middle" align="right"><?=date("M jS @ g:ia",$lead['datestamp']);?></td>
                        <td valign="middle" align="" class="assign_cell" width="150">
                        	<?php if(in_array($viewer->id,$acct->admins) || intval($acct->data['assign_leads'])){
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
            	<?php if(isset($_GET['sort']) or isset($_GET['q']) or isset($_GET['filter']) or isset($_GET['form_id'])){?>
            		<div class="message"><span>No leads match those filters.</span><a href="javascript:void(0);" onclick='$.fancybox({"href":"../forms/pickform.php","type":"iframe","width":"600","auto-scale":true});'>Add a lead</a> or <a href="<?= $HOME_URL ?>forms/">Create a Form</a> for your website.</div>
            	<?php }else{ ?>
	            	<div class="message"><span>Oh no, you have no leads!</span><a href="javascript:void(0);"  onclick='$.fancybox({"href":"../forms/pickform.php","type":"iframe","width":"600"});'>Add a lead</a> or <a href="<?= $HOME_URL ?>forms/">Create a Form</a> for your website.</div>
            	<?php } ?>
            <?php } ?>
           
        </div>
		<div class="right" style="font-size:12px;margin:5px 0px;">
			<?php if($acct->membership!='free'){?>
				<a href='csv.php?<?=$_SERVER['QUERY_STRING'];?>'>Export current list to CSV</a> | 
			<?php } ?>
			<?php $getDeleted = mysql_query("SELECT COUNT(id) FROM form_results WHERE deleted =1 AND form_id IN (SELECT id from forms where account_id={$acct->id})",$con) or die(mysql_error());
			$deleted = mysql_fetch_array($getDeleted);
			if(!intval($_GET['delcon']) && intval($deleted[0])){?><a href='index.php?delcon=1'>Show archived leads</a><?php }elseif(intval($_GET['delcon'])){ ?><a href='index.php'>Show active leads</a><?php } ?>
		</div>
		<div class="clear"></div>
		<div class='pagination'><?=$pgl;?></div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<script src="/js/ddslick.js"></script>
    <script>
		var sortData = [
			{
				text: "Newest First",
				value: "new",
				selected: <?php $sortText = "Sort Leads";if($_GET['sort']=="new"){echo "true";$sortText="new";}else{echo "false";}?>				
			},
			{
				text: "Oldest First",
				value: "old",
				selected: <?php if($_GET['sort']=="old"){echo "true";$sortText="old";}else{echo "false";}?>
			},
			{
				text: "Alphabetically",
				value: "alpha",
				selected: <?php if($_GET['sort']=="alpha"){echo "true";$sortText="alpha";}else{echo "false";}?>
			},
			{
				text: "Status",
				value: "status",
				selected: <?php if($_GET['sort']=="status"){echo "true";$sortText="status";}else{echo "false";}?>
			},
			{
				text: "Assignment",
				value: "assignment",
				selected: <?php if($_GET['sort']=="assignment"){echo "true";$sortText="assignment";}else{echo "false";}?>
			}
			/*,
			{
				text: "Instance Name",
				value: "code",
				selected: <?php if($_GET['sort']=="code"){echo "true";$sortText="code";}else{echo "false";}?>
			}*/
		];
			var filterData = [
			                  {text:"All",value:0,selected:<?php if(!intval($_GET['filter']) && false){echo "true";}else{echo "false";}?>}
            	<?php 
         		if(!in_array($viewer->id,$acct->admins)){$adcon = " and admin!=1 ";}
         		$r = mysql_query("SELECT * from statuses where `show`=1 and account_id={$acct->id} {$adcon} order by display asc",$con);
         		$out = array();
         		$filterText = "Filter Leads";
         		while($state = mysql_fetch_array($r)){
         			$x = array('text'=>$state['title'],'value'=>$state['id'],'selected'=>false);
         			if($_GET['filter']==$state['id']){
         				$x['selected']=true;
         				$filterText = $state['title'];
         			}
         			$out[]=$x;
         		}
         		for($i=0;$i<count($out);$i++){
         			echo ",".json_encode($out[$i]);	
         		}
         		?>
         	];

			var assData = [
			                  {text:"All",value:0,selected:<?php if(!intval($_GET['ass']) && false){echo "true";}else{echo "false";}?>}
            	<?php 
         		$assText = "Assigned to:";
            	$r = mysql_query("SELECT user_id from membership where account_id={$acct->id} order by user_id asc",$con);
         		$out = array();
         		while($state = mysql_fetch_array($r)){
         			$ux = new User($state['user_id']);
         			$x = array('text'=>$ux->name("F l"),'value'=>$ux->id,'selected'=>false);
         			if($_GET['ass']==$ux->id){
         				$x['selected']=true;
         				$assText = $state['title'];
         			}
         			$out[]=$x;
         		}
         		for($i=0;$i<count($out);$i++){
         			echo ",".json_encode($out[$i]);	
         		}
         		?>
         	];
		$(function() {
		 	$('#sort').ddslick({
				data:sortData,
				width: 110,
				imagePosition: "left",
				selectText: "<?=$sortText;?>",
				onSelected: function (data) {
					if(data.selectedData.value!="<?=$_GET['sort'];?>"){document.location.href="index.php?page=<?=$_GET['page'];?>&form_id=<?=$_GET['form_id'];?>&sort="+data.selectedData.value+"&filter=<?=$_GET['filter'];?>&ass=<?=$_GET['ass'];?>&delcon=<?=$_GET['delcon'];?>";}
				}
			});
			$('#filter').ddslick({
    			data:filterData,
				width: 110,
				imagePosition: "left",
				selectText: "<?=$filterText;?>",
				onSelected: function (data) {
					if(data.selectedData.value!="<?=$_GET['filter'];?>"){document.location.href="index.php?page=<?=$_GET['page'];?>&form_id=<?=$_GET['form_id'];?>&filter="+data.selectedData.value+"&sort=<?=$_GET['sort'];?>&ass=<?=$_GET['ass'];?>&delcon=<?=$_GET['delcon'];?>";}
				}
			});
			if(assData.length>2){
				$('#assfilter').ddslick({
	    			data:assData,
					width: 110,
					imagePosition: "left",
					selectText: "<?=$assText;?>",
					onSelected: function (data) {
						if(data.selectedData.value!="<?=$_GET['ass'];?>"){document.location.href="index.php?page=<?=$_GET['page'];?>&form_id=<?=$_GET['form_id'];?>&ass="+data.selectedData.value+"&sort=<?=$_GET['sort'];?>&filter=<?=$_GET['filter'];?>&delcon=<?=$_GET['delcon'];?>";}
					}
				});
			}
			<?php 
			/*if(intval($_GET['ass'])==$viewer->id && !intval($viewer->data['dontaskleads'])){
       			?>$.fancybox({"href":"asspref.php","type":"ajax","width":"320","height":"240"});<?php
       		}*/
			?>
		});
		
	</script>
    <?php include('../inc/footer.php'); ?>
</body>
</html>

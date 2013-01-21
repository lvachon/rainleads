<?php include '../inc/trois.php';
loginRequired();
if(!verAccount()){errorMsg("No account associated with login");}
$account = new Account(verAccount());
$con = conDB();
?>
<!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php
	$con = conDB();
	$users = array();
	if(!intval($_GET['id'])){
		$qry="SELECT user_id from membership where account_id={$account->id}";
		$r = mysql_query($qry,$con);
		while($a = mysql_fetch_array($r)){
			$users[]=intval($a['user_id']);
		}
		//$users = implode(",",$users);
	}else{
		$users = intval($_GET['id']);
	}
	if(strlen($_GET['time_start'])){
		$time_start = intval($_GET['time_start']);
	}else{
		$time_start = 0;//time()-86400*7;
	}
	if(strlen($_GET['time_end'])){
		$time_end = intval($_GET['time_end']);
	}else{
		//$r = mysql_query("SELECT max(datestamp) from form_impressions where form_id IN ($forms)",$con);
		//$time_end = mysql_fetch_array($r);
		$time_end = time();//intval($time_end[0]);
	}
	//status graphs
	$r = mysql_query("SELECT * from statuses where account_id={$account->id} order by display asc",$con);
	$ss = array();
	$st = array();
	while($s = mysql_fetch_array($r)){
		$ss[]=array("id"=>$s['id'],"title"=>$s['title']);
		$st[$s['id']]=$s['title'];
	}
	$sdata=array();
	$sdatai=array();
	$tldata=array();
	$mdata=array();
	foreach($users as $user_id){
		$qry="SELECT count(*) as cnt,status from form_results where id in (SELECT result_id from assignments where user_id={$user_id} and account_id={$account->id}) and deleted=0 and datestamp >=$time_start and datestamp<=$time_end group by status";
		$r = mysql_query($qry,$con);
		while($res = mysql_fetch_array($r)){
			$sdata[$res['status']][$user_id]=$res['cnt'];
			$sdatai[$user_id][$res['status']]=$res['cnt'];
		}
		
		$qry="SELECT count(*) as cnt from form_results where id in (SELECT result_id from assignments where user_id={$user_id} and account_id={$account->id}) and deleted=0 and datestamp >=$time_start and datestamp<=$time_end";
		$r = mysql_query($qry,$con);
		$user = new User($user_id);
		while($res = mysql_fetch_array($r)){
			$tldata[$user->name("F l")]=intval($res['cnt']);
		}
		
		$qry="SELECT count(*) as cnt from actions where user_id ={$user_id} and account_id={$account->id} and datestamp >=$time_start and datestamp<=$time_end and type='milestone_saved'";
		$r = mysql_query($qry,$con);
		$user = new User($user_id);
		while($res = mysql_fetch_array($r)){
			$mdata[$user->name("F l")]=intval($res['cnt']);
		}
		
	}
	
?>
	<script>
		var sdatai = [['User'<?php foreach($ss as $s){echo ",\"{$s['title']}\"";}?>],
 			<?php foreach($sdatai as $user_id=>$data){
 					$user = new User($user_id);
 					echo "[\"".$user->name("F l")."\"";
 					foreach($ss as $s){
 						echo ",".strval(intval($data[$s['id']]));
 					}
 					echo "],";
 			}
 			?>
 		];
		var sdata = [['Status'<?php foreach($users as $u){$user = new User($u);echo ",\"".$user->name("F l")."\"";}?>],
   			<?php foreach($sdata as $status=>$data){
   					echo "[\"".$st[$status]."\"";
   					foreach($users as $u){
   						echo ",".strval(intval($data[$u]));
   					}
   					echo "],";
   			}
   			?>
   		];

		var tldata = [['User','Leads'],
    			<?php foreach($tldata as $name=>$leads){
    					echo "[\"".$name."\",{$leads}],";
    			}
    			?>
    		];

		var mdata = [['User','Milestones Completed'],
		<?php foreach($tldata as $name=>$leads){
				echo "[\"".$name."\",{$leads}],";
			}
		?>
		];
		
		
		google.load("visualization", "1", {packages:["corechart","geochart","table"]});
		var scharti,schart;
		var colors = ['#0280B9', '#02B97D','#B97902', '#B93902', ];
		$(document).ready(function(){
			<?php if(count($sdatai)>0){?>
				scharti = new google.visualization.ColumnChart(document.getElementById('charti_div'));
	        	scharti.draw(google.visualization.arrayToDataTable(sdatai),{
        		colors: colors,title:"",
        		width:775,
        		height:200,
        		chartArea:{width:'90%',height:'70%'},
        		legend:{position:'bottom'},
        		hAxis:{slantedTextAngle:90}
        	});
	        	scharti.isStacked=false;
			<?php }else{ ?>
				$("#charti_div").html("<center>No Data</center>");
			<?php } ?>
			
			<?php if(count($sdata)>0){?>
	        	schart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	        	schart.draw(google.visualization.arrayToDataTable(sdata),{
        		colors: colors,title:"",
        		width:775,
        		height:200,
        		chartArea:{width:'90%',height:'70%'},
        		legend:{position:'bottom'},
        		hAxis:{slantedTextAngle:90}
        	});
	        	schart.isStacked=false;
        	<?php }else{ ?>
				$("#chart_div").html("<center>No Data</center>");
			<?php } ?>
        	tlchart = new google.visualization.ColumnChart(document.getElementById('tlchart_div'));
        	tlchart.draw(google.visualization.arrayToDataTable(tldata),{
        		colors: colors,title:"",
        		width:775,
        		height:200,
        		chartArea:{width:'90%',height:'70%'},
        		legend:{position:'none'},
        		hAxis:{slantedTextAngle:90}
        	});
        	tlchart.isStacked=false;

        	mchart = new google.visualization.ColumnChart(document.getElementById('mchart_div'));
        	mchart.draw(google.visualization.arrayToDataTable(mdata),{
        		colors: colors,title:"",
        		width:775,
        		height:200,
        		chartArea:{width:'90%',height:'70%'},
        		legend:{position:'none'},
        		hAxis:{slantedTextAngle:90}
        	});
        	mchart.isStacked=false;
			//google.visualization.events.addListener(chart, 'select', selectHandler);

			
			
		});
		$(function() {
		 	$("#stats-icon").attr({'src':'<?= $HOME_URL ?>img/stats-icon-on.png'});
 			$('#stats-tab').addClass('current');
		});
	</script>
	<style>
	.graphdiv{
				
	}
	
	</style>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
    	<h1>Lead Statistics</h1>
    	<div class="segmented_tabs">
    		<form method='get' id="range" class="left" action='user_totals.php?id=<?=$_GET['id'];?>'>
	    		<select name='time_start' style="font-size:16px; margin-top:5px; margin-right:5px; padding:5px; outline:none !Important;" onchange="$('#range').submit();">
	    			<option value='0'>All time</option>
	    			<option value='<?=mktime(0,0,0)-86400*7;?>' <?php if($time_start==mktime(0,0,0)-86400*7){echo "selected";} ?> >Last 7 days</option>
	    			<option value='<?=mktime(0,0,0)-86400*30;?>'  <?php if($time_start==mktime(0,0,0)-86400*30){echo "selected";} ?> >Last 30 days</option>
	    			<option value='<?=mktime(0,0,0)-86400*180;?>'  <?php if($time_start==mktime(0,0,0)-86400*180){echo "selected";} ?> >Last 180 days</option>
	    			<option value='<?=mktime(0,0,0,date("n"),1,date("Y"));?>'  <?php if($time_start==mktime(0,0,0,date("n"),1,date("Y"))){echo "selected";} ?> >This Month</option>
	    			<option value='<?=mktime(0,0,0,1,1,date("Y"));?>'  <?php if($time_start==mktime(0,0,0,1,1,date("Y"))){echo "selected";} ?> >This Year</option>
	    		</select>
	    		<input type='hidden' name='time_end' value='<?=time();?>'/>    		
	    	</form>
			<div class="segment left " onclick="document.location.href='form_totals.php'">
				Form Totals
			</div>
			<div class="segment right active" onclick="document.location.href='user_totals.php'">
				Team Totals
			</div>
		</div>
    	<div class="clear"></div>
    	<br/>    	
		<div class="collapse_graph">
			<div class="title">Total Leads</div>
			<div id='tlchart_div' class='graphdiv'></div>
		</div>
		<div class="collapse_graph">
			<div class="title">Leads by Status</div>
			<div id='chart_div' class='graphdiv'></div>
		</div>
		
		<div class="collapse_graph">
			<div class="title">Leads by Team Members</div>
			<div id='charti_div' class='graphdiv'></div>
		</div>
		<div class="collapse_graph">
			<div class="title">Milestones Completed</div>
			<div id='mchart_div' class='graphdiv'></div>
		</div>
		
	</div>
	<div class="clear"></div>
</div>
<?php include('../inc/footer.php'); ?>
</body>
</html>

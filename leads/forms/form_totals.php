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
	<script>
		google.load("visualization", "1", {packages:["corechart"]});
		var gdata,chart;
		$(document).ready(function(){
			chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
        	chart.draw(google.visualization.arrayToDataTable(gdata), {title:"Impressions and Submissions"});
			google.visualization.events.addListener(chart, 'select', selectHandler);
		});
	</script>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
		<?php
			$con = conDB();
			$time_start = 0;
			$time_end = time();
			$statfilter="";
			$r = mysql_query("SELECT * from form_impressions where form_id IN (SELECT id from forms where account_id={$account->id}) order by datestamp asc",$con);
			$gdata = array(array());
			while($i = mysql_fetch_array($r)){
				//if(!is_array($gdata[date("M/j",$i['datestamp'])])){$gdata[date("M/j",$i['datestamp'])]=array('impressions'=>array(),'submissions'=>array());}
				$gdata[date("M/j",$i['datestamp'])]['impressions'][]=array($i['ip'],$i['referer']);
			}
			$r = mysql_query("SELECT * from form_results where form_id IN (SELECT id from forms where account_id={$account->id}) order by datestamp asc",$con);
			//$gdata = array();
			while($i = mysql_fetch_array($r)){
				//if(!is_array($gdata[date("M/j",$i['datestamp'])]['submissions'])){$gdata[date("M/j",$i['datestamp'])]['submissions']=array();}
				$gdata[date("M/j",$i['datestamp'])]['submissions'][]=array($i['ip'],$i['referer']);
			}
		?>
		<script>
			var gdata = [{'date','impressions','submissions'}
			<?php foreach($gdata as $datestamp=>$row){
				echo ",{'".$datestamp."',".count($row['impressions']).",".count($row['submissions'])."}\n";
			}?>;
		</script>
		<div id='chart_div' style='width:800px;height:600px;'></div>
	</div>
</div>
</body>
</html>

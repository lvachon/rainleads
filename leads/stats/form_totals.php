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
	$forms = array();
	if(!intval($_GET['id'])){
		$r = mysql_query("SELECT id from forms where account_id={$account->id} and deleted=0",$con);
		while($a = mysql_fetch_array($r)){
			$forms[]=$a['id'];
		}
		$forms = implode(",",$forms);
	}else{
		$forms = intval($_GET['id']);
	}
	if(strlen($_GET['time_start'])){
		$time_start = intval($_GET['time_start']);
	}else{
		$time_start = mktime(0,0,0)-86400*7;
	}
	if(strlen($_GET['time_end'])){
		$time_end = intval($_GET['time_end']);
	}else{
		//$r = mysql_query("SELECT max(datestamp) from form_impressions where form_id IN ($forms)",$con);
		//$time_end = mysql_fetch_array($r);
		$time_end = time();//intval($time_end[0]);
	}
	$statfilter="";
	$time_step=86400;
	if($time_end-$time_start>86400*31){$time_step=86400*7;}
	$gdata = array();
	$sdata = array();
	$total_subs = 0;
	$total_impressions=0;
	$total_pipes=0;
	for($i=$time_start;$i<=$time_end;$i+=$time_step){
		$r = mysql_query("SELECT * from form_impressions where form_id IN ($forms) and datestamp >= $i and datestamp < $i+$time_step",$con);
		$gdata[$i]['impressions'] = array();
		$gdata[$i]['submissions'] = array();
		while($a = mysql_fetch_array($r)){
			$gdata[$i]['impressions'][]=array('ip'=>$a['ip'],'referer'=>$a['referer']);
			$total_impressions++;
		}
		$r = mysql_query("SELECT *,(SELECT title from statuses where id=status) as statustext from form_results where form_id IN ($forms)  and datestamp >= $i and datestamp <  $i+$time_step",$con);
		while($a = mysql_fetch_array($r)){
			$gdata[$i]['submissions'][]=array('ip'=>$a['ip'],'referer'=>$a['referer'],'status'=>$a['status']);
			$sdata[$a['statustext']]+=1;
			$total_subs++;
			if($a['pipeline']){$total_pipes++;}
		}
	}
	
?>
	<script>
		var gdata = [['date','Submissions','Impressions','Conversion rate'],
 			<?php foreach($gdata as $datestamp=>$row){
 				$subs = intval(count($row['submissions']));
 				$imps = intval(count($row['impressions']));
 				if($imps>0){$rate = $subs/$imps;}else{$rate=0;}
 				echo "['".date("M/j",$datestamp)."',$subs,$imps,$rate],\n";
 			}?>];
		<?php
		$mdata = array();
		$rdata = array();
		$con = conDB();
		foreach($gdata as $datestamp=>$row){
			foreach($row['impressions'] as $x){
				$ip = $x['ip'];
				$ref = $x['referer'];
				if(!strlen(trim($ref))){$ref="[None]";}
				$rdata[$ref]+=1;
				
				$r = mysql_query("SELECT * from ip_location where ip='".mysql_escape_string($ip)."' LIMIT 1",$con);
				$ll = mysql_fetch_array($r);
				if(!intval($ll['id'])){
					$res = json_decode(file_get_contents("http://freegeoip.net/json/{$ip}"));
					$mdata[$res->country_name]['imps']+=1;
					mysql_query("INSERT INTO ip_location(ip,lat,lon,country) VALUES('".mysql_escape_string($ip)."',".floatval($res->latitude).",".floatval($res->longitude).",'".mysql_escape_string($res->country_name)."')",$con);
				}else{
					$mdata[$ll['country']]['imps']+=1;	
				}
			}
			foreach($row['submissions'] as $x){
				$ip = $x['ip'];
				$ref = $x['referer'];
				if(!strlen(trim($ref))){
					$ref="[None]";
				}
				$rdata[$ref]+=1;
			
				$r = mysql_query("SELECT * from ip_location where ip='".mysql_escape_string($ip)."' LIMIT 1",$con);
				$ll = mysql_fetch_array($r);
				if(!intval($ll['id'])){
					$res = json_decode(file_get_contents("http://freegeoip.net/json/{$ip}"));
					$mdata[$res->country_name]['subs']+=1;
					mysql_query("INSERT INTO ip_location(ip,lat,lon,country) VALUES('".mysql_escape_string($ip)."',".floatval($res->latitude).",".floatval($res->longitude).",'".mysql_escape_string($res->country_name)."')",$con);
				}else{
					$mdata[$ll['country']]['subs']+=1;
				}
			}
		}
		arsort($rdata);
		arsort($mdata);
		?>
		var mdata = [['Country','Impressions','Submissions'],
		  			<?php foreach($mdata as $country=>$count){
		  				echo "['$country',".strval(intval($count['imps'])).",".strval(intval($count['subs']))."],\n";
		  			}?>];

		var rdata = [['Referrer','Impressions'],
			  			<?php foreach($rdata as $ref=>$count){
			 				echo "['$ref',".strval(intval($count))."],\n";
			  			}?>];
		var sdata = [['Status','Leads'],
			  			<?php foreach($sdata as $ref=>$count){
			 				echo "['$ref',".strval(intval($count))."],\n";
			  			}?>];

		var pdata = [['Pipeline','Leads'],['Yes',<?=intval($total_pipes);?>],['No',<?=strval(intval($total_subs) - intval($total_pipes));?>]];
		google.load("visualization", "1", {packages:["corechart","geochart","table"]});
		var gdata,chart,mdata,map,mapt,refs,pie,pipe;
		var cssClassNames = {headerRow: 'bigAndBoldClass', hoverTableRow: 'highlightClass'};
		$(document).ready(function(){
			chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        	chart.draw(google.visualization.arrayToDataTable(gdata),{
        		width:768,
                legend:{position:'bottom'},
           	 	hAxis:{slantedTextAngle:90},
           	 	seriesType:"bars",
           	 	chartArea:{width:'90%',height:'70%'},
           	 	series: {2:{type:"line"}},
           	 	
           	});
        	chart.isStacked=false;
			//google.visualization.events.addListener(chart, 'select', selectHandler);

			map = new google.visualization.GeoChart(document.getElementById('map_div'));
        	map.draw(google.visualization.arrayToDataTable(mdata),{width:770,chartArea:{width:'100%',height:'70%'}});

        	mapt = new google.visualization.Table(document.getElementById('map_table'));
        	mapt.draw(google.visualization.arrayToDataTable(mdata),{width:'760px',showRowNumber:true,cssClassNames:cssClassNames,tableArea:{width:'90%',left:'10px',height:'70%'},});

        	refs = new google.visualization.Table(document.getElementById('ref_div'));
        	refs.draw(google.visualization.arrayToDataTable(rdata),{width:'760px',showRowNumber:true,cssClassNames:cssClassNames,tableArea:{width:'90%',height:'70%'},});

        	pie = new google.visualization.PieChart(document.getElementById('status_div'));
        	pie.draw(google.visualization.arrayToDataTable(sdata),{width:380,chartArea:{width:'100%',height:'70%'}});

        	pipe = new google.visualization.PieChart(document.getElementById('pipe_div'));
        	pipe.draw(google.visualization.arrayToDataTable(pdata),{width:380,chartArea:{width:'100%',height:'70%'}});
			
		});
		$(function() {
		 	$("#stats-icon").attr({'src':'<?= $HOME_URL ?>img/stats-icon-on.png'});
 			$('#stats-tab').addClass('current');
		});
	</script>
	<style>
	.tablediv{width:700px;}
	.stat{margin-top:20px;border:solid 1px #DADADA;}
	table tr td {border:1px solid #f0f0f0; padding:5px; font-size:13px; color:#000}
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
			<div class="segment left active" onclick="document.location.href='form_totals.php'">
				Form Totals
			</div>
			<div class="segment right" onclick="document.location.href='user_totals.php'">
				Team Totals
			</div>
		</div>
    	<div class="clear"></div>
    	<br/>

    	<form method='get' action='form_totals.php?id=<?=$_GET['id'];?>'>
    		Time Range:<select name='time_start'>
    			<option value='<?=mktime(0,0,0)-86400*7;?>' <?php if($time_start==mktime(0,0,0)-86400*7){echo "selected";} ?> >Last 7 days</option>
    			<option value='<?=mktime(0,0,0)-86400*30;?>'  <?php if($time_start==mktime(0,0,0)-86400*30){echo "selected";} ?> >Last 30 days</option>
    			<option value='<?=mktime(0,0,0)-86400*180;?>'  <?php if($time_start==mktime(0,0,0)-86400*180){echo "selected";} ?> >Last 180 days</option>
    			<option value='<?=mktime(0,0,0,date("n"),1,date("Y"));?>'  <?php if($time_start==mktime(0,0,0,date("n"),1,date("Y"))){echo "selected";} ?> >This Month</option>
    			<option value='<?=mktime(0,0,0,1,1,date("Y"));?>'  <?php if($time_start==mktime(0,0,0,1,1,date("Y"))){echo "selected";} ?> >This Year</option>
    		</select>
    		<input type='hidden' name='time_end' value='<?=time();?>'/>
    		<label>Form:
			<select name='id'>
			<option value="">All Forms</option>
			<?php 
				$r = mysql_query("SELECT * from forms where account_id = {$account->id} and deleted=0 order by datestamp desc",$con);
				while($f = mysql_fetch_array($r)){
					$form = new Form($f['id']);
					?><option value="<?=$form->id;?>" <?php if($form->id==intval($_GET['id'])){echo "selected";}?>><?=$form->data['title'];?></option>
					<?php
				}
			?>
			</select></label>
    		<input type='submit' value='Filter'/>
    	</form>
		<div class='stat collapse_graph'>
			<div class="title">Impressions and submissions over time</div>
			<div id='chart_div' class='graphdiv'></div>
			<div class='tablediv'>
				<table style=" margin:0 15px; width:740px;">
					<tr><td>Total Impressions:</td><td><?=$total_impressions;?></td></tr>
					<tr><td>Total Submissions:</td><td><?=$total_subs;?></td></tr>
					<tr><td>Average Conversion Rate:</td><td><?=floor(1000*$total_subs/$total_impressions)/10;?>%</td></tr>
				</table>
			</div>
		</div>
		<div class='stat collapse_graph'>
			<div class="title">Top Countries</div>
			<div id='map_div' class='graphdiv'></div>
			<div id='map_table' class='tablediv' style="margin-left:10px;"></div>
		</div>
		<div class='stat collapse_graph'>
			<div class="title">Top Referring Websites</div>
			<div id='ref_div' class='tablediv' style="margin:10px; margin-bottom:0px;"></div>
		</div>
		<div class='stat collapse_graph left' style="margin-right:10px; margin-top:0px;">
			<div class="title">Status Breakdown</div>
			<div id='status_div' class='graphdiv'></div>
		</div>
		<div class='stat collapse_graph right' style="margin-top:0px;">
			<div class="title">Pipeline Breakdown</div>
			<div id='pipe_div' class='graphdiv''></div>
		</div>
	</div>
	<Div class="clear"></DIV>
</div>
<?php include '../inc/footer.php'; ?>
</body>
</html>

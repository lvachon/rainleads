<?php include '../inc/trois.php';
loginRequired();
$account = new Account(verAccount());
$user = new User(verCookie());
if($account->user_id!=$user->id){errorMsg("Only the account administrator can modify the microsite");}
$forms = mysql_query("SELECT * from forms where account_id = {$account->id} and deleted=0 order by datestamp desc",$con);
?>
<html>
<?php include('../inc/head.php'); ?>
<style>
	span.strong {
		font-size:13px;
		color:#666;
		display: block;
		margin-bottom: 5px;
	}
	.setting {
		border-bottom: 1px solid #d8d8d8;
		padding:15px 0;
	}
	.split-input {
		border: 1px solid #c0c0c0;
		background: #f0f0f0;
		padding: 0px 0 0 5px;
		height: 26px;
		line-height: 24px;
		font-size: 13px;
		color:#999;
		margin-top:5px;
	}
	.split-input span {
		
		
	}
	.split-input input {
		border: none;
		height: 26px;
		line-height: 24px;
		position: relative;
		left:2px;
		padding: 5px;
		outline:none !Important;
		border-left:1px solid #c0c0c0;
	}
</style>
<script>
$(function() {
 	$("#globe-icon").attr({'src':'<?= $HOME_URL ?>img/globe-icon-on.png'});
	$('#microsite-tab').addClass('current');
});
</script>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
    	<h1>Manage your Microsite</h1>
    	<div class="clear"></div>
    	<p class="caption">Your RainLeads microsite is a simple one page website where you can feature your business information and a custom form of your choice to help you generate more leads! Set up takes only a few moments.</p>
    	<br/>
    	<?php if($account->membership!="pro" && false){?>
	    	<!-- If the account is not pro -->
	    	<div class="message"><span>Your microsite will not become available to the public without a pro subscription.</span> <a class="button green_button" style="width:120px; margin:auto;" href="#">Upgrade Now</a></div>
	    	<br/>
	    <?php }else{
	    	if(!intval(mysql_num_rows($forms))){?>
		    	<!-- If the account has no forms -->
		    	<div class="message"><span>Before you generate your microsite</span> <a class="button blue_button" style="width:120px; margin:auto;" href="<?= $HOME_URL ?>forms/">Create a Form</a></div>
		    	<div class="clear"></div>
		    	<br/>
	    	<?php }else{ ?>
	    	
		    	<div class="tab_content" style="border-top:1px solid #c8c8c8; height:auto; min-height:0px; padding-top:5px;">
	    		<form method='post' action='save.php' id='frm'>
		    	<div class="setting">
			    	<div class="left">
				    	<span class="strong">Choose a Contact Form:</span>
				    	<select style="font-size:16px;" name='form'>
				    		<?php while($row = mysql_fetch_array($forms)){
				    			$form = new Form($row['id']);
				    		?>
				    			<option value="<?= $form->id ?>" <?php if($account->data['microsite_form']==$row['id']){echo "selected";}?>><?=$form->data['title'];?></option>
				    		<?php } ?>
				    	</select>
			    	</div>
			    	<div class="right" style="width:350px; padding-top:8px; line-height:16px;">
			    		<span class="small">This is the form that will be embedded on your Microsite, and will be what visitors to the site will interact with.</span>
			    	</div>
			    	<div class="clear"></div>
		    	</div>
		    
		    	<div class="setting">
			    	<div class="left">
			    		<span class="strong">Setup your Custom URL:</span>
				    	<table><tr><td>
					    	<div class="split-input">
					    		<span>http://coba.se/<span id='msholder'><?=$account->data['microsite_url'];?></span></span>
					    		<input type="text" name='url' value="<?=htmlentities($account->data['microsite_url']);?>" <?php if(strlen($account->data['microsite_url']) || $account->membership=="free"){echo "style='display:none;'";}?> id='msurl'/>
					    	</div>
				    	</td><td>
					    	<?php if(strlen($account->data['microsite_url']) && $account->membership!="free"){?>
				    			<small style='float:right;'><a href='javascript:void(0)' onclick='if(confirm("Editing your microsite URL will break any existing links and search results.  Are you sure you want to do that?")){$("#msurl").show();$("#msholder").hide();$(this).hide();}'>[edit]</a></small>
				    		<?php
				    		} ?>
			    		</td></tr></table>
			    	</div>
			    	<div class="right" style="width:350px; padding-top:8px; line-height:16px;">
			    		<span class="small">
			    			This URL is what the public will navigate to in order to submit a lead.
			    			<?php if($account->membership=="free"){?>
			    				<br/><br/>Free accounts cannot activate their microsite by setting a URL.  Please 
			    				<a href='/account/upgrade.php'>Upgrade.</a>
			    			<?php }?> 
			    		</span>
			    	</div>
			    	<div class="clear"></div>
		    	</div>
		    	<div class="setting">
			    	<div class="left" style="width:350px;">
				    	<span class="strong">Edit your Microsite:</span>
				    	<a class="button left" href='edit.php' >Edit Details</a><span style="position:relative; color:#777; top:9px; font-size:13px;">Last edited <?= timeAgo(intval($account->data['MDlastedit'])) ?></span><div class="clear"></div>
			    	</div>
			    	<div class="right" style="width:350px; padding-top:8px; line-height:16px;">
			    		<span class="small">Edit the company description, services, address and contact information that appears on the Microsite</span>
			    	</div>
			    	<div class="clear"></div>
		    	</div>
		    	
		    	<table width='100%'>
		    		<tr><td width='50%' align='center'>
		    			<div class="button blue_button" style="width:100px; margin-top:15px;" onclick='$("#frm")[0].submit();'>Save Microsite</div>
		    		</td><td width='50%' align='center'>
		    			<div class="button blue_button" style="width:100px; margin-top:15px;" onclick='document.location.href="http://www.coba.se/<?=$account->data['microsite_url'];?>";'>View Microsite</div>
		    		</td></tr>
				</table>	    	
		    	</form>
		    	</div>
		    	<br/>
		    	<div class="tab_content" style="border-top:1px solid #c8c8c8; min-height:0px; padding-top:5px;">
		    	<div class="setting">
			    	<div class="left" style="width:350px;">
				    	<span class="strong">Share your Microsite:</span>
				    	<a href="http://www.facebook.com/sharer.php?u=http://coba.se/<?=htmlentities($account->data['microsite_url']);?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=270,width=600');return false;" class="button left" style="padding:5px 5px 3px 5px; text-shadow:#fff 0 1px 0;" target="_blank">
				    		<img src="../img/icons/facebook.png" /><span style="position:relative; top:-3px;"> Facebook</span>
				    	</a>
				    	<a href="https://twitter.com/intent/tweet?url=http://coba.se/<?=htmlentities($account->data['microsite_url']);?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=257,width=600');return false;" class="button left" style="padding:5px 5px 3px 5px; text-shadow:#fff 0 1px 0;" target="_blank">
				    		<img src="../img/icons/twitter.png" /><span style="position:relative; top:-3px;"> Twitter</span>
				    	</a>
				    	<a  href="https://plus.google.com/share?url=http://coba.se/<?=htmlentities($account->data['microsite_url']);?>" class="button left" style="padding:5px 5px 3px 5px; text-shadow:#fff 0 1px 0;" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank">
				    		<img src="../img/icons/google.png" /><span style="position:relative; top:-3px;"> Google+</span>
				    	</a>
			    	</div>
			    	<div class="right" style="width:350px; padding-top:8px; line-height:16px;">
			    		<span class="small">Share your Microsite! You want as many potential clients to see this microsite as possible. Share, like, tweet, get it out there!</span>
			    	</div>
			    	<div class="clear"></div>
		    	</div>
		    	<div class="setting">
			    	<span class="strong">Total Microsite Impressions:</span>
			    	<?php
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
					$total_subs = 0;
					$total_impressions=0;
					for($i=$time_start;$i<=$time_end;$i+=$time_step){
						$r = mysql_query("SELECT * from form_impressions where form_id IN (SELECT id from forms where account_id={$account->id} and deleted=0) and datestamp >= $i and datestamp < $i+$time_step and tracking_code=\"microsite\"",$con);
						$gdata[$i]['impressions'] = array();
						$gdata[$i]['submissions'] = array();
						while($a = mysql_fetch_array($r)){
							$gdata[$i]['impressions'][]=array('ip'=>$a['ip'],'referer'=>$a['referer']);
							$total_impressions++;
						}
						$r = mysql_query("SELECT *,(SELECT title from statuses where id=status) as statustext from form_results where form_id IN ($forms)  and datestamp >= $i and datestamp <  $i+$time_step",$con);
						while($a = mysql_fetch_array($r)){
							$gdata[$i]['submissions'][]=array('ip'=>$a['ip'],'referer'=>$a['referer'],'status'=>$a['status']);
							$total_subs++;
						}
					}
			    	?>
			    	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			    	<script>
					var gdata = [['date','Submissions','Impressions','Conversion rate'],
			 			<?php foreach($gdata as $datestamp=>$row){
			 				$subs = intval(count($row['submissions']));
			 				$imps = intval(count($row['impressions']));
			 				if($imps>0){$rate = $subs/$imps;}else{$rate=0;}
			 				echo "['".date("M/j",$datestamp)."',$subs,$imps,$rate],\n";
			 			}?>];
		 			google.load("visualization", "1", {packages:["corechart"]});
					var gdata,chart;
					$(document).ready(function(){
						chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
			        	chart.draw(google.visualization.arrayToDataTable(gdata),
			               {width:768,legend:{position:'bottom'},
			           	 	hAxis:{slantedTextAngle:90},
			           	 	seriesType:"bars",
			           	 	series: {2:{type:"line"}}
			           	});
			        	chart.isStacked=false;
			      	});
			      	</script>
			      	<form method='get' action='index.php'>
			    		<select name='time_start'>
			    			<option value='<?=mktime(0,0,0)-86400*7;?>' <?php if($time_start==mktime(0,0,0)-86400*7){echo "selected";} ?> >Last 7 days</option>
			    			<option value='<?=mktime(0,0,0)-86400*30;?>'  <?php if($time_start==mktime(0,0,0)-86400*30){echo "selected";} ?> >Last 30 days</option>
			    			<option value='<?=mktime(0,0,0)-86400*180;?>'  <?php if($time_start==mktime(0,0,0)-86400*180){echo "selected";} ?> >Last 180 days</option>
			    			<option value='<?=mktime(0,0,0,date("n"),1,date("Y"));?>'  <?php if($time_start==mktime(0,0,0,date("n"),1,date("Y"))){echo "selected";} ?> >This Month</option>
			    			<option value='<?=mktime(0,0,0,1,1,date("Y"));?>'  <?php if($time_start==mktime(0,0,0,1,1,date("Y"))){echo "selected";} ?> >This Year</option>
			    		</select>
			    		<input type='hidden' name='time_end' value='<?=time();?>'/>
			    		<input type='submit' value='Filter'/>
			    	</form>
			      	<div id='chart_div' style='height:400px;'></div>
			   	</div>
    		<?php }
	    	} ?>
    	</div>
    </div>
    <div class="clear"></div>
</div>

    
    <p id='msg'></p>

    <script> 
      

      
    
    </script>
<?php include('../inc/footer.php'); ?>
</body>
</html>
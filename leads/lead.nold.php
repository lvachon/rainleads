<?php include '../inc/trois.php';
loginRequired();
if(!verAccount()){errorMsg("No account associated with login");}
$id = intval($_GET['id']);
$f = new Form();
$con = conDB();
$r = mysql_query("SELECT * from form_results where id=$id",$con);
$d = mysql_fetch_array($r);
if(!intval($d['id'])){die("NO DATA");}
$f->elems = unserialize($d['data']);
for($i=0;$i<count($f->elems);$i++){
	$f->elems[$i]->init();
}

?>
<!DOCTYPE html>
<html>
<head>
	<link type="text/css" rel="stylesheet" media="screen" href="http://www.rainleads.com/2.0/css/style.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="./js/ddslick.js"></script>
    <script>
		var stateData = [
			{
				text: "Pending",
				value: "Pending",
				selected: false				
			},
			{
				text: "Active",
				value: "Active",
				selected: false
			},
			{
				text: "Won",
				value: "Won",
				selected: false
			},
			{
				text: "Dead",
				value: "Dead",
				selected: false
			}
		];
		var statusSelect = "Set Status";
		for(var i=0;i<stateData.length;i++){
			if(stateData[i].value=="<?=$d['status'];?>"){stateData[i].selected=true;statusSelect=stateData[i].value;}
		}
		var assignData = [
			{
				text: "Fuchsia M.",
				value: 1,
				selected: false
			},
			{
				text: "Makanga N.",
				value: 2,
				selected: false
			},
			{
				text: "Susan M.",
				value: 3,
				selected: false
			},
			{
				text: "Kurt V.",
				value: 4,
				selected: false
			}
		];
		var glbl;
		$(function() {
		 	$('#state').ddslick({
				data:stateData,
				width: 110,
				imagePosition: "left",
				selectText: statusSelect,
				onSelected: function (data) {
					$.post("formstatus.php",{id:<?=$d['id'];?>,status:data.selectedData.value},function(data){});
				}
			});
			$('#assign').ddslick({
    			data:assignData,
				width: 120,
				imagePosition: "left",
				selectText: "Assign Lead",
				onSelected: function (data) {
					console.log(data);
				}
			});

			$.get("showresult.php?id=<?=$_GET['id'];?>",function(data){
				$("#form_fields").html(data);
			});
		});
	</script>
    <title>RainLeads - Dead Simple Lead Management. Srsly</title>    
</head>
<body>
<div id="lead_content">
	<div class="left">    
        <h2><?=$f->getElemByName('name')->value;?></h2>
        <h3><?=$f->getElemByName('email')->value;?></h3>
        <br/>
        
        <br/>
        <div class="left facebook social_link"><img src="img/facebook-icon.png" /> Facebook Profile </div>
        <div class="left linkedin social_link off"><img src="img/linkedin-icon-off.png" /> LinkedIn Profile</div>
        <div class="clear"></div>
    </div>
    <div class="right" id="milestone_list">
    	<h5>Milestones</h5>
        <div class="milestone-list">
        	<table width="100%" cellpadding="3">
            	<tr class="active" valign="middle">
                	<td valign="middle" width="10"><input type="checkbox" checked="checked" value="milestone_1"/ ></td>
                    <td valign="middle">Initial Callback</td>
                </tr>
                <tr>
                	<td width=""><input type="checkbox" value="milestone_2"/ ></td>
                    <td valign="middle">Sent Info Packet</td>
                </tr>
                <tr>
                	<td width=""><input type="checkbox" value="milestone_2"/ ></td>
                    <td valign="middle">Milestone</td>
                </tr>
                <tr>
                	<td width=""><input type="checkbox" value="milestone_2"/ ></td>
                    <td valign="middle">Milestone</td>
                </tr>
                <tr>
                	<td width=""><input type="checkbox" value="milestone_2"/ ></td>
                    <td valign="middle">Milestone</td>
                </tr>              
            </table>	
        </div>
    </div>
    <div class="clear"></div>
   
    <div class="clear"></div>
    	<div id="state"></div>
        <div id="assign"></div>
    <br/>
    <div class="clear"></div>
    <br/>
    <h1>Lead Details</h1>
    <div class="clear"></div>
    <div id='form_fields'>
    	<?= $f->getResultHTML();?>
    </div>
    <h1>Lead Activity</h1>
    <div class="clear"></div>
    <div id="activity" class="scrollbar1">  	    
        <div class="activity">
            <table cellpadding="2" width="100%">
                <tr>
                    <td rowspan="3" width="30"><img src="avatar/1.jpg" width="24" height="24" /></td>
                    <td valign="top"><span class="name">Kurt Vachon</span> completed a milestone.</td>
                </tr>
                <tr>
                    <td class="small">"Set up phone call" - Lead: <a class="lead-link">Amanda A McApple&hellip;</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="left"><small class="timestamp">4 Hours Ago</small></div>
                        <div class="right"><img src="img/comment-icon.png" id="comments" /><span class="comment_count">10</span></div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="activity">
            <table cellpadding="2" width="100%">
                <tr>
                    <td rowspan="3" width="30"><img src="avatar/1.jpg" width="24" height="24" /></td>
                    <td valign="top"><span class="name">Kurt Vachon</span> completed a milestone.</td>
                </tr>
                <tr>
                    <td class="small">"Set up phone call" - Lead: <a class="lead-link">Amanda A McApple&hellip;</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="left"><small class="timestamp">4 Hours Ago</small></div>
                        <div class="right"><img src="img/comment-icon.png" id="comments" /><span class="comment_count">10</span></div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="activity">
            <table cellpadding="2" width="100%">
                <tr>
                    <td rowspan="3" width="30"><img src="avatar/1.jpg" width="24" height="24" /></td>
                    <td valign="top"><span class="name">Kurt Vachon</span> completed a milestone.</td>
                </tr>
                <tr>
                    <td class="small">"Set up phone call" - Lead: <a class="lead-link">Amanda A McApple&hellip;</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="left"><small class="timestamp">4 Hours Ago</small></div>
                        <div class="right"><img src="img/comment-icon.png" id="comments" /><span class="comment_count">10</span></div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="activity">
            <table cellpadding="2" width="100%">
                <tr>
                    <td rowspan="3" width="30"><img src="avatar/1.jpg" width="24" height="24" /></td>
                    <td valign="top"><span class="name">Kurt Vachon</span> completed a milestone.</td>
                </tr>
                <tr>
                    <td class="small">"Set up phone call" - Lead: <a class="lead-link">Amanda A McApple&hellip;</a></td>
                </tr>
                <tr>
                    <td>
                        <div class="left"><small class="timestamp">4 Hours Ago</small></div>
                        <div class="right"><img src="img/comment-icon.png" id="comments" /><span class="comment_count">10</span></div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </table>
        </div>         
    </div>
</div>
</body>
</html>

<?php include '../inc/trois.php';
loginRequired();
if (intval($_GET['id'])) { $user = new User(intval($_GET['id'])); } else { $user = new User($viewer->id); }
$con = conDB();
$molength = array(0,31,28,31,30,31,30,31,31,30,31,30,31); //Days in each month (G.D. Julian Calendar)
$day = intval($_GET['day']);
if(!$day){$day=date("j",time());}
$month = intval($_GET['month']);
if(!$month){$month=date("n",time());}
$year = intval($_GET['year']);
if(!$year){$year=date("Y",time());}
if( ($year %4 == 0) && (($year %100 != 0) || ($year %400 == 0)) ){ $molength[2]++; } //Leap year (G.D. Julian Calendar)
$numEvents = 0; 
$target ="&id={$user->id}";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php include '../inc/head.php'; ?>
    <style>
		.calendar { border-collapse: separate; border-spacing: 1px; }
		.upper { background: #5c5c5c; color: #fff; }
		.daylabel { font-family: Georgia, "Times New Roman", Times, serif; font-size: 14px; color: #2d2d2d; }
		.adjuster { color: #bcd397; font-weight: bold; }
		.adjuster:hover { color: #98b26b; cursor: pointer; }
    	.events, .event {background:#f6fbed; color:#aacc6e;}
		.events:hover, .event:hover {background:#aacc6e; color: #f6fbed; cursor: pointer;}
    	.no-events {background:#FFFFFF; cursor: pointer;}
    	.today, .blueit {background:#1f95c3 !important; color: #fff !important; text-shadow: none !important; }
    	.subnav label{margin:2px;border:solid 1px;}
		.empty { border-top: 1px solid #e9e9e9; }
		.hour-label {
			background: #e9e9e9;
			padding: 6px 6px 0px 2px;
			text-align: right;
			vertical-align: bottom;
			text-shadow: #FFF -1px 1px 3px;	
			font-size:14px;
			font-family: Georgia, "Times New Roman", Times, serif;
			width: 80px;
		}
		.labeled td {
			font-weight:normal;
			font-size:14px;
			font-family: Georgia, "Times New Roman", Times, serif;
			text-align:center;
			background: #cccccc;
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e9e9e9');
			background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#e9e9e9));
			background: -moz-linear-gradient(top,  #ffffff,  #e9e9e9);
			color: #2d2d2d;
			text-shadow: #FFF -1px 1px 3px;	
			border-top: none !important;
		}
		.event {
			padding: 4px 6px 1px 6px;
			font-size: 14px;
			text-shadow: none !important;
			border-top: 1px solid #e9e9e9;
		}
		.event small, .regular {
			color: #2d2d2d !important;
		}
		.event .light {
			color: #BEC9A9;
		}
		.event:hover .light {
			color: #DCE0D5;
		}
		#week .event {
			padding: 0px 6px;
			line-height: 27px;
			font-size: 13px;
			width: 84px;
		}
		#week .hour-label {
			padding: 6px 6px 4px 2px;
			height: 17px;
			width: 64px;
		}
		#week .hour-label .hour {
			position: relative;
			top: -15px;
		}
    </style>
    
</head>
<body>
    <div id="wrap">
        <?php include '../inc/header.php'; ?>
        <?php include '../inc/nav.php'; ?>
        <div id="content" class="inner">
            <div class="left" id="side">
				<?php include '../inc/sidenav.php';?>
            </div>
            <div class="right" id="main">
                <div style="border: 1px solid #d7d7d7; padding: 12px 18px; margin-bottom: 10px;">
                    <h1 class="left no-bold" style="margin-top: 4px;">CALENDAR</h1>
                    <input type="hidden" id="daychosen" name="daychosen" value="<?=$day?>" />
                    <div class="right">
                    	<div class="left" id="assfilter"></div>
                    	<?php if($viewer->getAccount()->membership=="basic" || $viewer->getAccount()->membership=="pro"){?>
	                        <a href="javascript:void(0);" onclick="$.post('request.php',function(data){$.fancybox(data);});" class="button blue_button left" style="padding: 8px 14px; margin-right:5px;">Export Calendar</a>        
	                        <a href="javascript:void(0);" onclick="$.post('upload-pop.php',function(data){$.fancybox(data);});" class="button blue_button left" style="padding: 8px 14px;">Create Event</a>
	                    <?php }else{ ?> 
	                    	Your account is unable to create or export events.  Please <a href='/account/upgrade.php'>upgrade</a> your account.
	                    <?php } ?>
                    </div>
                    <div class="clear"></div>
                </div>        
				<?php if ($_GET['view'] == 'day') { include('day.php'); } else if ($_GET['view'] == 'week') { include('week.php'); } else { include('month.php'); } ?>           
                <div id="dayActivity" style="margin-top: 20px; max-height:500px; overflow-y:auto;">
					<?php include 'showDays.php';?>
                </div>
            </div>
        	<div class="clear"></div>
        </div>
        <?php include('../inc/footer.php') ?>
        </div>    
        </body>
        </html>
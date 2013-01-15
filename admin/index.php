<?php include '../includes/trois.php';?>
<?php include 'head.php' ?>
</head>

<body>
<div id="header">
<img src="img/home.png" align="left" /><h1> &nbsp;Admin Panel</h1>
<p><a href="<?=$HOME_URL?>" class="ui-state-default ui-corner-all" id="button"><span class="ui-icon ui-icon-arrowthick-1-w"></span>Back to Web Site</a></p>
<br clear="all" />
<div id="dialog" title="Dialog Title">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>
<div id="sidebar">
	
		<?php include 'side_nav.php' ?>
</div>
<div id="content">
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				<strong>Welcome, <?= $viewer->name('F') ?>!</strong></p><p>This is your Social Network's Administration Panel. Here you can manage the entire community with a few short clicks. Choose a section from the left to begin.</p>
			</div>
		</div>
        <br />
		<?php $getFlags = mysql_query("SELECT COUNT(*) FROM `flags` WHERE 1",$con);
		$flags = mysql_fetch_array($getFlags);
		
		if($flags[0]>0){?>
        
            <div class="ui-widget">
                <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
                    <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
                    <strong><a href="flags.php"><?=$flags[0]?> Items</strong> have been flagged innapropriate or unauthorized. Visit the Flag Manager to take the proper action.</a></p>
                </div>
            </div>
        
        <?php } ?>
</div>
</body>
</html>

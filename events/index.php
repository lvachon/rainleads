<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php include '../inc/trois.php'; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<?php include('../inc/head.php'); ?>
    <?php
        $con = conDB();
        $page = intval($_GET['page']);
        if(!$page){$page=1;}
        $per_page=16;
        $offset =($page-1)*$per_page;
        $target = "index.php?p=np";
        $qry="events WHERE `start_time` >".time()." order by end_time asc";
        $pgl = pageLinks($qry,$page,$per_page,$target);
        $r = mysql_query("SELECT * from $qry LIMIT $offset,$per_page",$con);
	?>
</head>
<body>
    <div id="wrap">
        <?php include '../inc/header.php'; ?>
        <?php include '../inc/nav.php'; ?>
        <div id="content" class="inner">
            <div id="side" class="left">
				<?php include('../inc/sidenav.php') ?>
            </div>
            <div id="main" class="left">
            	<h2 class="left">Upcoming Events</h2>
				<?php if(intval($viewer->id)){?>
                    <a href="upload.php" class="button right">Create Event</a>
                <?php } ?>
                <div class="clear"></div>
                <hr/>
                <?php while($e = mysql_fetch_array($r)){
                    $event = new Event($e['id']); 
					include 'include.php';
				} ?>
                <br />
                <div id='pagination' class="right"><?=$pgl;?></div>
                <div class="clear"></div>
            </div>
            
            <div class="clear"></div>
         </div><?php include('../inc/footer.php') ?>
    </div>    
</body>
</html>
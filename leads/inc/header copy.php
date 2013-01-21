<?php if(!intval($viewer->id)){?>
<div id="header">
    <div class="inner">
        <div class="left">
            <a href="index.php"><img src="/img/logo.png" alt="lead management software" width="250" /></a>
        </div>
        <div class="right">
        	<div class="phone_no" style="font-family: Georgia;font-size: 14px; color: #515151; margin-top:4px;">QUESTIONS? <i>CALL US AT 978-594-1489</div>
            <a href="<?=$sub?>index.php" class="button right" style="margin-top:10px;">Member Login</a>           
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php }else{ ?>
	<div id="header" class="loggedin">
        <div class="inner">
            <a href="<?=$HOME_URL?>account/dashboard.php"><img class="left" src="<?=$HOME_URL?>img/sm-logo.png" /></a>
            <div class="left" id="client_name">
                <a href="<?=$HOME_URL?>account/dashboard.php"><?=trunc($viewer->getAccount()->title,30)?></a>
            </div>
            <div class="right" id="nav">
            	<?php $con = conDB(); $getRequests = mysql_query("SELECT COUNT(id) FROM mail WHERE new =1 AND user_id != {$viewer->id} AND conversation_id IN(SELECT id FROM conversations WHERE thread_id REGEXP '.*(^|,){$viewer->id}($|,).*')",$con); 
				$msgs = mysql_fetch_array($getRequests);?>
            	<ul>
                	<li><a href="<?=$HOME_URL?>account/" class="yellow">MY ACCOUNT</a> | </li>
                    <li><!--<a href="javascript:void(0)" class="yellow" onclick="if($('#help-nav').is(':hidden')){$('#help-nav').show();}else{$('#help-nav').hide();}">HELP <?php if(intval($msgs[0])){?>(<?=number_format($msgs[0])?>)<?php } ?></a>| 
                    	<ul id='help-nav'>
                        	
                        	<li><a href="<?=$HOME_URL?>support/">Support</a></li>
                            
                            <li style="border-bottom:none !important;"><a href="javascript:void(0)" onclick="showFeatureTips(),$('#help-nav').hide();">Tour</a></li>
                        </ul>-->
                        <a href="<?=$HOME_URL?>support/" class="yellow">HELP<?php if(intval($msgs[0])){?> (<?=number_format($msgs[0])?>)<?php } ?></a>|
                    </li>
                    <li><a href="<?=$HOME_URL?>login.php?out" class="yellow">LOGOUT</a> </li>
                 </ul>
			</div>
            <div id="search">
                <form method='get' action='<?=$HOME_URL;?>leads/index.php'><input type="text" onFocus="if($(this).val()=='Search for Leads...'){$(this).val('')}" onBlur="if($(this).val()==''){$(this).val('Search for Leads...')}" value="Search for Leads..." name='q'/></form>         
            </div>
        	<div class="clear"></div>
        </div>
    </div>
    <!-- If the user is in a trial period, show the following -->
	<?php if($viewer->getAccount()->membership=="free" && $viewer->id == $viewer->getAccount()->user_id){ 
		$remaining = floor(($viewer->getAccount()->expiration-time())/86400);?>
		<div class="message info"><div class="inner"><center>Thank you for trying RainLeads. There are <?=$remaining?> days left in your FREE trial. <a href="<?=$HOME_URL?>account/upgrade.php">Upgrade Your Account</a></center></div></div>
	<?php } 
} ?>
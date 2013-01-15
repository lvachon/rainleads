<div id="accordion">
    <div>
        <h3><a href="#">Members</a></h3>
        <ul class="side-nav">
            <li onclick="document.location.href='users.php'"><a>Users</a></li>
            <li onclick="document.location.href='admins.php'"><a>Admins</a></li>           
            <li onclick="document.location.href='accounts.php'"><a href="#">Accounts</a></li>
			<li onclick="document.location.href='email_blast.php'"><a href="#">Email Blast</a></li>            
        </ul>
    </div>
   
     <div>
        <h3 onclick="document.location.href='support_tickets.php'"><a href="support_tickets.php">Support</a></h3>
        <ul class="side-nav">
            <li onclick="document.location.href='support_tickets.php'"><a href="#">Ticketing</a></li>
        </ul>
    </div>
        <div>
        <h3><a href="#">CMS</a></h3>
        <ul class="side-nav">
				<?php
					$con = conDB();
					$r = mysql_query("SELECT DISTINCT chunk_id from cms",$con);
					while($chunk_id = mysql_fetch_array($r)){
					?><li onclick="document.location.href='cms.php?edit=<?=urlencode($chunk_id[0]);?>'"><?php if(substr($chunk_id[0],0,3)=="RTF"){echo substr($chunk_id[0],3);}else{echo $chunk_id[0];}?></li>
					<?php	
					}
				?>                   
        </ul>
    </div>
    <div>
        <h3><a href="#">Ad Manager</a></h3>
        <ul class="side-nav">
            <li onclick="document.location.href='manage-ads.php'"><a href="#">Ads</a></li>
            <li onclick="document.location.href='manage-clients.php'"><a href="#">Clients</a></li>
        </ul>
    </div>
    <div>
    	<h3><a href="#">Transactions</a></h3>
        <ul class="side-nav">
            <li onclick="document.location.href='transactions.php'"><a href="#">Transaction History</a></li>
            <li onclick="document.location.href='promo.php'"><a href="#">Promo Codes</a></li>
        </ul>
    </div>
</div>
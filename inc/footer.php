<div id="footer">
<div class="inner">
    <div class="left" style="padding-right:45px;">
        <a href="#"><img src="<?= $HOME_URL ?>img/rss.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.twitter.com/rainleads" target="_blank"><img src="<?= $HOME_URL ?>img/twitter.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.linkedin.com/company/rainleads?trk=hb_tab_compy_id_2807294"><img src="<?= $HOME_URL ?>img/linkedin.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.facebook.com/rainleads" target="_blank"><img src="<?= $HOME_URL ?>img/facebook.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://plus.google.com/101283430835968547250/posts"><img src="<?= $HOME_URL ?>img/google.png" /></a>
        <br/><br/>
        <div style="background:#fff; padding:0px; border:1px solid #004366; box-shadow:#71a6c2 0 1px 0;" class="fb-like-box" data-href="http://www.facebook.com/pages/Rain-Leads/177708628763" data-width="275" data-show-faces="false" data-border-color="004569" data-stream="false" data-header="false"></div>
    </div>
    <div class="left">
        <ul>
            <li><a href="<?=$HOME_URL?>about.php">About RainLeads</a></li>
            <li><a href="<?=$HOME_URL?>faq.php">FAQ</a></li>
            <li><a href="<?=$HOME_URL?>terms.php">Terms & Conditions</a></li>
            <li><a href="<?=$HOME_URL?>privacy.php">Privacy Policy</a></li>
			<li><a href="<?=$HOME_URL?>enterprise.php">Enterprise Solutions</a></li>
        </ul>
    </div>
     
    <div class="right">
        Copyright 2011-2013 RainLeads, Inc.
    </div>
    <div class="clear"></div>
</div>

<div id="fb-root"></div>
<script src="https://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<script>
  FB.init({appId: '346873902077931', status: true, cookie: true, xfbml: true});
  FB.Event.subscribe('auth.sessionChange', function(response) {
    if (response.session) {
      // A user has logged in, and a new cookie has been saved
    } else {
      // The user has logged out, and the cookie has been cleared
    }
  });
  
</script>
<script>
	function login() {
	    FB.login(function(response) {
	        if (response.authResponse) {
	            // connected
	            document.location.href="<?= $HOME_URL ?>facebook.php";
	        } else {
	            // cancelled
	            window.reload();
	        }
	    }, {scope:'manage_pages'});
	}
</script>
<?php $account = $viewer->getAccount();
if(strlen($account->data['analytics'])){
	echo $account->data['analytics'];
}?>
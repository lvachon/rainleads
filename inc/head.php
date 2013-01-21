<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$SITE_NAME?></title>
<?php if(!in_array($_SERVER['SCRIPT_NAME'],$LOGGED_OUT_PAGES)){?>
	<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>css/style.css?rand=<?= rand() ?>" />
<?php }else{?>
	<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>css/styles.css?rand=<?= rand() ?>" />
<?php } ?>
<link href='https://fonts.googleapis.com/css?family=Schoolbell' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="<?=$HOME_URL?>jquery.fancybox.js"></script>
<script src="<?=$HOME_URL?>js/rsv.js"></script>
<script type="text/javascript" src="<?=$HOME_URL;?>js/jstz.min.js"></script> 
<script src="<?=$HOME_URL?>jquery.poshytip.min.js"></script>
<!--[if lt IE 9]> <script src="<?=$HOME_URL?>js/jquery.backgroundSize.min.js"></script> <![endif]-->
<link href='https://fonts.googleapis.com/css?family=Schoolbell' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="<?=$HOME_URL;?>img/favicon.ico" />
<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>jquery.fancybox.css" />
<link type="text/css" rel="stylesheet" media="screen" href="<?=$HOME_URL?>tip-twitter/tip-twitter.css" />
<link rel=" stylesheet" type="text/css" href="<?=$HOME_URL?>joyride-2.0.2.css">
<script src="<?=$HOME_URL?>jquery.joyride-2.0.2.js"></script>
<script>
$(function() {
 // Handler for .ready() called.
$('.tip').poshytip({
	className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignY:'bottom',
	alignX: 'center',
	allowTipHover: false,
	fade: false,
	slide: false,
	offsetY:5,
	offsetX:0
});


});
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-8670505-8']);
  _gaq.push(['_setDomainName', 'rainleads.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1023787515;
var google_conversion_label = "2qb4COfk_AMQ-4OX6AM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1023787515/?value=0&amp;label=2qb4COfk_AMQ-4OX6AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?php if(!strlen($_SESSION['time']) || strlen($_SESSION['offset'])){
		session_start();
	}
    $timezone = $_SESSION['time'];
	$offset = $_SESSION['offset'];

?>
<script type="text/javascript">
    $(document).ready(function() {
		if("<?php echo $timezone; ?>".length==0){
             timezone = jstz.determine_timezone();
			 timename = timezone.name();
			 offset = timezone.offset();
            $.ajax({
                type: "GET",
                url: "<?=$HOME_URL?>timezone.php",
                data: 'time='+ timename+'&offset='+offset,
                success: function(){
                    location.reload();
                }
            });
        }
    });
</script>
<script>
	function killReminder(id){
		$.post('<?=$HOME_URL?>events/kill-reminder.php',{'id':id},function(data){
			$('#remind_'+id).fadeOut();
		});
	}
</script>
<?php date_default_timezone_set($timezone); 
if(strlen($timezone) && intval($viewer->id)){
	$viewer->data['timezone'] = $timezone;
	$viewer->save();
}
//echo "Your timezone: ".$viewer->data['timezone'];?>
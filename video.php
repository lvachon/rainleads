<html>
	<head>
		<title></title>
		<script src="http://www.youtube.com/iframe_api"></script>
		<script src="<?=$HOME_URL?>jquery.fancybox.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
			</head>
	<body style="width:863px; height:450px; overflow:hidden; margin:0px; padding:0px;">
	<script src="http://www.youtube.com/iframe_api"></script>
<div id="container"></div>
​<script>
		    function log(msg) {
        jQuery('#log').prepend(msg + '<br/>');
    }

    function onPlayerStateChange(event) {
        switch(event.data) {
            case YT.PlayerState.ENDED:
                log('Video has ended.');
                parent.$.fancybox.close();
                break;
            
        }
    }
      function player(){
      	var vidId = '<?= $_GET['v'] ?>';
      	new YT.Player('player', {
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
      }
    $(function() {
             
			
            
            var vidId = '<?= $_GET['v'] ?>';
            $('#container').html('<iframe id="player" width="800" height="450" src="http://www.youtube.com/embed/' + vidId + '?enablejsapi=1&autoplay=1&autohide=1&showinfo=0&modestbranding=1&controls=0&theme=light&hd=1&rel=0" frameborder="0" allowfullscreen></iframe>');
            setTimeout('player();',1000);
            
       
    });</script>	
</html>

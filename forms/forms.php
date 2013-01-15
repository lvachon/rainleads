<!DOCTYPE html>
<html>
<?php include('inc/head.php'); ?>
<script src="./js/ddslick.js"></script>
<script>
		var stateData = [
			{
				text: "Newest First",
				value: 1,
				selected: false				
			},
			{
				text: "Oldest First",
				value: 2,
				selected: false
			},
			{
				text: "Alphabetically",
				value: 3,
				selected: false
			}
		];
		
		$(function() {
		 	$('#sort').ddslick({
				data:stateData,
				width: 120,
				imagePosition: "left",
				selectText: "Sort Contacts",
				onSelected: function (data) {
					console.log(data);
				}
			});
			$('.contact_row').click(function(){
				if($(this).find('.contact_preview').is(':visible')){
					$('.contact_details').hide()
					$('.contact_preview').show()
					$(this).find('.contact_preview').hide()
					$(this).find('.contact_details').show()
				}else{
					$('.contact_details').hide()
					$('.contact_preview').show()
				}
			});
			
		});
		function sortContacts(letter){
			document.location.href='./contacts.php?alpha='+letter+'';	
		}
	</script>
<body>
<?php include('inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('inc/nav.php') ?>
    </div>
    <div id="main" class="left">
        <div id="contact_forms">
            <h1>Contact Forms</h1>
            <div class="right">                        	                     
                <a class="button blue_button">Create New</a>
            </div>
            <div class="clear"></div>
            <br/>
            <div class="contact_form_row">
            	<div class="form_name">Hompage Contact Form</div>
                <div class="form_links">
                	<div class="form_link"><img src="img/edit-icon.png"><span>Edit</span></div>
                    <div class="form_link"><img src="img/gear-icon.png"><span>Settings</span></div>
                    <div class="form_link"><img src="img/embed-icon.png"><span>Embed Codes</span></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="contact_form_row">
            	<div class="form_name">Contact Page Contact Form</div>
                <div class="form_links">
                	<div class="form_link"><img src="img/edit-icon.png"><span>Edit</span></div>
                    <div class="form_link"><img src="img/gear-icon.png"><span>Settings</span></div>
                    <div class="form_link"><img src="img/embed-icon.png"><span>Embed Codes</span></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="contact_form_row">
            	<div class="form_name">Facebook Contact Form</div>
                <div class="form_links">
                	<div class="form_link"><img src="img/edit-icon.png"><span>Edit</span></div>
                    <div class="form_link"><img src="img/gear-icon.png"><span>Settings</span></div>
                    <div class="form_link"><img src="img/embed-icon.png"><span>Embed Codes</span></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="contact_form_row">
            	<div class="form_name">Microsite Contact Form</div>
                <div class="form_links">
                	<div class="form_link"><img src="img/edit-icon.png"><span>Edit</span></div>
                    <div class="form_link"><img src="img/gear-icon.png"><span>Settings</span></div>
                    <div class="form_link"><img src="img/embed-icon.png"><span>Embed Codes</span></div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
       
    <div class="clear"></div>
</div>
</body>
</html>

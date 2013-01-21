<?php include '../inc/trois.php'; 
loginRequired();
if(!verAccount()){
	errorMsg("No account associated with login");
}
$account = new Account(verAccount());
if($account->data['add_forms'] != 1 && $viewer->id != $account->user_id){
	errorMsg("Access denied by account owner.");
}

$con = conDB();
$r = mysql_query("SELECT * from membership where user_id={$viewer->id}",$con);
$m = mysql_fetch_array($r);
$delcon = " and deleted=0 ";
if(intval($_GET['delcon'])){$delcon= " and deleted=1 ";}
$forms = mysql_query("SELECT * from forms where account_id = {$account_row['id']}  {$delcon} order by datestamp desc",$con);
?>
<!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<script src="/js/ddslick.js"></script>
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
			
 			$("#forms-icon").attr({'src':'<?= $HOME_URL ?>img/forms-icon-on.png'});
 			$('#forms-tab').addClass('current');

		});
		function sortContacts(letter){
			document.location.href='./contacts.php?alpha='+letter+'';	
		}
	</script>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
        <div id="contact_forms">
            <h1>Contact Forms</h1>
            <div class="right">                        	                     
                <?php 
                $fcr = mysql_query("SELECT count(*) from forms where account_id={$account->id} and deleted=0",$con);
				$fc = mysql_fetch_array($fcr);
				$fc = intval($fc[0]);
                if(($account->data['add_forms'] == 1 || $viewer->id == $account->user_id)){
                	if($account->formLimit()>$fc){?>
                    	<a class="button blue_button" href='create-form.php'>Create New Contact Form</a>
                    <?php }else{ ?>
                    	<small>
                    		You have reached your limit of custom forms.<br/>  Please <?php if($viewer->id == $account->user_id){?><a href="../account/upgrade.php"><?php } ?>upgrade<?php if(in_array($viewer->id,$account->admins)){?></a><?php } ?> your account.
                    	</small>
                    <?php  }
                } ?>
            </div>
            <div class="clear"></div>
            <br/>
            <?php 
            $fx = 0;
            while($f = mysql_fetch_array($forms)){
            $form = new Form($f['id']);
            $owner = new User($form->data['orig_user']);
            $le = new User($form->data['last_editor']);
            ?>
            <div class="contact_form_row">
            	<div class="form_name left"><?=$form->data['title'];?>
            		<div class="meta">Created By: <?= $owner->name("F l"); ?> &middot; Last Edit: <?= date("M dS @ g:ia",$form->datestamp) . " by " . $le->name("F l"); ?></div>
            	</div>
            	
            	<div class="clear"></div>
                <div class="form_links">
                	<?php if($account->data['add_forms'] != '0' || ($account->data['add_forms'] == '0' && $viewer->id == $account->user_id)){?> <div class="form_link button" onclick="document.location.href='create-form.php?id=<?=$f['id'];?>'"><img src="/img/edit-icon.png"><span><a href='create-form.php?id=<?=$f['id'];?>'>Edit</a></span></div><?php } ?>
                    <div class="form_link button" onclick="document.location.href='edit-form.php?id=<?=$f['id']?>'"><img src="/img/gear-icon.png"><span><a href='edit-form.php?id=<?=$f['id']?>'>Styles</a></span></div>
                    <?php if($account->membership!="lite"){?><div class="form_link button" onclick="document.location.href='formcode.php?id=<?=$f['id'];?>'"><img src="/img/embed-icon.png"><span><a href='formcode.php?id=<?=$f['id'];?>'>Embed Codes</a></span></div><?php } ?>
                    <div class="form_link button" onclick='$.get("<?=$HOME_URL;?>forms/preview.php?id=<?=$f['id'];?>",function(data){$.fancybox(data);});'><img src="/img/preview-icon.png" style="position:relative; top:1px;"><span><a href='#' onclick='$.get("<?=$HOME_URL;?>forms/preview.php?id=<?=$f['id'];?>",function(data){$.fancybox(data);});'>Preview</a></span></div>
                    <div class="form_link button" onclick="document.location.href='../leads/index.php?form_id=<?=$f['id']?>'"><img src="/img/lead-icon-on.png"><span><a href='../leads/index.php?form_id=<?=$f['id']?>'>Leads</a></span></div>
                    <?php if($_GET['delcon'] != 1){?><div class="form_link button" onclick="document.location.href='javascript:void(0)' onclick='if(confirm("Do you really want to delete this form?\nResults will not be affected.")){document.location.href="kill-form.php?id=<?=$f['id']?>";}'"><img src="/img/delete-icon.png"><span><a href='javascript:void(0)' onclick='if(confirm("Do you really want to delete this form?\nPrevious leads will not be affected.\nThis form will no longer be able to recieve new lead data.")){document.location.href="kill-form.php?id=<?=$f['id']?>";}'>Archive</a></span></div><?php } ?>
                    <div class="form_link button" onclick="document.location.href='copyform.php?id=<?=$f['id']?>'"><img src="/img/gear-icon.png"><span><a href='copyform.php?id=<?=$f['id']?>'>Copy</a></span></div>

                    <div class="clear"></div>
                    
                </div>
            </div>
            <?php $fx++;} ?>
        </div>
        <?php if($fx>0){?>
        <div class="right" style="font-size:12px;margin:5px 0px;">
			<?php if(!intval($_GET['delcon'])){?><a href='index.php?delcon=1'>Show archived forms</a><?php }else{ ?><a href='index.php'>Show active forms</a><?php } ?>
	    </div>
	    <?php } ?>
        
		
	    <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('../inc/footer.php'); ?>
</body>
</html>

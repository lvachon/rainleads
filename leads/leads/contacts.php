<?php include '../inc/trois.php';
loginRequired();
$viewer = new User(verCookie());
$acct = new Account(verAccount());
?>
<!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<script src="../js/ddslick.js"></script>
<script>
		var stateData = [
			{
				text: "Newest First",
				value: "new",
				selected: <?php if($_GET['sort']=="new"){echo "true";;}else{echo "false";}?>				
			},
			{
				text: "Oldest First",
				value: "old",
				selected: <?php if($_GET['sort']=="old"){echo "true";}else{echo "false";}?>
			},
			{
				text: "Alphabetically",
				value: "alpha",
				selected: <?php if($_GET['sort']=="alpha"){echo "true";}else{echo "false";}?>
			}
		];
		
		$(function() {
		 	$('#sort').ddslick({
				data:stateData,
				width: 120,
				imagePosition: "left",
				selectText: "Sort Contacts",
				onSelected: function (data) {
					if(data.selectedData.value!="<?=$_GET['sort'];?>"){document.location.href='./contacts.php?alpha=<?=$_GET['alpha'];?>&sort='+data.selectedData.value;}
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
			document.location.href='./contacts.php?alpha='+letter+'&sort=<?=$_GET['sort'];?>';	
		}
	
	</script>
	<style>
	.contact_preview {display:block;}
	.contact_details {display:none;}
	</style>
	

	
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
        <div id="all_contacts">
            <h1>All Contacts <?php if(strlen($_GET['alpha'])){echo "(".strtoupper($_GET['alpha'])." - ".strtoupper(chr(ord($_GET['alpha'])+1)).")";}?></h1>
            <div class="right">                        	
                <div id="sort"></div>     
            </div>
            <div class="clear"></div>
            <div id="alpha_filter" style='letter-spacing:0.2em;'>
            	<a class='aplha_link' href='javascript:void(0)' onclick='sortContacts("")'>All</a> &middot;
            	<?php
					foreach (range('A', 'Z') as $char) {
						
						echo "<a class='aplha_link'"; 
						if($_GET['alpha'] == $char){ echo" id='selected'";} 
						echo" href='javascript:void(0)' onclick='sortContacts("; 
						echo'"'.$char.'"'; 
						echo ")'>".$char."</a>";
						if($char !='Z'){echo " &middot; ";}
					}
				?>
            </div>
            <?php 
            $con = conDB();
            $sort="datestamp desc";
            if($_GET['sort']=="new"){
            	$sort = "datestamp desc";
            }
            if($_GET['sort']=="old"){
            	$sort = "datestamp asc";
            }
            if($_GET['sort']=="alpha"){
            	$sort = "name asc";
            }
            $filter = "";
            if(strlen($_GET['alpha'])){
            	$filter = " and lcase(substring(name,1,1))=\"".strtolower(mysql_escape_string($_GET['alpha']))."\" ";
            }
            $page = intval($_GET['page']);
            if(!$page){
            	$page = 1;
            }
            $per_page=25;
            $offset = ($page-1)*$per_page;
            if(intval($acct->data['see_all_leads']) || in_array($viewer->id,$acct->admins)){
            	$qry = "form_results where length(name)>0 and length(email)>0 and form_id IN (SELECT id from forms where account_id={$account_row['id']}) {$filter} ";
            	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort} LIMIT $offset,$per_page",$con);
            }else{
            	$qry = "form_results where length(name)>0 and length(email)>0 and id IN (SELECT result_id from assignments where user_id={$viewer->id})  {$filter} ";
            	$res = mysql_query("SELECT *,(SELECT user_id from assignments where result_id=form_results.id) as assigned_user,(SELECT title from statuses where id=status) as statustext,(SELECT color from statuses where id=status) as statuscolor from $qry order by {$sort} LIMIT $offset,$per_page",$con);
            }
            $pgl = pageLinks($qry,$page,$per_page,"contacts.php?sort={$_GET['sort']}&alpha={$_GET['alpha']}");
			while ($lead = mysql_fetch_array($res)){
				include('../inc/contact.php');
			}?>
            <div class='pagination'><?=$pgl;?></div>
        </div>
       
    <div class="clear"></div>
</div>
</body>
</html>
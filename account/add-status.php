<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());
if($account->user_id!=$viewer->id){errorMsg("Only the account owner can change that setting");die();}
$account = $viewer->getAccount();
$table = $_POST['table'];?>
<script type="text/javascript">
	function swatchTog(){
		var sOpt = $('#main_swatch').find('.swatch_options');
		if($(sOpt).is(':hidden')){
			$(sOpt).show(); 
		}else{
			$(sOpt).hide(); 
		}
	}
	function switchSwatch(id){
		var swClass = "color_"+id;
        $('#main_swatch').attr({'class':'swatch_select '+swClass});  
		$('#color').val(swClass); 
	}
</script>
<h2 class="">Add <?php if($table == 'statuses'){ echo "Status"; }else{ echo "Milestone"; }?></h2>
<hr class="title_line"/>
<div class="clear"></div>

<div id="interior" style="width:400px; font-size:14px">
<small class="">Please choose a <?php if($table == 'statuses'){?>color and<?php } ?> title:</small>
     <form action="save-status.php" method="post">
        <input type="hidden" name="color" id="color" value="color_1" />
        <input type="hidden" name="table" value="<?=$table?>" />
        <input type="hidden" name="account_id" value="<?=$account->id?>" />
        <ul id="status_list">
            <li class="status_row" style="border-bottom:none; background:none;">
                <?php if($table == 'statuses'){?>
                    <div class="swatch_arrow left" data-statusID=1 style="margin-top:6px;">
                        <div class="swatch_select color_1"  id="main_swatch" onClick="swatchTog();">&#9660;
                            <div class="swatch_options">
                                <div class="swatch_option color_1" data-colorID="1" onClick="switchSwatch(1);"></div>
                                <div class="swatch_option color_2" data-colorID="2" onClick="switchSwatch(2);"></div>
                                <div class="swatch_option color_3" data-colorID="3" onClick="switchSwatch(3);"></div>
                                <div class="swatch_option color_4" data-colorID="4" onClick="switchSwatch(4);"></div>
                                <div class="swatch_option color_5" data-colorID="5" onClick="switchSwatch(5);"></div>
                                <div class="swatch_option color_6" data-colorID="6" onClick="switchSwatch(6);"></div>
                                <div class="swatch_option color_7" data-colorID="7" onClick="switchSwatch(7);"></div>
                                <div class="swatch_option color_8" data-colorID="8" onClick="switchSwatch(8);"></div>           
                                <br clear="all" />
                            </div>
                        </div>    
                    </div>
                <?php } ?>
                <div class="left milestone_name" style="width:200px;"><input type="text" name="title" style="font-size 13px; padding:5px; border:1px solid #c0c0c0; width:260px;" placeholder="<?php if($table == 'statuses'){ echo "Status"; }else{ echo "Milestone"; }?> Title" /></div>
                
                <div class="clear"></div>
            </li>
        </ul>
        <br/>
    	
    	<input type="submit" class=" blue_button button" value="Save <?php if($table == 'statuses'){ echo "Status"; }else{ echo "Milestone"; }?>" />
    	
    </form>
</div>

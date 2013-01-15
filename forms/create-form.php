<?php include '../inc/trois.php';
loginRequired();
if(!verAccount()){errorMsg("No account associated with login");}
if($viewer->getAccount()->data['add_forms'] != 1 && $viewer->id != $viewer->getAccount()->user_id){
	errorMsg("Access denied by account owner.");
}
$account = new Account(verAccount());
if(!intval($_GET['id'])){
	$fcr = mysql_query("SELECT count(*) from forms where account_id={$account->id} and deleted=0",$con);
	$fc = mysql_fetch_array($fcr);
	$fc = intval($fc[0]);
    if($fc>$account->formLimit()){errorMsg("You have reached your limit of custom forms.<br/>  Please <a href='/account/upgrade.php'>upgrade</a> your account.");die();}
	$f = new Form(array('action'=>'#','method'=>"post"));
	$f->addElem(new FormElement(array('name'=>"name","label"=>"Full Name","type"=>"text",'required'=>'1')));
	$f->addElem(new FormElement(array('name'=>"email","label"=>"Email","type"=>"text",'required'=>'1')));
	$f->addElem(new FormElement(array('name'=>"phone","label"=>"Phone #","type"=>"text",'required'=>'1')));
	$f->addElem(new FormElement(array('name'=>"notes","label"=>"Comments","type"=>"textarea",'required'=>'1')));
	$f->data['orig_user']=strval($viewer->id);
	$f->account_id = verAccount();
	$f->save();
	header("Location: create-form.php?id={$f->id}");
	die();	
}
$f = new Form($_GET['id']);
if($f->account_id!=verAccount()){errorMsg("This is not your form to edit");}
?>
<!DOCTYPE html>
<html>
<?php include('../inc/head.php'); ?>
<body>
<?php include('../inc/header.php'); ?>
<div id="content" class="inner">
	<div id="side" class="left">
    	<?php include('../inc/sidenav.php') ?>
    </div>
    <div id="main" class="left">
        <div id="contact_forms">
            <h1>Create Contact Form</h1>
            <div class="clear"></div>
            <br/>
            <h2>Step 1: Create Your Form Fields</h2>
            <span class="subtitle">Click on any form field to edit it's label. To add a field, select field type and hit Add.</span>
            <br/>
            <div class="" id='formcontainer'>
            	<?=$f->getEditHTML();?>
            </div>
            <div class='box'>
            	<select id='addfield' style="margin-top:10px;">
            		<option value='text'>Single-line text box</option>
            		<option value='textarea'>Multi-line text box</option>
            		<option value='select'>Drop-down box</option>
            		<option value='mselect'>Multi-select list</option>
            		<option value='date'>Date picker</option>
            		<option value='time'>Time chooser</option>
            	</select>
            	<input type='button' value='Add Element'  onclick='addElem();'/>
            	<div class='right'>
            		<form method='post' action='edit-form.php?id=<?=$f->id;?>' id="main_form"> 
                    	<input type="hidden" name="embed" value="0" id="embed">
 	            		<span style="font-size:13px;">Name Your Form:</span> <input  style="border-color:#c0c0c0; padding:4px;" type='text' name='formname' value="<?=htmlentities($f->data['title']);?>"/>
	            		<input type='button' class="button" style="display:inline" value='Preview' onclick='$.get("preview.php",{id:<?=$f->id;?>},function(data){$.fancybox(data);});' />
	            		<input type='submit' class="button blue_button" style="display:inline" value='Save & Style'/>
                       <div class="clear"></div>
                      
	            	</form>
            	</div>
            	 <div class="clear"></div>
            </div>
        </div>
       
    <div class="clear"></div>
    <center style="display:block; margin:20px 60px; font-size:14px; color:#F00; line-height:17px;"><small style="color:#F00;"> Collecting credit card information is strictly prohibited and will result in immediate account removal.<br/>See our <a href="<?=$HOME_URL?>terms.php"  target="_blank">Terms & Conditions</a> for more details.</small>
</div>
<script src="/datepicker/javascript/functions.js"></script>
<script src="/js/jquery.sortable.js"></script>
<link type="text/css" rel="stylesheet" media="screen" href="/datepicker/css/style.css" />
<script>
	$(document).ready(function(){
		
  		

  		initShit();
		
	});

	function initShit(){
		$('li.form_field').click(function(){
			if($(this).find('.preview_field').is(':visible')){
				$('.edit_field').hide()
				$('.preview_field').show()
				$(this).find('.edit_field').show()
				$(this).find('.preview_field').hide()
			}else{
				
			}			
		});

		$("#form_fields").sortable();
  		$('#form_fields').sortable().bind('sortupdate', function() {
  	            //Store the new order.
  				var fieldOrder = [];
  				$("#form_fields").find(".form_field[draggable='true']").each(function(){ fieldOrder.push(this.id); });
  				$.post("formedit.php",{"action":"reorder","form_id":<?=$f->id;?>,"ids":fieldOrder.join(",")},function(data){
  	  				$("#formcontainer").html(data);
  	  				$("#formcontainer").css({"opacity":"1"})
  	  				initShit();
  				});	
  				$("#formcontainer").css({"opacity":"0.3"})
  	        });
	}
	
	function selectRows(fieldID,rows){
		$('*[data-fieldID="'+fieldID+'"]').attr({'rows':rows});	
	}
	function textWidth(fieldID,width){
		$('input[data-fieldID="'+fieldID+'"],textarea[data-fieldID="'+fieldID+'"],select[data-fieldID="'+fieldID+'"]').css({'width':width});
		$('input[data-field-id="'+fieldID+'"],textarea[data-field-ID="'+fieldID+'"],select[data-field-ID="'+fieldID+'"]').css({'width':width});
		
			
	}
	function labelText(labelID,label){
		$('div[data-labelID="'+labelID+'"]').html(label+':');	
	}	
	function optionLabel(labelID,optionID,label){
		$('select[data-fieldID="'+labelID+'"] option[data-optionID="'+optionID+'"]').html(label);//.val(value);		
	}
	function setHeight(fieldID,height){
		$('select[data-fieldID="'+fieldID+'"]').css({'height':height+'px'});	
	}	
	function addSelectOption(fieldID){
		var lastOption = $('select[data-fieldID="'+fieldID+'"]').find("option:last");
		var lastOptionID = lastOption.attr("data-optionID");
		var nextOptionID = 0.0+Number(lastOptionID)+1.0*1;
		var editSelectOption = '<div class="edit_select_option" data-optionID="'+nextOptionID+'"><span class="edit_label">Option Label</span><br/><input type="text" value="" class="edit" onKeyUp="optionLabel('+fieldID+','+nextOptionID+',$(this).val())"> <img src="/img/field-delete-icon.png" onclick="removeSelectOption('+fieldID+','+nextOptionID+')" /></div>';
		var selectOption = '<option value="" data-optionID="'+nextOptionID+'"></option>';
		
		$('.edit_select[data-fieldID="'+fieldID+'"] .edit_select_option[data-optionID="'+lastOptionID+'"]').after(editSelectOption);
		$('select[data-fieldID="'+fieldID+'"] option[data-optionID="'+lastOptionID+'"]').after(selectOption);
		//alert(fieldID+","+lastOptionID);
		
	}
	function removeSelectOption(fieldID,optionID){
		$('div[data-fieldID="'+fieldID+'"] div.edit_select_option[data-optionID="'+optionID+'"]').remove();
		$('select[data-fieldID="'+fieldID+'"] option[data-optionID="'+optionID+'"]').remove();
	}
	

    function delField(fieldID){
		if(confirm("Do you really want to delete this field?")){
	    	$.post("formedit.php",{"form_id":"<?=$f->id;?>","action":"remove","elem_id":fieldID},function(data){
		    	$("#formcontainer").html(data);
				initShit();
				$("#formcontainer").css({"opacity":"1"})
			});	
			$("#formcontainer").css({"opacity":"0.3"})
		}
    }
    function saveField(type,fieldID){
		var data = {"type":type};
		data.label = $("#label"+fieldID).val();
		data.width = $("#width"+fieldID).val();
		//data.name = type+"_"+fieldID;
		labelText(fieldID,$("#label"+fieldID).val());
		data.rows=$("#rows"+fieldID).val();
		if($("#required_check_"+fieldID+":checked").length>0){
			data.required="1";
		}else{
			data.required="0";
		}
		if(type=="select"){
			var options = new Array();
			var op = $('select[data-fieldID="'+fieldID+'"]',$(".edit_field")).find("option");
			for(var i=0;i<op.length;i++){
				options[i]=op[i].innerHTML;
			}
			data.options = options.join(";");
			$('select[data-fieldID="'+fieldID+'"]',$(".preview_field")).html($('select[data-fieldID="'+fieldID+'"]',$(".edit_field")).html());
			if($('select[data-fieldID="'+fieldID+'"]',$(".edit_field")).attr("multiple")=="multiple"){data.multiple="1";}
		}
    	$.post("formedit.php",{"form_id":"<?=$f->id;?>","action":"edit_elem","elem_id":fieldID,'elem_data':JSON.stringify(data)},function(data){if(data=="ok"){
			$(".preview_field",$("#"+fieldID)).show();
			$('.edit_field',$("#"+fieldID)).hide();
        	}else{$.fancybox(data);}
    	});
    }

    function addElem(){
		var data = {type:$("#addfield").val()};
		if(data.type=="mselect"){data.type="select";data.multiple="1";}
		data.label = "New Field";
		data.width = "95";
		$.post("formedit.php",{"form_id":"<?=$f->id;?>","action":"append","elem_data":JSON.stringify(data)},function(data){
			$("#formcontainer").html(data);
			initShit();
			$("#formcontainer").css({"opacity":"1"})
		});	
		$("#formcontainer").css({"opacity":"0.3"})
    }
</script>
</body>
</html>

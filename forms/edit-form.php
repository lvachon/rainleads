<?php
include '../inc/trois.php';
$f = new Form($_GET['id']);
if(!$f->id){
	die("No form");
}
if($viewer->getAccount()->data['add_forms'] != 1 && $viewer->id != $viewer->getAccount()->user_id){
	errorMsg("Access denied by account owner.");
}
$account = new Account($f->account_id);
if($f->account_id!=$account->id){errorMsg("This form does not belong to this account");die();}
if(strlen($_POST['formname'])){
	$f->data['title']=$_POST['formname'];
	$f->save();
	if($_POST['embed'] == '1'){
		header("Location:formcode.php?id={$_GET['id']}");
	}else{
		header("Location:edit-form.php?id={$_GET['id']}");
	}
	die();
}

$a = explode('}',$f->data['styles']);

$style = array('background0'=>"FFFFFF",'font-family1'=>'Arial','font-weight1'=>'normal','font-size1'=>'14px','color1'=>'3e3e3e','font-family2'=>'Arial','font-size2'=>'14px','color2'=>'3e3e3e','background2'=>'ffffff','border2'=>'898989');
if(count($a)>1){
	$cnt=0;
	foreach($a as $aa){
		$aa = substr($aa,strpos($aa,"{")+1);
		$b = explode(";",$aa);
		foreach($b as $bb){
			$c = explode(":",$bb);
			$style[$c[0].$cnt]=$c[1];
		}
		$cnt++;
	}
}
$style['font-size1']=substr($style['font-size1'],0,strlen($style['font-size1'])-2);
$style['font-size2']=substr($style['font-size2'],0,strlen($style['font-size2'])-2);
$a=explode(" ",$style['color2']);
$style['color2']=$a[0];
$a=explode(" ",$style['border2']);
$style['border2']=$a[0];
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
        <div id="contact_form_settings">
            <h1>Step 2: Customize Your Form</h1>            
            <div class="clear"></div>
            <br/>
            <div id="form_settings">  
            	<h2 class="left">Form Styles &amp; Preview</h2>
                <hr class="title_line" />          
                <div  class="left" style="width:55%;padding:0px;">                
                <div id="style_settings">
                <table cellpadding="5" width="100%">
                	<tr>
                    	<td align="" width="112">Form Width:</td>
                        <td><input onChange="previewStyles()" type="text" id="pixel_width" value="<?=substr($style['width0'],0,strlen($style['width0'])-2);?>" style="width:30px; text-align:center" /> Pixels</td>
                    </tr>
                    <tr>
                    	<td>Background Color:</td>
                        <td><input type="text" onChange="previewStyles()" id="background_color" class="color" value="#<?=$style['background0'];?>" style="width:60px; text-align:center; text-transform:uppercase;" /></td>
                    </tr>
                    
                    <tr>
                    	<td>Label Font Family:</td>
                        <td>
                        	<select id="label_font" onChange="previewStyles()">
                        		<option <?php if($style['font-family1']=="Arial"){echo "selected";}?> >Arial</option>
                        		<option <?php if($style['font-family1']=="Georgia"){echo "selected";}?> >Georgia</option>
                        		<option <?php if($style['font-family1']=="Lucida Grande"){echo "selected";}?> >Lucida Grande</option>
                        		<option <?php if($style['font-family1']=="Segoe ui"){echo "selected";}?> >Segoe ui</option>
                        		<option <?php if($style['font-family1']=="Tahoma"){echo "selected";}?> >Tahoma</option>
                        		<option <?php if($style['font-family1']=="Verdana"){echo "selected";}?> >Verdana</option>
                        	</select>
                        	<input type="checkbox" id="label_font_weight" onChange="previewStyles()" <?php if($style['font-weight1']=='bold'){echo "checked";}?> />Bold
                        </td>
                    </tr>
                    <tr>
                    	<td>Label Font Size:</td>
                        <td><input type="text" id="label_font_size" onChange="previewStyles()" value="<?=$style['font-size1'];?>" style="width:20px; text-align:center" /> px</td>
                    </tr>
                    <tr>
                    	<td>Label Font Color:</td>
                        <td><input type="text" id="label_font_color" onChange="previewStyles()" class="color" value="#<?=$style['color1'];?>" style="width:60px; text-align:center; text-transform:uppercase;" /></td>
                    </tr>
                    <tr>
                    	<td>Field Font Family:</td>
                        <td><select id="field_font" onChange="previewStyles()">
                        		<option <?php if($style['font-family2']=="Arial"){echo "selected";}?> >Arial</option>
                        		<option <?php if($style['font-family2']=="Georgia"){echo "selected";}?> >Georgia</option>
                        		<option <?php if($style['font-family2']=="Lucida Grande"){echo "selected";}?> >Lucida Grande</option>
                        		<option <?php if($style['font-family2']=="Segoe ui"){echo "selected";}?> >Segoe ui</option>
                        		<option <?php if($style['font-family2']=="Tahoma"){echo "selected";}?> >Tahoma</option>
                        		<option <?php if($style['font-family2']=="Verdana"){echo "selected";}?> >Verdana</option>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                    	<td>Field Font Size:</td>
                        <td><input id="field_font_size" type="text" onChange="previewStyles()" value="<?=$style['font-size2'];?>" style="width:20px; text-align:center" /> px</td>
                    </tr>
                    <tr>
                    	<td>Field Font Color:</td>
                        <td><input id="field_font_color" type="text" onChange="previewStyles()" class="color" value="#<?=$style['color2'];?>" style="width:60px; text-align:center; text-transform:uppercase;" /></td>
                    </tr>
                    <tr>
                    	<td>Field Border Color:</td>
                        <td><input id="field_border_color" type="text" onChange="previewStyles()" class="color" value="#<?=$style['border2'];?>" style="width:60px; text-align:center; text-transform:uppercase;" /></td>
                    </tr>
                    <tr>
                    	<td>Field Background:</td>
                        <td><input type="text" id="field_background" onChange="previewStyles()" class="color" value="#<?=$style['background2'];?>" style="width:60px; text-align:center; text-transform:uppercase;" /></td>
                    </tr>                    
                </table>
                </div>                
                <script type="text/javascript" src="/jscolor/jscolor.js"></script>                
                <script>
					function previewStyles(){
							var pixel_width = $('#pixel_width').val()+"px";
							var background_color = $('#background_color').val();
							var field_background = $('#field_background').val();
							var field_font = $('#field_font').val();
							var field_font_size = $('#field_font_size').val()+"px";
							var field_font_color = $('#field_font_color').val();
							var field_border_color = $('#field_border_color').val();
							var label_font = $('#label_font').val();
							var label_font_size = $('#label_font_size').val()+"px";
							var label_font_color = $('#label_font_color').val();
							if($('#label_font_weight').is(":checked")){
								var label_font_weight = "bold";
							}else{
								var label_font_weight = "normal";	
							}
							
							var styles = "#form_preview{background:#"+background_color+";}.form_preview_label{font-family:"+label_font+";font-size:"+label_font_size+";color:#"+label_font_color+";font-weight:"+label_font_weight+";}.form_preview_field{font-family:"+field_font+";font-size:"+field_font_size+";color:#"+field_font_color+" !important;background:#"+field_background+";border:#"+field_border_color+" solid 1px!important;}"
							var savestyles = "#form_<?=$f->id;?>{background:#"+background_color+";width:"+pixel_width+"}.form_label{font-family:"+label_font+";font-size:"+label_font_size+";color:#"+label_font_color+";font-weight:"+label_font_weight+";}.form_field{font-family:"+field_font+";font-size:"+field_font_size+";color:#"+field_font_color+" !important;background:#"+field_background+";border:#"+field_border_color+" solid 1px!important;}"
							$('#form_preview_style').html(styles);
							$.post("formedit.php",{"form_id":<?=$f->id;?>,"action":"edit_styles","styles":savestyles,"width":pixel_width},function(data){
								if(data!="ok"){
									$.fancybox(data);
								}
							});
					}
					
					
					
					$(document).ready(function(){
						setTimeout('previewStyles();',500);
					});
				</script>            
            </div>
            <div class="right" style="width:44%;">
            	
                <div class="" id="form_preview">
                    <table width="100%" cellpadding="5">
                        <tr>
                            <td><span class="form_preview_label">Full Name</span></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form_preview form_preview_field" value="Jon Smith" /></td>
                        </tr>
                        <tr>
                            <td><span class="form_preview_label">Email Address</span></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form_preview form_preview_field" value="Jon Smith" /></td>
                        </tr>
                        <tr>
                            <td><span class="form_preview_label">Project Budget Needed</span></td>
                        </tr>
                        <tr>
                            <td><select class="form_preview form_preview_field"><option>$5,000</option><option>$15,000</option><option>$25,000</option><option>$50,000</option></select></td>
                        </tr>
                        <tr>
                            <td><span class="form_preview_label">Service Needed</span></td>
                        </tr>
                        <tr>
                            <td><select class="form_preview form_preview_field" multiple="multiple" style="height:70px; width:200px;"><option>Design</option><option>Development</option><option>Design</option><option>Development</option></select></td>
                        </tr>                       
                    </table>
                </div>
                <small>Form Preview (<a href='javascript:void(0);' onclick='$.get("preview.php?id=<?=$f->id;?>",function(data){$.fancybox({content:data,type:"iframe",width:$("#pixel_width").val()});});'>Full Preview</a>)</small>
                <style id="form_preview_style">
					#form_preview{width:320px;}.form_preview_label{font-family:arial;font-size:14px;font-weight:bold;}.form_preview_field{font-family:arial;font-size:14px; background:#ffffff;}
				</style>
                <style>
                	.form_preview_label{
                		
                		
                	}
                	.form_preview_field{
                		padding: 4px;
                		margin-top:-5px;
                		min-width: 300px;
                		margin-right: 0px;
                	}
                	#form_preview{
                		padding: 10px;
                		padding-right: 0px;
                	}
                </style>
            </div>
            <div class="clear"></div>
            </div>
            <br/>
            <form action="save-details.php" id="main_form" method="post">
            	<input type="hidden" name="id" value="<?=$f->id?>" />
                <input type="hidden" name="embed" id="embed" value="0" />
                <h2 class="left">Team Members Notified</h2><hr class="title_line"/>
                <div class="clear"></div>
                    <div id="members">
                    <?php $members = explode(',',$f->data['members']);?>
                    <?php foreach($account->members as  $mem){
                        $m = new User($mem);?>
                        <label>
                        <div class="team_member">
                            <input type="checkbox" class="left" id="checkbox<?=$m->id?>" name="members[]" <?php if(in_array($m->id,$members)){?> checked<?php } ?> value="<?=$m->id?>" />
                            <div class="sq24"><?=$m->avatar(24,24);?></div>
                            <div class="left"><?=$m->name()?><br/><small class="grey"><?=$m->email?></small></div>
                            <div class="clear"></div>
                        </div>
                        </label>
                    <?php } ?>
                   </div>
                   <br/>
                    <h2 class="left">Form Submission Message</h2><hr class="title_line"/>
                    <?php if(strlen($f->thankyou)){
						$thanks = $f->thankyou;
					}else{
						$thanks ="Thank you for your inquiry. We will be in touch shortly!";
					}?>
                    <textarea name="thank_you" style="width:766px;min-height:125px;"><?=$thanks?></textarea>
                    <br /><br />
                    <table width="100%">
                        <tr>
                            <td colspan="2" align="right"><input type="button" class="button left" value="Cancel" /> <input type='button' class="button blue_button right" value='Save & Embed' onClick="$('#embed').val('1');$('#main_form').submit();"/> <input style="display:inline; text-align:center;" type="submit" class="button blue_button right" value="Save Settings "><br clear="all" /></td>
                         </tr>
                    </table>
                </form>
        </div>
    
    <div class="clear"></div>
</div>
</body>
</html>

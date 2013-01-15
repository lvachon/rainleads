<?php

/*function conDB(){
	$con = mysql_connect("localhost","rl","rldbpass");
	mysql_select_db("new_rainleads",$con);
	return $con;
}*/
function attrs($array){
	$html = "";
	if(!isset($array['style'])){
		$array['style']="";
	}
	foreach($array as $key=>$val){
		if(!in_array($key,array('type','value','multiple','options','label','styles','width'))){
			if($key=="style" && intval($array['width'])){
				$val.=";width:{$array['width']};";
			}
			if($key=="name" && isset($array['multiple'])){$val.='[]';}
			$html .= " $key=\"".htmlentities($val)."\"";
		}
	}
	return $html;
}

$CURRENT_FORM_IDS = array();


class FormElement {
	
	public $type = "";
	public $options = array();
	public $selected = array();
	public $data=array();
	public $value="";	
	public $html="";
	public $uid="";
	public $label="";
	function FormElement($data){
		global $CURRENT_FORM_IDS;
		if(strlen($data['type'])){
			$this->uid = strval(count($CURRENT_FORM_IDS));
			$this->type = $data['type'];
			$this->data = $data;
			if(!intval($this->data['width'])){
				$this->data['width']="95";
			}
			if(!strlen($this->data['class'])){
				$this->data['class']="form_field";
			}
			if(!strlen($this->data['name'])){
				$this->data['name']=$this->type."_".$this->uid;
			}
			$this->label = $data['label'];
			$this->init();
			
		}
		
	}
	
	function init(){
		if($this->type=="text"){
			$this->initText();
		}
		if($this->type=="textarea"){
			if(!intval($this->data['rows'])){
				$this->data['rows']="4";
			}
			$this->initTextarea();
		}
		if($this->type=="select"){
			$this->initSelect();
		}
		if($this->type=="check"){
			$this->initCheck();
		}
		if($this->type=="radio"){
			$this->initRadio();
		}
		if($this->type=="date"){
			$this->initDate();
		}
		if($this->type=="date"){
			$this->initTime();
		}
	}
	//<input type="text" value="" data-fieldid="4" class="preview date_picker_field" readonly="readonly"><button type="button" class="Zebra_DatePicker_Icon Zebra_DatePicker_Icon_Inside" style="left: 115px; top: 0px; display: none; ">Pick a date</button>
	
	function getEditHTML(){
		$html = "";
		switch ($this->type){
			case "text":
				$html = "<input type='text' id = 'field{$this->uid}' data-fieldid='{$this->uid}' value=\"\"". attrs($this->data)." disabled/>";
				break;
			case "textarea":
				$html = "<textarea class='preview'". attrs($this->data)." disabled data-fieldid='{$this->uid}'></textarea>";
				break;
			case "select":
				if(!$this->data['multiple']){
					$html .= "<select data-fieldid='{$this->uid}'".attrs($this->data).">\n";
					for($i=0;$i<count($this->options);$i++){
						$html.= "	<option data-optionID='{$i}' value=\"".htmlentities($this->options[$i])."\"";
						if($this->selected[0]==$i){
							$html .= " selected";
						}
						$html.= ">".htmlentities($this->options[$i])."</option>\n";
					}
					$html .= "</select>";
				}else{
					$html .= "<select data-fieldid='{$this->uid}'".attrs($this->data)." multiple>\n";
					for($i=0;$i<count($this->options);$i++){
						$html.= "	<option data-optionID='{$i}' value=\"".htmlentities($this->options[$i])."\"";
						if(in_array($i,$this->selected)){
							$html .= " selected";
						}
						$html.= ">".htmlentities($this->options[$i])."</option>\n";
					}
					$html .= "</select>";
				}
				break;
			case "check":
				for($i=0;$i<count($this->options);$i++){
					$html.= "	<label><input type='checkbox' value=\"".htmlentities($this->options[$i])."\" ".attrs($this->data);
					if(in_array($i,$this->selected)){
						$html .= " checked";
					}
					$html.= "/>".htmlentities($this->options[$i])."</label>\n";
				}
				break;
			case "radio":
				for($i=0;$i<count($this->options);$i++){
					$html.= "	<label><input type='radio' value=\"".htmlentities($this->options[$i])."\" ".attrs($this->data);
					if($i==$this->selected[0]){
						$html .= " checked";
					}
					$html.= "/>".htmlentities($this->options[$i])."</label>\n";
				}
				break;
			case "date":
			case "time":
				return $this->getHTML();
			break;
		}
		return $html;
	}
	
	
	function getHTML(){
		$html = "";
		switch ($this->type){
			case "text":
				$html = "<input data-field-id='{$this->uid}' type='text' value=\"".htmlentities($this->value)."\"". attrs($this->data)."/>";
				break;
			case "textarea":
				$html = "<textarea data-field-id='{$this->uid}' ". attrs($this->data)." >".htmlentities($this->value)."</textarea>";
				break;
			case "select":
				if(!$this->data['multiple']){
					$html .= "<select".attrs($this->data)." data-field-id='{$this->uid}'>\n";
					for($i=0;$i<count($this->options);$i++){
						$html.= "	<option data-optionID='{$i}' value=\"".htmlentities($this->options[$i])."\"";
						if($this->selected[0]==$i){
							$html .= " selected";
						}
						$html.= ">".htmlentities($this->options[$i])."</option>\n";
					}
					$html .= "</select>";
				}else{
					$html .= "<select".attrs($this->data)." multiple  data-field-id='{$this->uid}'>\n";
					for($i=0;$i<count($this->options);$i++){
						$html.= "	<option data-optionID='{$i}' value=\"".htmlentities($this->options[$i])."\"";
						if(in_array($i,$this->selected)){
							$html .= " selected";
						}
						$html.= ">".htmlentities($this->options[$i])."</option>\n";
					}
					$html .= "</select>";
				}
				break;
			case "check":
				for($i=0;$i<count($this->options);$i++){
					$html.= "	<label><input type='checkbox' value=\"".htmlentities($this->options[$i])."\" ".attrs($this->data);
					if(in_array($i,$this->selected)){
						$html .= " checked";
					}
					$html.= "/>".htmlentities($this->options[$i])."</label>\n";
				}
				break;
			case "radio":
				for($i=0;$i<count($this->options);$i++){
					$html.= "	<label><input type='radio' value=\"".htmlentities($this->options[$i])."\" ".attrs($this->data);
					if($i==$this->selected[0]){
						$html .= " checked";
					}
					$html.= "/>".htmlentities($this->options[$i])."</label>\n";
				}
				break;
			case "date":
				$this->data['class'].= " datepicker";
				$html .= "<div id='date_{$this->uid}' ".attrs($this->data)." >\n";
				$html .= "	<select name='date_month_{$this->uid}' id='month{$this->uid}'>\n";
				$html .= "		<option>Month</option>";
				for($i=1;$i<13;$i++){
					$x = mktime(0,0,0,$i,1,2000);
					$html .= "<option value=\"{$i}\" ";
					if(date("n",$this->value)==$i && $this->value){$html .=" selected ";}
					$html .= ">".date("F",$x)."</option>\n";
				}
				$html .= "	</select>\n\n";
				$html .= "	<select name='date_day_{$this->uid}' id='day{$this->uid}'>\n";
				$html .= "		<option>Day</option>";
				for($i=1;$i<32;$i++){
					$html .= "		<option value=\"{$i}\" ";
					if(date("j",$this->value)==$i && $this->value){
						$html .=" selected ";
					}
					$html .= ">{$i}</option>\n";
				}
				$html .= "	</select>\n\n";
				$html .= "	<select name='date_year_{$this->uid}' id='year{$this->uid}'>\n";
				$html .= "		<option>Year</option>";
				for($i=1900;$i<2100;$i++){
					$html .= "		<option value=\"{$i}\" ";
					if(date("Y",$this->value)==$i && $this->value){
						$html .=" selected ";
					}
					$html .= ">{$i}</option>\n";
				}
				$html .= "	</select>\n\n";
				$html .= "</div>";
				break;
			case "time":
				$this->data['class'] .= " timepicker";
				$html .= "<div id='time_{$this->uid}' ".attrs($this->data)." >\n";
				$html .= "	<select name='time_hour_{$this->uid}' id='hour{$this->uid}'>\n";
				for($i=1;$i<13;$i++){
					$x = mktime(0,0,0,$i,1,2000);
					$html .= "<option value=\"{$i}\" ";
					if(date("g",$this->value)==$i){
						$html .=" selected ";
					}
					$html .= ">{$i}</option>\n";
				}
				$html .= "	</select> : \n\n";
				$html .= "	<select name='time_minute_{$this->uid}' id='minute{$this->uid}'>\n";
				for($i=0;$i<60;$i++){
					$html .= "		<option value=\"{$i}\" ";
					if(date("j",$this->value)==$i){
						$html .=" selected ";
					}
					if($i<10){$i = "0".$i;}
					$html .= ">{$i}</option>\n";
				}
				$html .= "	</select> \n\n";
				$html .= "	<select name='time_ampm_{$this->uid}' id='ampm{$this->uid}'>\n";
				$html .= "		<option value=\"AM\"";
				if(date("A",$this->value)=="AM"){$html .= " selected ";}
				$html .= ">AM</option>\n";
				$html .= "		<option value=\"PM\"";
				if(date("A",$this->value)=="PM"){$html .= " selected ";}
				$html .= ">PM</option>\n";
				$html .= "	</select>\n\n";
				$html .= "</div>";
				break;
		}
		return $html;
	}
	
	
	
	
	function initText(){
		//$this->value = $this->data['value'];
	}
	function initTextarea(){
		//$this->value = $this->data['value'];
	}
	function initSelect(){
		if(!$this->data['multiple']){
			$this->initRadio();
		}else{
			$this->initCheck();
		}
	}
	function initDate(){
		$this->value = intval($this->data['value']);
		//if(!intval($this->value)){$this->value = time();}
	}
	function initTime(){
		$this->initDate();
	}	
	
	function initCheck(){
		$this->options = explode(";",$this->data['options']);
		$this->selected=array(-1);
		$vals = explode(";",$this->value);
		for($i=0;$i<count($this->options);$i++){
			foreach($vals as $val){
				if($this->options[$i]==$val){
					$this->selected[]=$i;
					break;
				}
			}
		}
	}
	function initRadio(){
		$this->options = explode(";",$this->data['options']);
		$this->selected=array(-1);
		for($i=0;$i<count($this->options);$i++){
			if($this->options[$i]==$this->value){
				$this->selected[0]=$i;
				break;
			}
		}
	}
	
}

class Form{
	public $elems = array();
	public $data = array();
	public $id=0;
	public $account_id=0;
	public $datestamp=0;
	public $thankyou="";
	
	function Form($data){
		global $CURRENT_FORM_IDS, $HOME_URL;
		if(!is_numeric($data)){$this->data = $data;}
		else{
			$con = conDB();
			$r = mysql_query("SELECT * from forms where id=".intval($data),$con);
			$f = mysql_fetch_array($r);
			$CURRENT_FORM_IDS=array();
			if(intval($f['id'])){
				$this->elems = unserialize(gzuncompress($f['formdata']));
				if(!is_array($this->elems)){return false;}
				for($i=0;$i<count($this->elems);$i++){
					$this->elems[$i]->uid=$i;
				}
				$this->data = unserialize(gzuncompress($f['extra_data']));
				if(!strlen($this->data['action']) || true){$this->data['action']=$HOME_URL."forms/saveform.php";}
				if(!strlen($this->data['method']) || true){$this->data['method']="post";}
				if(!strlen($this->data['title'])){	$this->data['title']="New Form";}
				$this->id = intval($f['id']);
				$this->datestamp = intval($f['datestamp']);
				$this->account_id = intval($f['account_id']);
				$this->thankyou = $f['thankyou'];
				foreach($this->elems as $elem){
					$CURRENT_FORM_IDS[]=$elem->uid;
					foreach($elem->elems as $e){
						$CURRENT_FORM_IDS[]=$e->uid;
					}
				}
			}else{
				return false;
			}
		}
	}
	
	function getAccount(){
		$account = new Account($this->account_id);
		return $account;
	}
	
	function addElem($element){
		global $CURRENT_FORM_IDS;
		$this->elems[]=$element;
		$CURRENT_FORM_IDS[]=$element->uid;
	}
	
	function editElem($uid,$elem){
		$ac=0;
		foreach($this->elems as $e){
			if($e->uid==$uid){
				$this->elems[$ac]=$elem;
				return true;
			}
			$ac++;
		}
		return false;
	}
	
	function getElem($uid){
		for($ac = 0;$ac<count($this->elems);$ac++){
			$a = $this->elems[$ac];
			if($a->uid==$uid){return $this->elems[$ac];}
		}
		return false;
	}
	
	function getElemByName($name){
		for($ac = 0;$ac<count($this->elems);$ac++){
			$a = $this->elems[$ac];
			if($a->data['name']==$name){
				return $this->elems[$ac];
			}
		}
		return false;
	}
	
	function setElem($uid,$elem){
		for($ac = 0;$ac<count($this->elems);$ac++){
			$a = $this->elems[$ac];
			if($a->uid==$uid){
				$this->elems[$ac] = $elem;
				return true;
			}
		}
		return false;
	}
	
	function delElem($uid){
		for($a=0;$a<count($this->elems);$a++){
			if($this->elems[$a]->uid==$uid){
				array_splice($this->elems,$a,1);return true;
			}
		}
		return false;
	}
	
	function getHTML($preview = false,$tracking_code="",$manual=false){
		global $HOME_URL;
		$html = "<style>{$this->data['styles']}</style>\n";
		if(!$preview){
			$html .= "<form id = 'form_{$this->id}' ".attrs($this->data)."  >\n";
		}else{
			$html .= "<div id = 'form_{$this->id}' ".attrs($this->data)."  >\n";
		}
		if(strlen($tracking_code)){$html .= "<input type='hidden' name='tracking_code' value=\"".htmlentities($tracking_code)."\"/>\n";}
		$html .= "<input type='hidden' name='form_id' value='{$this->id}'/>\n";
		$html .= "<input type='hidden' name='form_data' value=\"".base64_encode(gzcompress(serialize($this->elems)))."\"/>\n";
		$html .= "<table>";
		foreach($this->elems as $row){
			$html .= "<tr><td class='form_label'>".htmlentities($row->label)."</td></tr>\n";
			$html .= "<tr><td id='form_row_{$row->uid}'>".$row->getHTML()."</td></tr>\n";
		}
		if($manual){
			$html .= "<tr><td class='form_label'>Referer</td></tr>\n";
			$html .= "<tr><td id='form_row_{$row->uid}'><input type='text' name='referer'/></td></tr>\n";
		}
		if(!$manual && false){
			//CAPTCHA
			$html .= "<tr><td colspan=\"4\" align=\"center\">\n";
			$html .= "	<div id=\"divrecaptcha\">\n";
			$html .= "		<img id=\"captcha\" src=\"{$HOME_URL}captcha/securimage_show.php\" alt=\"CAPTCHA Image\" style=\"border:1px solid #000;\" /><!--Important-->\n";
			$html .= "		<div><a href=\"#\" onclick=\"document.getElementById('captcha').src = '{$HOME_URL}captcha/securimage_show.php?' + Math.random(); return false\"><small>Can't Read The Words?</small></a></div>\n";
			$html .= "		<br />\n";
			$html .= "		<div class=\"recaptcha_only_if_image\"><strong>Enter the words shown above</strong></div>\n";
			$html .= "		<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" />\n";
			$html .= "		<object type=\"application/x-shockwave-flash\" data=\"{$HOME_URL}captcha/securimage_play.swf?audio_file=/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000\" width=\"19\" height=\"19\">\n";
			$html .= "			<param name=\"movie\" value=\"{$HOME_URL}captcha/securimage_play.swf?audio_file=/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000\" />\n";
			$html .= "		</object>\n";
			$html .= "	</div>\n";
			$html .= "</td></tr>\n";
		}
		if(!$preview){$html .= "<tr><td colspan='2'><input type='button' value='Submit' onclick='if(rainleads_checkForm())".'{$'."(\"#form_{$this->id}\")[0].submit();}'/></td></tr>\n";}
		$html .= "</table>\n</form>";
		return $html;
	}
	
	function getResultHTML(){
		//$html = "<style>{$this->data['styles']}</style>\n";
		//$html .= "<div id='fields'>";
		foreach($this->elems as $row){
			$html .= "<div class='field'>";
			$html .= "	<div class='field_label'>".htmlentities($row->label)."</div>\n";
			if($row->type=="date"){
				$html .= "	<div class='field_value'>".htmlentities(date("M jS Y",$row->value))."</div>\n";
			}else{
				if($row->type=="time"){
					$html .= "	<div class='field_value'>".htmlentities(date("g:i a",$row->value))."</div>\n";
				}else{
					$html .= "	<div class='field_value'>".htmlentities($row->value)."</div>\n";
				}
			}
			$html .= "</div>";
		}
		//$html .= "</div>";
		return $html;
	}
	
	function getValidationJS(){
		$js .= "	function rainleads_checkForm(){\n";
		$js .= "		var a = $(\".reqmissing\");\n";
		$js .= " 		if(a.length>0){\n";
		$js .= "			for(var i=0;i<a.length;i++){\n";
		$js .= "				$(\".reqmissing:eq(\"+i+\")\").removeClass('reqmissing');\n";
		$js .= "			};\n";
		$js .= "		}\n";
		$js .= "		var reqmissing=false;\n";
		foreach($this->elems as $e){
			if(intval($e->data['required'])){
				switch($e->type){
					case "text":
						$js.= "		if(String($(\"input[data-field-id='{$e->uid}']\").val()).length<1)".'{$'."(\"#form_row_{$e->uid}\").addClass('reqmissing');reqmissing=true;}\n";
						break;
					case "textarea":
						$js.= "		if(String($(\"textarea[data-field-id='{$e->uid}']\").val()).length<1)".'{$'."(\"#form_row_{$e->uid}\").addClass('reqmissing');reqmissing=true;}\n";
						break;
					case "select":
						$js.= "		if($(\"select[data-field-id='{$e->uid}']\").val() == null)".'{$'."(\"#form_row_{$e->uid}\").addClass('reqmissing');reqmissing=true;}\n";
						break;
					case "date":
						$js.= "		if($(\"#month{$e->uid}\").val()==\"Month\" || $(\"#day{$e->uid}\").val()==\"Day\" || $(\"#year{$e->uid}\").val()==\"Year\")".'{$'."(\"#form_row_{$e->uid}\").addClass(\"reqmissing\");reqmissing=true;}\n";
						break;
					case "time":
						$js.= "		if($(\"#hour{$e->uid}\").val()==\"\" || $(\"#minute{$e->uid}\").val()==\"\" || $(\"#second{$e->uid}\").val()==\"\")".'{$'."(\"#form_row_{$e->uid}\").addClass(\"reqmissing\");reqmissing=true;}\n";
						break;
						
				}
			}
		}
		$js .= "		if(reqmissing){alert(\"Required fields are missing, they have been highlighted\");}\n";
		$js .= "		return !reqmissing;\n";
		$js .= "	}";
		return $js;
	}
	
	function getEditHTML(){
		$DEFREQ = array("name","email","phone","about");
		$html = "<ul id=\"form_fields\">";
		foreach($this->elems as $row){
			$html .= "<li class='form_field' id='{$row->uid}' draggable='true'>\n";
			$html .= "<div class='edit_field' style='display:none'>\n";
			$html .= "		<div class='left' style='width:320px;'>\n";
			$html .= "			<input type='text' id = 'label{$row->uid}' value=\"".htmlentities($row->label)."\" class='edit'/>\n";
			if($row->type!="date" && $row->type!="time"){
				$html .= "			<span class=\"edit_label\">Width</span>\n";
				$html .= "			<select id='width{$row->uid}' class=\"edit\" onchange=\"textWidth({$row->uid},$(this).val());\">\n";
				$html .= "				<option value='33%' ";
				if($row->data['width']=="33%"){$html .= "selected";}
				$html .= ">Small (1/3)</option>\n";
				$html .= "				<option value='50%' ";
				if($row->data['width']=="50%"){$html .= "selected";}
				$html .= ">Medium (1/2)</option>\n";
				$html .= "				<option value='95%' ";
				if($row->data['width']=="95%"){$html .= "selected";}
				$html .= ">Large (Full)</option>\n";
				$html .= "			</select>\n";
				//$html .= "			<input type=\"number\" id='width{$row->uid}' style=\"width:46px;\" value=\"{$row->data['width']}\" class=\"edit\" onchange=\"textWidth({$row->uid},$(this).val())\">";
			}
			if($row->type=="textarea" || ($row->type=="select" && $row->data['multiple'])){
				$html .= "			<input type=\"number\" id='rows{$row->uid}' style=\"width:36px;\" value=\"{$row->data['rows']}\" class=\"edit\" onchange=\"selectRows({$row->uid},$(this).val())\"><span class=\"edit_label\">Rows</span>\n";
			}
			if($row->type=="select"){
				$html .= "<div class=\"edit_select\" data-fieldid=\"{$row->uid}\">\n";
				for($i=0;$i<count($row->options);$i++){
	                $html .= "	<div class=\"edit_select_option\" data-optionid=\"{$i}\">\n";
	                $html .= "		<span class=\"edit_label\">Option Label</span><br>\n";
	                $html .= "		<input type=\"text\" value=\"".htmlentities($row->options[$i])."\" class=\"edit\" onkeyup=\"optionLabel({$row->uid},{$i},$(this).val())\">\n";
	                if($i>0){$html .=" <img src=\"/img/field-delete-icon.png\" onclick=\"removeSelectOption('{$row->uid}','{$i}')\" />\n";}                                                                
	                $html .= "	</div>\n";
				}
                $html .= "	<div class=\"edit_label add_select_option\" onclick=\"addSelectOption({$row->uid})\"><img src=\"/img/yellow-add.png\"> Add New Option</div>\n";
                $html .= "</div>\n";
			}
			
			$html .= "			<br/>\n";
			$html .= "			".$row->getEditHTML()."\n";
			$html .= "	</div>";
			$html .= "	<div class=\"right\">\n";
            $html .= "		<span class=\"required\">Required</span><input id='required_check_{$row->uid}' type=\"checkbox\"";
            if($row->type=="text"&& (in_array($row->data['name'],$DEFREQ))){$html.= " checked disabled ";}
            if(intval($row->data['required'])){$html .= " checked ";}
            $html .= "><br>\n";
            if(!($row->type=="text" && (in_array($row->data['name'],$DEFREQ)))){
            	$html .= "		<span class=\"red\" onclick=\"delField('{$row->uid}');\">Delete Field</span><img src=\"/img/field-delete-icon.png\" onclick=\"delField('{$row->uid}');\" class=\"delete-field\">\n";
			}
            $html .= "<input type='button' value='Save' onclick='saveField(\"{$row->type}\",\"{$row->uid}\");'/>\n";
            $html .= "	</div>\n";
            $html .= "	<div class=\"clear\"></div>";
            $html .= "</div>\n";
            $html .= "	<div class=\"preview_field\" style=\"\">\n";
            $html .= "       <div class=\"left\" style='width:320px'>\n";
            $html .= "           <div class=\"field_label\" data-labelid=\"{$row->uid}\">{$row->label}</div>\n";
            $row->data['disabled']='disabled';
            $html .= $row->getHTML();       		
            $html .= "       </div>\n";
            //$html .= "       <div class=\"right\">\n";
            //$html .= "       	<span class=\"blue\">Edit</span>\n";
            //$html .= "       </div>\n";
            $html .= "       <div class=\"clear\"></div>\n";
            $html .= "   </div>\n";
            $html .= "   <div class=\"clear\"></div>\n";
            $html .= "</li>";
            
		}
		$html .= "</ul>";
		return $html;
	}
	
	function save(){
		$con = conDB();
		if(intval($this->id)){
			mysql_query("UPDATE forms set formdata=\"".mysql_escape_string(gzcompress(serialize($this->elems)))."\", extra_data=\"".mysql_escape_string(gzcompress(serialize($this->data)))."\", thankyou=\"".mysql_escape_string($this->thankyou)."\" WHERE id=".strval(intval($this->id))." LIMIT 1",$con);
			return mysql_affected_rows($con)*$this->id;
		}else{
			mysql_query("INSERT INTO forms(formdata,extra_data,account_id,datestamp,thankyou) VALUES(\"".mysql_escape_string(gzcompress(serialize($this->elems)))."\",\"".mysql_escape_string(gzcompress(serialize($this->data)))."\",".strval(intval($this->account_id)).",unix_timestamp(),\"".mysql_escape_string($this->thankyou)."\")",$con);
			$this->id = intval(mysql_insert_id($con));
			return $this->id;
		}
	}
	
	function delete(){
		global $ADMIN_IDS;
		if(!$this->id){return false;}
		if($this->user_id != verCookie() && !in_array(verCookie(),$ADMIN_IDS)){
			return false;
		}
		$id = $this->id;
		$con = conDB();
		$tables = array('form_resultsa'=>'form_id','formsa'=>'id');
		foreach($tables as $table => $field){
			$table = substr($table,0,-1);
			mysql_query("DELETE FROM {$table} WHERE {$field} = $id",$con);
		}
	}
	
}

function test(){
	$f = new Form(array('action'=>'#','method'=>'post','id'=>'frm'));
	$f->addElem(new FormElement(array('type'=>"text",'value'=>"moo",'name'=>"test1",'label'=>'Text')));
	$f->addElem(new FormElement(array('type'=>"textarea",'value'=>"moo",'name'=>"test2",'label'=>"Textarea")));
	$f->addElem(new FormElement(array('type'=>"select",'value'=>"five",'name'=>"test3",'options'=>"one;two;three;four;five;six",'label'=>"Single Select")));
	$f->addElem(new FormElement(array('type'=>"select",'value'=>"four",'name'=>"test4",'options'=>"one;two;three;four;five;six","value"=>"two;four","multiple"=>true,"style"=>"width:400px;height:200px;border:solid 3px #FF0000",'label'=>"Multiple Select")));
	$f->addElem(new FormElement(array('type'=>"check",'value'=>"three",'name'=>"test5",'options'=>"one;two;three;four;five;six",'label'=>"Checkboxes")));
	$f->addElem(new FormElement(array('type'=>"radio",'value'=>"two",'name'=>"test5",'options'=>"one;two;three;four;five;six",'label'=>"Radiobuttons")));
	$f->addElem($t);
	echo $f->getHTML();
	$f->save();
}
//test();


<?php
foreach(range(1,12) as $x){
	?>
	<option value="<?=$x;?>" <?php if($selected==$x){echo "selected='selected'";}?>><?=date("F",mktime(0,0,0,$x,1,2010));?></option>
	<?php
}
?>
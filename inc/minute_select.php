<?php foreach(range(00,59) as $x){
	if ($x%15==0) { ?>
	<option value="<?=$x;?>"<?php if($selected > $x-8 && $selected <= $x + 7){echo ' selected="selected"';}?>><?=date("i",mktime(0,$x,0,1,1,2010));?></option>
	<?php }
} ?>
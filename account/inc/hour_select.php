<?php
foreach(range(1,12) as $x){
	?>
	<option value="<?=$x;?>" <?php if($selected==$x){echo "selected='selected'";}?>><?=$x;?></option>
	<?php
}
?>
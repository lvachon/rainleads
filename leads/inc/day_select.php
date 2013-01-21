<?php
foreach(range(1,31) as $x){
	?>
	<option value="<?=$x;?>" <?php if($selected==$x){echo "selected='selected'";}?>><?= $x ?></option>
	<?php
}
?>
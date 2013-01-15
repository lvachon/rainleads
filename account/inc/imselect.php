<?php static $ims = array(
    'AIM',
	'Skype',
	'ICQ',
	'MSN/Live',
	'Yahoo',
	'Gmail',
	'Steam',
	'XBL',
	'PSN'
);

?><select name='imnetwork'>
<?php
foreach($ims as $t){
	?><option value="<?=htmlentities($t);?>" <?php if($t==$cdata['imnetwork']){echo "selected";}?> ><?=htmlentities($t);?></option>
	<?php
}
?>

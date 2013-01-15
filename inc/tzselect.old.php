<?php static $regions = array(
    'Africa' => DateTimeZone::AFRICA,
    'America' => DateTimeZone::AMERICA,
    'Antarctica' => DateTimeZone::ANTARCTICA,
    'Asia' => DateTimeZone::ASIA,
    'Atlantic' => DateTimeZone::ATLANTIC,
    'Europe' => DateTimeZone::EUROPE,
    'Indian' => DateTimeZone::INDIAN,
    'Pacific' => DateTimeZone::PACIFIC
);
$tzlist=array();
foreach ($regions as $name => $mask) {
	$tzlist[] = DateTimeZone::listIdentifiers($mask);
}
?>
<select name='timezone'>
<?php
foreach($tzlist as $t){
	foreach($t as $tz){
		?><option value="<?=htmlentities($tz);?>" <?php if($tz==$cdata['timezone']){echo "selected";}?> ><?=htmlentities($tz);?></option>
	<?php
	}
}
?>

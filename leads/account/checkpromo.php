<?php include '../inc/trois.php';
$con = conDB();
$promo = $_POST['promo'];
$r = mysql_query("SELECT * from promo where lcase(code)=lcase('".mysql_escape_string($promo)."')",$con);
$promo_row = mysql_fetch_array($r);
if(!intval($promo_row['id'])){die("The promo code you entered was invalid");die();}
if($promo_row['type']!='free_months'){die("This promo code cannot be used for this type of transaction.");die();}
echo "This promo code is good for {$promo_row['amount']} free months";
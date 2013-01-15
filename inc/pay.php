<?php include '../inc/trois.php';
include 'authnetfunction.php';
loginRequired();
accountRequired();
$account = $viewer->getAccount();
$AUTHNET_API_LOGIN_ID="4DvS9sFkx3R";
$AUTHNET_API_TX_KEY="8J95Sg6z3t46yVJ5";
$startdate = $account->expires();//This is when the new account starts to be billed
$update = false;
if($account->plandata['name']!=free && $startdate>time()){
	//old subscription exists, try update request first
	$xml = '<?xml version="1.0" encoding="utf-8"?>\n<ARBUpdateSubscriptionRequest xmlns= "AnetApi/xml/v1/schema/AnetApiSchema.xsd">'."\n";	
	$update = true;
}else{
	//no old sub exists, create one
	$xml = '<?xml version="1.0" encoding="utf-8"?>\n<ARBCreateSubscriptionRequest xmlns= "AnetApi/xml/v1/schema/AnetApiSchema.xsd">'."\n";
}
$xml .= "	  <merchantAuthentication>\n";
$xml .= "	  	<name>$AUTHNET_API_LOGIN_ID</name>";
$xml .= "	  	<transactionKey>$AUTHNET_API_TX_KEY</transactionKey>";
$xml .= "	  </merchantAuthentication>";
$p=false;
foreach($SUB_PLANS as $p){
	if($_POST['plan']==$p['name']){$plan=$p;}
}
if(!$p){errorMsg("No plan found with that name: {$_POST['plan']}'");die();}
$xml .= "	<subscription>\n";
$xml .= "		<name>Rainleads {$p['name']}</name>\n";
if(!$update){
	$xml .= "		<paymentSchedule>\n";
	$xml .= "			<interval>\n";
	$xml .= "				<length>1</length>\n";
	$xml .= "				<unit>months</unit>\n";
	$xml .= "			</interval>\n";
	$xml .= "			<startDate>".date("Y-m-d",$startdate)."</startDate>\n";
	$xml .= "			<totalOccurrences>9999</totalOccurrences>\n";
	$xml .= "		</paymentSchedule>\n";
}
$xml .= "		<amount>{$plan['price']}</amount>\n"
$xml .= "		<payment>\n";
$xml .= "			<creditCard>\n";
$xml .= "				<cardNumber>{$_POST['ccnum']}</cardNumber>\n";
$xml .= "				<expirationDate>{$_POST['ccnum']}</expirationDate>\n";
$xml .= "				<cardCode>{$_POST['ccnum']}</cardCode>\n";
$xml .= "			</creditCard>\n";
$xml .= "		</payment>\n";
$xml .= "		<customer>\n";
$xml .= "			<id>{$account->id}</id>\n";
$xml .= "			<email>{$viewer->email}</email>\n";
$xml .= "		</customer>\n";
$xml .= "		<billTo>\n";
$xml .= "			<firstName>{$_POST['bfname']}</firstName>\n";
$xml .= "			<lastName>{$_POST['blname']}</lastName>\n";
$xml .= "			<address>{$_POST['baddress1']}</address>\n";
$xml .= "			<city>{$_POST['bcity']}</city>\n";
$xml .= "			<state>{$_POST['bstate']}</state>\n";
$xml .= "			<zip>{$_POST['bzip']}</zip>\n";
$xml .= "		</billTo>\n";
$xml .= "	</subscription>\n"
$xml .= "</ARBCreateSubscriptionRequest>";

$response = send_request_via_curl("https://api.authorize.net","/xml/v1/request.api",$xml);

if ($response)
{
	/*
	 a number of xml functions exist to parse xml results, but they may or may not be avilable on your system
	please explore using SimpleXML in php 5 or xml parsing functions using the expat library
	in php 4
	parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
	*/
	list ($refId, $resultCode, $code, $text, $subscriptionId) =parse_return($response);


	echo " Response Code: $resultCode <br>";
	echo " Response Reason Code: $code<br>";
	echo " Response Text: $text<br>";
	echo " Reference Id: $refId<br>";
	echo " Subscription Id: $subscriptionId <br><br>";
	echo " Data has been written to data.log<br><br>";
	

	$fp = fopen('data.log', "a");
	fwrite($fp, "$refId\r\n");
	fwrite($fp, "$subscriptionId\r\n");
	fwrite($fp,"------------\r\n\r\n");
	fclose($fp);
	$con = conDB();
	mysql_query("UPDATE account set sub_id='".mysql_escape_string($subscriptionId)."' plantype='".mysql_escape_string($plan['name'])."' where id={$account->id} LIMIT 1",$con);

}
else
{
	echo "Transaction Failed. <br>";
}


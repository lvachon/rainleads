<?php include '../inc/trois.php';
include 'authnetfunction.php';
loginRequired();
accountRequired();
$con = conDB();
$account = $viewer->getAccount();
$AUTHNET_API_LOGIN_ID="7tZv74PcN7";
$AUTHNET_API_TX_KEY="6WM9t39tQ9R9Us9Y";

$delta = intval($_POST['delta']);

$things = array(array("name"=>"+100MB storage","price"=>5,"tx"=>"storage"),array("name"=>"+1 User account","price"=>5,"tx"=>"user"));
$thing = $things[intval($_POST['thing'])];
if(!strlen($thing['tx'])){errorMsg("Invalid selection");die();}
$dtx = "add";
if($delta<0){
	//They want to remove a thing, see if they have a thing to remove first
	$dtx="rem";
	$r = mysql_query("SELECT count(*) from transactions where type='add_{$thing['tx']}' and account_id={$account->id}",$con);
	$add = mysql_fetch_array($r);
	$add = intval($add[0]);
	$r = mysql_query("SELECT count(*) from transactions where type='rem_{$thing['tx']}' and account_id={$account->id}",$con);
	$rem = mysql_fetch_array($r);
	$rem = intval($rem[0]);
	$total = $add-$rem;
	
	if($total<$delta){errorMsg("You cannot remove something you haven't added");die();}
}else{
	if($account->membership=="free" || $account->memberhip=="lite"){
		errorMsg("Your account type is not able to add a la carte items, please <a href='{$HOME_URL}account/upgrage.php'>upgrade</a> your account to Basic or Pro.");
		die();
	}
}
//ok delta and account checks out, compute the price

$price = $account->mo_price+$thing['price']*$delta;

//one last sanity check

if($price<0 || $price < $account->plandata['price']){
	errorMsg("I don't know what you did, but you shouldn't have done it.  You sneaky little...");die();
}

$xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<ARBUpdateSubscriptionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'."\n";
$xml .= "	<merchantAuthentication>\n";
$xml .= "		<name>$AUTHNET_API_LOGIN_ID</name>";
$xml .= "		<transactionKey>$AUTHNET_API_TX_KEY</transactionKey>";
$xml .= "	</merchantAuthentication>";
$xml .= "	<subscriptionId>{$account->sub_id}</subscriptionId>";
$xml .= "	<subscription>\n";
$xml .= "		<amount>{$price}</amount>\n";
$xml .= "	</subscription>\n";
$xml .= "</ARBUpdateSubscriptionRequest>";


$response = send_request_via_curl("api.authorize.net","/xml/v1/request.api",$xml);

if ($response)
{
	/*
	 a number of xml functions exist to parse xml results, but they may or may not be avilable on your system
	please explore using SimpleXML in php 5 or xml parsing functions using the expat library
	in php 4
	parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
	*/
	list ($refId, $resultCode, $code, $text, $subscriptionId) =parse_return($response);

	if($text!="Successful."){
		errorMsg($text);
		die();
	}



	mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'{$dtx}_{$thing['tx']}','".mysql_escape_string(serialize(array('sent'=>$xml,'read'=>$response)))."',{$thing['price']},unix_timestamp())",$con);

	

	$fp = fopen('data.log', "a");
	fwrite($fp, "$xml\r\n");
	fwrite($fp, "$subscriptionId\r\n");
	fwrite($fp,"------------\r\n\r\n");
	fclose($fp);
	$con = conDB();
	mysql_query("UPDATE accounts set mo_price=$price where id={$account->id} LIMIT 1",$con);
	header("Location: alacarte.php");
}
else
{
	echo "Transaction Failed. <br>";
}


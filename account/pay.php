<?php

include_once '/var/www/vhosts/mcgrish.com/httpdocs/inc/trois.php';
include '/var/www/vhosts/mcgrish.com/httpdocs/account/authnetfunction.php';

loginRequired();
accountRequired();
$account = $viewer->getAccount();
$signup=false;

$con = conDB();
$AUTHNET_API_LOGIN_ID="7tZv74PcN7";
$AUTHNET_API_TX_KEY="6WM9t39tQ9R9Us9Y";
$startdate = $account->expiration;
if($startdate<time()){$startdate=time();}
$update = false;

$ccnum = $_POST['ccnum'];
$expdate = "20".$_POST['expyr']."-".$_POST['expmo'];
$ccode = $_POST['ccv'];
$fname = $_POST['bfname'];
$lname = $_POST['blname'];
$address = $_POST['baddress'];
$city = $_POST['bcity'];
$state = $_POST['bstate'];
$zip = $_POST['bzip'];
$promo = $_POST['promo'];
$email = $viewer->email;

//Check for promocode, set promo row if applicable
if(strlen($promo)){
	$r = mysql_query("SELECT * from promo where lcase(code)=lcase('".mysql_escape_string($promo)."')",$con);
	$promo_row = mysql_fetch_array($r);
	if(!intval($promo_row['id'])){errorMsg("The promo code you entered was invalid");die();}
	if($promo_row['type']!='free_months'){errorMsg("This promo code cannot be used for this type of transaction.");die();}
	
}else{
	$promo_row=false;
}


//First step, authorize their card
$post_url = "https://secure.authorize.net/gateway/transact.dll";
$post_values = array(

		// the API Login ID and Transaction Key must be replaced with valid values
		"x_login"			=> $AUTHNET_API_LOGIN_ID,
		"x_tran_key"		=> $AUTHNET_API_TX_KEY,

		"x_version"			=> "3.1",
		"x_delim_data"		=> "TRUE",
		"x_delim_char"		=> "|",
		"x_relay_response"	=> "FALSE",

		"x_type"			=> "AUTH_ONLY",
		"x_method"			=> "CC",
		"x_card_num"		=> $ccnum,
		"x_exp_date"		=> $expdate,
		"x_card_code"		=> $ccode,

		"x_amount"			=> "12.00",

		"x_first_name"		=> $fname,
		"x_last_name"		=> $lname,
		"x_address"			=> $address,
		"x_city"			=> $city,
		"x_state"			=> $state,
		"x_zip"				=> $zip

);

fwrite($f,"C");
$post_string = "";
foreach( $post_values as $key => $value )
{
	$post_string .= "$key=" . urlencode( $value ) . "&";
}
$post_string = rtrim( $post_string, "& " );
$request = curl_init($post_url); // initiate curl object
curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
$post_response = curl_exec($request); // execute curl post and store results in $post_response
curl_close ($request); // close curl object
$response_array = explode($post_values["x_delim_char"],$post_response);

if(intval($response_array[0])!=1){
	$post_values['x_card_num']="**** **** **** ".substr($post_values,strlen($post_values['x_card_num'])-4);//Censor ccnum to last four only
	mysql_query("INSERT INTO transactions(user_id,account_id,type,data,datestamp) VALUES({$viewer->id},{$account->id},'sub_fail','".mysql_escape_string(serialize(array('sent'=>$post_values,'read'=>$response_array)))."',unix_)",$con);
	if(!$signup){
		errorMsg("Your card was not accepted: {$response_array[3]}");
		die();
	}else{
		header("Location: /account/firstuser.php?msg=".urlencode("Your card was not accepted: {$response_array[3]}"));
		die();
	}
}

//If we've gotten this far, the auth was OK, so now we can void that auth.

$post_values = array(

		// the API Login ID and Transaction Key must be replaced with valid values
		"x_login"			=> $AUTHNET_API_LOGIN_ID,
		"x_tran_key"		=> $AUTHNET_API_TX_KEY,

		"x_version"			=> "3.1",
		"x_delim_data"		=> "TRUE",
		"x_delim_char"		=> "|",
		"x_relay_response"	=> "FALSE",

		"x_type"			=> "VOID",
		"x_trans_id"		=> $response_array[6]

);
$post_string = "";
foreach( $post_values as $key => $value )
{
	$post_string .= "$key=" . urlencode( $value ) . "&";
}
fwrite($f,"D");
$post_string = rtrim( $post_string, "& " );
$request = curl_init($post_url); // initiate curl object
curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
$post_response = curl_exec($request); // execute curl post and store results in $post_response
curl_close ($request); // close curl object
$response_array = explode($post_values["x_delim_char"],$post_response);

//AUth voided



$xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<ARBCreateSubscriptionRequest xmlns= "AnetApi/xml/v1/schema/AnetApiSchema.xsd">'."\n";
$xml .= "	<merchantAuthentication>\n";
$xml .= "		<name>$AUTHNET_API_LOGIN_ID</name>";
$xml .= "		<transactionKey>$AUTHNET_API_TX_KEY</transactionKey>";
$xml .= "	</merchantAuthentication>";
$xml .= "	<subscription>\n";
$xml .= "		<name>Rainleads</name>\n";
$xml .= "		<paymentSchedule>\n";
$xml .= "			<interval>\n";
$xml .= "				<length>1</length>\n";
$xml .= "				<unit>months</unit>\n";
$xml .= "			</interval>\n";
$xml .= "			<startDate>".date("Y-m-d",$startdate)."</startDate>\n";
$xml .= "			<totalOccurrences>9999</totalOccurrences>\n";
if(intval($promo_row['amount'])){
	$xml .= "			<trialOccurrences>{$promo_row['amount']}</trialOccurrences>\n";
}
$xml .= "		</paymentSchedule>\n";
$xml .= "		<amount>12.00</amount>\n";
if(intval($promo_row['id'])){
	$xml .= "		<trialAmount>0</trialAmount>\n";	
}
$xml .= "		<payment>\n";
$xml .= "			<creditCard>\n";
$xml .= "				<cardNumber>{$ccnum}</cardNumber>\n";
$xml .= "				<expirationDate>{$expdate}</expirationDate>\n";
$xml .= "				<cardCode>{$ccode}</cardCode>\n";
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
$xml .= "	</subscription>\n";
$xml .= "</ARBCreateSubscriptionRequest>";

$response = send_request_via_curl("api.authorize.net","/xml/v1/request.api",$xml);
fwrite($f,"E");
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
		if(!$signup){
			errorMsg($text);
			die();
		}else{
			header("Location: /account/upgrade.php?msg=".urlencode($text));
			die();
		}
	}
	
	
	$xml = str_replace($_POST['ccnum'],"xxxx xxxx xxxx ".substr($_POST['ccnum'],strlen($_POST['ccnum'])-4),$xml);
	
	mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp,txid) VALUES({$viewer->id},{$account->id},'sub_create','".mysql_escape_string(serialize(array('sent'=>$xml,'read'=>$response)))."',12,unix_timestamp(),'$subscriptionId')",$con);
	
	
	if($promo_row){
		$account->data['promoUsed']=$promo_row['code'];
		$account->data['promoEnds']=strval(time()+86400*30*intval($promo_row['amount']));
		$account->save();
	}
	
	
	$fp = fopen('data.log', "a");
	fwrite($fp, "$xml\r\n");
	fwrite($fp, "$subscriptionId\r\n");
	fwrite($fp,"------------\r\n\r\n");
	fclose($fp);
	$con = conDB();
	mysql_query("UPDATE accounts set sub_id='".mysql_escape_string($subscriptionId)."', plantype='paid',mo_price=12 where id={$account->id} LIMIT 1",$con);
	header("Location: {$HOME_URL}/account/index.php");
}
else
{
	echo "Transaction Failed. <br>";
}

fwrite($f,"F");
fclose($f);

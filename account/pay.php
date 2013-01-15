<?php include '../inc/trois.php';
include 'authnetfunction.php';
loginRequired();
accountRequired();
$con = conDB();
$account = $viewer->getAccount();
$AUTHNET_API_LOGIN_ID="7tZv74PcN7";
$AUTHNET_API_TX_KEY="6WM9t39tQ9R9Us9Y";
$startdate = $account->expiration;
if($startdate<time()){$startdate=time();}
$update = false;
$plan=false;
foreach($SUB_PLANS as $p){
	if($_POST['plan']==$p['name']){
		$plan=$p;
	}
}
if(!$plan){
	errorMsg("No plan found with that name: {$_POST['plan']}'");die();
}
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

		"x_amount"			=> $plan['price'],

		"x_first_name"		=> $fname,
		"x_last_name"		=> $lname,
		"x_address"			=> $address,
		"x_city"			=> $city,
		"x_state"			=> $state,
		"x_zip"				=> $zip,
		"x_email"			=> $email

);
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
	errorMsg("Your card was not accepted: {$response_array[3]}");
	die();
}


if(strlen($account->sub_id)){
	//old subscription exists, try update request first
	$xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<ARBUpdateSubscriptionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'."\n";
	$update = true;
}else{
	//no old sub exists, create one
	$xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<ARBCreateSubscriptionRequest xmlns= "AnetApi/xml/v1/schema/AnetApiSchema.xsd">'."\n";
}
$xml .= "	<merchantAuthentication>\n";
$xml .= "		<name>$AUTHNET_API_LOGIN_ID</name>";
$xml .= "		<transactionKey>$AUTHNET_API_TX_KEY</transactionKey>";
$xml .= "	</merchantAuthentication>";
if($update){	$xml .= "	<subscriptionId>{$account->sub_id}</subscriptionId>";}
$xml .= "	<subscription>\n";
if(!$update){
	$xml .= "		<name>Rainleads {$plan['name']}</name>\n";
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
}
$xml .= "		<amount>{$plan['price']}</amount>\n";
if(intval($promo_row['id']) && !$update){
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
if(!$update){
	$xml .= "</ARBCreateSubscriptionRequest>";
}else{
	$xml .= "</ARBUpdateSubscriptionRequest>";
}

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
	
	
	$xml = str_replace($_POST['ccnum'],"xxxx xxxx xxxx ".substr($_POST['ccnum'],strlen($_POST['ccnum'])-4),$xml);
	
	if(!$update){mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp,txid) VALUES({$viewer->id},{$account->id},'sub_create','".mysql_escape_string(serialize(array('sent'=>$xml,'read'=>$response)))."',{$plan['price']},unix_timestamp(),'$subscriptionId')",$con);}
	else{mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'sub_update','".mysql_escape_string(serialize(array('sent'=>$xml,'read'=>$response)))."',{$plan['price']},unix_timestamp())",$con);}
	
	if($promo_row){
		$account->data['promoUsed']=$promo_row['code'];
		$account->data['promoEnds']=strval(time()+86400*30*intval($promo_row['amount']));
		$account->save();
	}
	
	if($update){
		$subscriptionId=$account->sub_id;
		
		//remove storage additions
		$r = mysql_query("SELECT count(*) from transactions where type='add_storage' and account_id={$account->id}",$con);
		$add = mysql_fetch_array($r);
		$add = intval($add[0]);
		$r = mysql_query("SELECT count(*) from transactions where type='rem_storage' and account_id={$account->id}",$con);
		$rem = mysql_fetch_array($r);
		$rem = intval($rem[0]);
		$total = $add-$rem;
		for($i=0;$i<$total;$i++){
			mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'rem_storage','".mysql_escape_string(serialize("Automatic cancel upon subscription"))."',0,unix_timestamp())",$con);
		}
		//remove user additions
		$r = mysql_query("SELECT count(*) from transactions where type='add_user' and account_id={$account->id}",$con);
		$add = mysql_fetch_array($r);
		$add = intval($add[0]);
		$r = mysql_query("SELECT count(*) from transactions where type='rem_user' and account_id={$account->id}",$con);
		$rem = mysql_fetch_array($r);
		$rem = intval($rem[0]);
		$total = $add-$rem;
		for($i=0;$i<$total;$i++){
			mysql_query("INSERT INTO transactions(user_id,account_id,type,data,amount,datestamp) VALUES({$viewer->id},{$account->id},'rem_user','".mysql_escape_string(serialize("Automatic cancel upon subscription"))."',0,unix_timestamp())",$con);
		}
		
	}
	
	
	
	$fp = fopen('data.log', "a");
	fwrite($fp, "$xml\r\n");
	fwrite($fp, "$subscriptionId\r\n");
	fwrite($fp,"------------\r\n\r\n");
	fclose($fp);
	$con = conDB();
	mysql_query("UPDATE accounts set sub_id='".mysql_escape_string($subscriptionId)."', plantype='".mysql_escape_string($plan['name'])."',mo_price={$plan['price']} where id={$account->id} LIMIT 1",$con);
	header("Location: index.php");
}
else
{
	echo "Transaction Failed. <br>";
}


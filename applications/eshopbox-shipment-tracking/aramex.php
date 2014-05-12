<?php
//echo 'asdasd'; exit;
error_reporting(1);
//include_once('shipping-services-api-wsdl.wsdl');
//echo 'abc';
//exit;

	//include_once('http://ws.dev.aramex.net/shippingapi/tracking/service_1_0.svc');
	$soapClient = new SoapClient('shipments-tracking-api-wsdl.wsdl');
	//$soapClient->__setLocation('http://ws.dev.aramex.net/shippingapi/tracking/service_1_0.svc');
	echo '<pre>';
	// shows the methods coming from the service 
	print_r($soapClient->__getFunctions());
	
	/*
		parameters needed for the trackShipments method , client info, Transaction, and Shipments'  Numbers.
		Note: Shipments array can be more than one shipment.
	*/
	$params = array(
		'ClientInfo'  			=> array(
									'AccountCountryCode'	=> 'JO',
									'AccountEntity'		=> 'AMM',
									'AccountNumber'		=> '20016',
									'AccountPin'		=> '331421',
									'UserName'		=> 'reem@reem.com',
									'Password'		=> '123456789',
									'Version'		=> 'v1.0'
								),

		'Transaction' 			=> array(
									'Reference1'			=> '4695351430' 
								),
		'Shipments'				=> array(
									'4695351430'
								)
	);
	
	// calling the method and printing results
	try {
		$auth_call = $soapClient->TrackShipments($params);
		print_r($auth_call);
	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}





?>

<?php

$email = isset($_GET['email']) ? $_GET['email'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';

if (empty($name) || empty($email)) {
	exit();
}

require_once('aweber_api/aweber_api.php');

$consumerKey    	= 'AkDrW8EgsX6mB41OMr3kvmHW';
$consumerSecret 	= 'oAiyAoZvrEzsID47Vxmu5ebu4eCuQKWTVux2TKts';
$accessTokenKey 	= 'AgZQawqoqkO2x6DWMR3RyvOv';
$accessTokenSecret 	= 'pKPCwtII7ulmjy7GT9QiM4ARKsDczcvXVRmbFupN';
$account_id 		= '544917';
$list_id 			= '4200583';

$aweber = new AWeberAPI($consumerKey, $consumerSecret);

try {
	$account = $aweber->getAccount($accessTokenKey, $accessTokenSecret);
	$list = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_id}");
	$params = array(
		'email' 		=> $email,
		'ip_address' 	=> $_SERVER['REMOTE_ADDR'],
		'ad_tracking' 	=> 'client_lib_example',
		'last_followup_message_number_sent' => 1,
		'misc_notes' 	=> 'notes',
		'name' 			=> $name
	);

	$subscribers = $list->subscribers;
	$new_subscriber = $subscribers->create($params);
} catch(AWeberAPIException $exc) {
	
}


?>
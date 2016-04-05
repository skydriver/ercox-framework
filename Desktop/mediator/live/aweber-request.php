<?php

require_once('aweber_api/aweber_api.php');

/*
$consumerKey    	= 'Ak3dYij0fvwWIeb2D90Oyawj';
$consumerSecret 	= 'rkwFt1ySTT71JZwqU4Oo6JdBjquSljTHUSEvDFAY';
$accessTokenKey 	= 'AgIno8gMZG8HfFBfBL08hp3u';
$accessTokenSecret 	= 'r7IkBnt6jnYPVVJw7yOkQicYNQtoc7lwP04tEOSM';
$account_id 		= '544917';
$list_id 			= '4200540';

$consumerKey    	= $_GET['consumerKey'];
$consumerSecret 	= $_GET['consumerSecret'];
$accessTokenKey 	= $_GET['accessTokenKey'];
$accessTokenSecret 	= $_GET['accessTokenSecret'];
$account_id 		= $_GET['account_id'];
$list_id 			= $_GET['list_id'];
*/

$consumerKey    	= 'AkDrW8EgsX6mB41OMr3kvmHW';
$consumerSecret 	= 'oAiyAoZvrEzsID47Vxmu5ebu4eCuQKWTVux2TKts';
$accessTokenKey 	= 'AgZQawqoqkO2x6DWMR3RyvOv';
$accessTokenSecret 	= 'pKPCwtII7ulmjy7GT9QiM4ARKsDczcvXVRmbFupN';
$account_id 		= '544917';
$list_id 			= '4200540';

$email 		= $_GET['email'];
$name 		= $_GET['name'];
$pwd 		= $_GET['pwd'];

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
		'name' 			=> $name,
		'custom_fields' => array(
			'WP Pass' => $pwd
		),
	);

	$subscribers = $list->subscribers;
	$new_subscriber = $subscribers->create($params);
} catch(AWeberAPIException $exc) {
	
}

?>
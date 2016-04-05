<?php

// CSRF protection
/*
if (!isset($_REQUEST['state'])) {
	exit('Access denied');
}
*/

require_once('../../../wp-load.php');

define('REDIRECT_URI', 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
echo REDIRECT_URI;
exit();

// var_dump( $_REQUEST );
// echo '<hr />';

if (isset($_REQUEST['code'])) {
	$linkedin = new CodeArtLinkedIn(LINKEDIN_CLIENT_ID, LINKEDIN_CLIENT_SECRET);
	$accessToken = $linkedin->auth_second($_REQUEST['code']);
	var_dump($accessToken);
	/*
		$params = array();
		$params['oauth2_access_token'] = $_REQUEST['code'];
		$params['format'] = 'json';

		$url = 'https://api.linkedin.com/v1/people/~?' . http_build_query($params);
		$content = file_get_contents($url);
		var_dump($content);
	*/

	// $linkedin_response = $linkedin->auth_second();
} else {
	var_dump($_REQUEST);
}


?>
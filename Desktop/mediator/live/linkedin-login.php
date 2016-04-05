<?php

require_once('../../../wp-load.php');



// Change these
define('API_KEY', LINKEDIN_CLIENT_ID);
define('API_SECRET', LINKEDIN_CLIENT_SECRET);
// You must pre-register your redirect_uri at https://www.linkedin.com/secure/developer
define('REDIRECT_URI', 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
// define('SCOPE', 'r_basicprofile r_emailaddress' );
define('SCOPE', 'r_basicprofile r_emailaddress');



// You'll probably use a database
session_name('linkedin_login');
session_start();
 
// OAuth 2 Control Flow
if (isset($_GET['error'])) {
    // LinkedIn returned an error
    // print $_GET['error'] . ': ' . $_GET['error_description'];
    codeart_linkedin_print_error($_GET['error'] . ': ' . $_GET['error_description'] . '. Please contact the administartor.');
    exit;
} elseif (isset($_GET['code'])) {
    // User authorized your application
    if ($_SESSION['state'] == $_GET['state']) {
        // Get token so you can make API calls
        getAccessToken();
    } else {
        // CSRF attack? Or did you mix up your states?
        exit;
    }
} else { 
    if ((empty($_SESSION['expires_at'])) || (time() > $_SESSION['expires_at'])) {
        // Token has expired, clear the state
        $_SESSION = array();
    }
    if (empty($_SESSION['access_token'])) {
        // Start authorization process
        getAuthorizationCode();
    }
}



$profile_fields = array(
    'firstName',
    'lastName',
    'email-address'
);

$profile_fields_string = implode(',', $profile_fields);

// Congratulations! You have a valid token. Now fetch your profile 
$user = fetch('GET', '/v1/people/~:(' . $profile_fields_string . ')');

$useremail = sanitize_email( $user->emailAddress );
$userdata = get_user_by( 'email', $useremail );

// Redirect URL //
if( is_object($userdata) && email_exists($userdata->user_email) )
{
    wp_clear_auth_cookie();
    wp_set_current_user( $userdata->ID );
    wp_set_auth_cookie( $userdata->ID );

    $redirect_to = get_bloginfo('url') . '/edit/';
    wp_safe_redirect( $redirect_to );
}
else
{
    codeart_linkedin_print_error('Email not exists...');
}

exit;




function getAuthorizationCode() {
    $params = array(
        'response_type' => 'code',
        'client_id'     => API_KEY,
        'scope'         => SCOPE,
        'state'         => uniqid('', true), // unique long string
        'redirect_uri'  => REDIRECT_URI,
    );
 
    // Authentication request
    $url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);
     
    // Needed to identify request when it returns to us
    $_SESSION['state'] = $params['state'];
 
    // Redirect user to authenticate
    header("Location: $url");
    exit;
}
     
function getAccessToken() {
    $params = array(
        'grant_type'    => 'authorization_code',
        'client_id'     => API_KEY,
        'client_secret' => API_SECRET,
        'code'          => $_GET['code'],
        'redirect_uri'  => REDIRECT_URI,
    );
     
    // Access Token request
    $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
     
    // Tell streams to make a POST request
    $context = stream_context_create(
        array('http' => 
            array('method' => 'POST',
            )
        )
    );
 
    // Retrieve access token information
    $response = file_get_contents($url, false, $context);
 
    // Native PHP object, please
    $token = json_decode($response);
 
    // Store access token and expiration time
    $_SESSION['access_token'] = $token->access_token; // guard this! 
    $_SESSION['expires_in']   = $token->expires_in; // relative time (in seconds)
    $_SESSION['expires_at']   = time() + $_SESSION['expires_in']; // absolute time
     
    return true;
}
 
function fetch($method, $resource, $body = '') {
    // print $_SESSION['access_token'];
 
    $opts = array(
        'http'=>array(
            'method' => $method,
            'header' => "Authorization: Bearer " . $_SESSION['access_token'] . "\r\n" . "x-li-format: json\r\n"
        )
    );
 
    // Need to use HTTPS
    $url = 'https://api.linkedin.com' . $resource;
 
    // Append query parameters (if there are any)
    if (count($params)) { $url .= '?' . http_build_query($params); }
 
    // Tell streams to make a (GET, POST, PUT, or DELETE) request
    // And use OAuth 2 access token as Authorization
    $context = stream_context_create($opts);
 
    // Hocus Pocus
    $response = file_get_contents($url, false, $context);
 
    // Native PHP object, please
    return json_decode($response);
}
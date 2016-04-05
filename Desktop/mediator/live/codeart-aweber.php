<?php

// Complete example on how to add a subscriber to your List.

// Refer to our getting started guide for a complete API walkthrough
// https://labs.aweber.com/getting_started/main

require_once('aweber_api/aweber_api.php');

$consumerKey    = 'Ak3dYij0fvwWIeb2D90Oyawj'; # put your credentials here
$consumerSecret = 'rkwFt1ySTT71JZwqU4Oo6JdBjquSljTHUSEvDFAY'; # put your credentials here
$accessKey      = 'AgIno8gMZG8HfFBfBL08hp3u'; # put your credentials here
$accessSecret   = 'r7IkBnt6jnYPVVJw7yOkQicYNQtoc7lwP04tEOSM'; # put your credentials here
$account_id     = '544917'; # put the Account ID here
$list_id        = '4200540'; # put the List ID here

$aweber = new AWeberAPI($consumerKey, $consumerSecret);

try {
    $account = $aweber->getAccount($accessKey, $accessSecret);
    _debug($account);

    $listURL = "/accounts/{$account_id}/lists/{$list_id}";

    $list = $account->loadFromUrl($listURL);

    # create a subscriber
    $params = array(
        'email' => 'damjan123@codeart.mk'
    );
    $subscribers = $list->subscribers;
    $new_subscriber = $subscribers->create($params);

    # success!
    print "A new subscriber was added to the $list->name list!";

} catch(AWeberAPIException $exc) {
    print "<h3>AWeberAPIException:</h3>";
    print " <li> Type: $exc->type              <br>";
    print " <li> Msg : $exc->message           <br>";
    print " <li> Docs: $exc->documentation_url <br>";
    print "<hr>";
    exit(1);
}

function _debug($val)
{
    echo '<pre>' . print_r($val, true) . '</pre>';
}

?>
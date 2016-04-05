<?php

require_once('aweber_api/aweber_api.php');

$consumerKey    = "AkDrW8EgsX6mB41OMr3kvmHW"; # put your consumer key here
$consumerSecret = "oAiyAoZvrEzsID47Vxmu5ebu4eCuQKWTVux2TKts"; # put your consumer secret heres

$aweber = new AWeberAPI($consumerKey, $consumerSecret);

# Get an access token
if (empty($_COOKIE['accessToken'])) {
    if (empty($_GET['oauth_token'])) {
        $callbackUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);
        setcookie('requestTokenSecret', $requestTokenSecret);
        setcookie('callbackUrl', $callbackUrl);
        header("Location: {$aweber->getAuthorizeUrl()}");
        exit();
    }

    $aweber->user->tokenSecret = $_COOKIE['requestTokenSecret'];
    $aweber->user->requestToken = $_GET['oauth_token'];
    $aweber->user->verifier = $_GET['oauth_verifier'];
    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
    setcookie('accessToken', $accessToken);
    setcookie('accessTokenSecret', $accessTokenSecret);
    header('Location: '.$_COOKIE['callbackUrl']);
    exit();
}

# Get AWeber Account
$account = $aweber->getAccount($_COOKIE['accessToken'], $_COOKIE['accessTokenSecret']);

# iterative example (loop thru each list, grabbing its subscribers collection, 
# then loop thru each subscriber printing their email address
echo "<hr>";
foreach ($account->lists as $list) {
    print " <li> LIST: " . $list->name;
        foreach ($list->subscribers as $subscriber) {
            print "<li> SUBSCRIBER: " . $subscriber->email;
        }
}
echo "<hr>";



# its faster to be direct if you know the URL, so you can do this
$subscribers_collection = $aweber->loadFromUrl('https://api.aweber.com/1.0/accounts/../lists/../subscribers');
print "<li>self link: " . $subscribers_collection->url . "<br>";
foreach ($subscribers_collection as $subscriber) {
    print " <li> " . $subscriber->email . "<br>";
}
echo "<hr>";



# finding a subscriber:
# in the example above you attempted to go to the find subscriber url directly, instead, call the 'find' method
# on the subscriber collection.  it does quite a few things for you, such as escaping your data.  See below:

$found_subscribers_collection = $subscribers_collection->find(array('email' => 'user@example.com'));
print "<li>total size: " . $found_subscribers_collection->total_size . "<br>";

foreach ($found_subscribers_collection as $subscriber) {
    print " <li> " . $subscriber->email . "<br>";
}


# find() is available for any collection in the client library, but currently our api only supports find()
#  on a subscriber collection, and it only accepts an email address.   find() returns a collection, but in the
#  case of finding a subscriber by email address, there will either be 0 or 1 results.


# updating subscriber information
# updating is fairly easy, just change the name of the attribute, then call its 'save()' method
print "<hr>";
print " <li> name before: " . $subscriber->name . "<br>";
$subscriber->name = "harry";
$subscriber->save();
print " <li> name after: " . $subscriber->name . "<br>";



# deleting a subscriber
# just call the delete method  (CAUTION, once deleted, they are DELETED, there is no undo feature for this)
$subscriber->delete();



# alternately if you just want to unsubscribe them from your list, do this
$subscriber->status = 'unsubscribed';
$subscriber->save();


# creating a new resource:
# currently we can only create new custom_fields with the api.  creating/adding new subscribers is a feature that is not
# currently available in the api, but will be available sometime in the future.

# to create a new custom field:
echo "<hr>";
$custom_fields =  $aweber->loadFromUrl('https://api.aweber.com/1.0/accounts/../lists/../custom_fields');

print "BEFORE<br>";
foreach ($custom_fields as $custom_field) {
    print "<li>" . $custom_field->id . " " . $custom_field->name . "<br>";
}
$custom_fields->create(array('name' => 'favorite color'));


$custom_fields =  $aweber->loadFromUrl('https://api.aweber.com/1.0/accounts/../lists/../custom_fields');
print "AFTER<br>";
foreach ($custom_fields as $custom_field) {
    print "<li>" . $custom_field->id . " " . $custom_field->name . "<br>";
}

?>
<?php

include 'lib/include.php'

$oauth = NOAuth::get_for_twitter();
$request_token = $oauth->get_request_token();
if (!empty($request_token)) {

	header("Location: http://api.twitter.com/oauth/authorize?oauth_token=" . $request_token['oauth_token'])
	exit;


} else {
	echo "Twitter login disabled, could not get request token.";
}
?>

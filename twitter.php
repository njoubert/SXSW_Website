<?php
session_start();
include 'lib/include.php';

$oauth = NOAuth::get_for_twitter();

if (isset($_GET['oauth_token'])) {
	
	//step 3: receive an authorized token back
	$tok = $_GET['oauth_token'];
	$ver = $_GET['oauth_verifier'];

	$oauth_token = $_SESSION['oauth_token'];
	$oauth_token_secret = $_SESSION['oauth_token_secret'];
	
	if ($oauth_token != $tok) {
		echo "<p>The tokens does not match...</p>";
	}
	
	echo "<p>Session details: " . $r . ", " . $rts . "</p>";
	echo "<p>Received details:" . $tok . ", " . $ver . "</p>";

	//step 4: exchange request token for access token
	$access_token = $oauth->get_access_token($oauth_token, $oauth_token_secret, $ver);
	
	echo "THANK YOU FOR LOGGING IN WITH TWITTER.";
	print_r($access_token);
	
} else {
	
	//step 1: get a token
	$request_token = $oauth->get_request_token();
	if (!empty($request_token)) {
		//now, save the request_token and request_token_secret, we need is in step 4
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		//step 2: get the user to authorize the usage of this token
		$oauth->redirect_to_authorize($request_token['oauth_token']);
	} else {
		echo "Twitter login disabled, could not get request token.";
	}
	print_r($request_token);


}
?>